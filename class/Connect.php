<?php

class Connect {

    const HOST = 'localhost';
    const DBNAME = 'chat';
    const USER = 'root';
    const PASS = 'root';
    const MAXUPLOAD = 30000;


    private function connection() {
        try {
            return new PDO( 'mysql:host='.self::HOST.';dbname='.self::DBNAME, self::USER, self::PASS );
        } catch ( PDOException $e ) {
            return 'Cannot connected on database : Error -> ' . $e->getMessage() . '<br/>';
            die();
        }
    }

    public function ifMemExist($login) {
        try {
            $db = $this->connection();
            $db->beginTransaction();
            $query = $db->prepare('SELECT mem_login FROM members WHERE mem_login= :mem_login');
            $query->bindParam(':mem_login',$login);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            ( $query->rowCount()==1 ) ? $existe="true" : $existe="false";
            return $existe;
            die();
        } catch(Exception $e ) {
            return 'Cannot verified if user exist : Error ->'.$e->getMessage();
        }
    }

    private function hashed( $password ) {
        return password_hash( $password, PASSWORD_DEFAULT );
    }

    private function getLastIdm(){
        $db=$this->connection();
        $db->beginTransaction();
        $sql = "SELECT idm FROM members";
        $query=$db->prepare($sql);
        $query->execute();
        return $query->rowCount();
    }

    public function newMember( $nom, $passWord ) {
        // we hashed the password before to insert
        $hashPass = $this->hashed( $passWord );
        $onlineTag = "1";
        $image_data=file_get_contents('assets/media/nopic.png'); 
        $blob=base64_encode($image_data);
        $idm=$this->getLastIdm()+1;
        $tokken=session_id();
        try {
            $db = $this->connection();
            $db->beginTransaction();
            $sql = "INSERT INTO members VALUES (:myid,:mylog,:mypass,:blob,:myonline,:mysess) ";
            $query=$db->prepare($sql);
            $query->bindParam(':myid',$idm);
            $query->bindParam(':mylog',$nom);
            $query->bindParam(':mypass', $hashPass);
            $query->bindParam(':blob', $blob,PDO::PARAM_LOB);
            $query->bindParam(':myonline', $onlineTag);
            $query->bindParam(':mysess', $tokken);
            $query->execute();
            if($db->commit()){
                return "1";
            }
            //return $query->rowCount();
            //echo "\nPDO::errorInfo():\n";
            //print_r($db->errorInfo());

        } catch( Exception $e ) {
            return 'insertion of the new member Failed : Error-> '.$e->getMessage();
        }

    }

    //function to change the variable online in the database
    //@param string($name)
    //return bollean/int
    public function online($name)  { 
            $tokken=session_id();
            $onlineTag = 1;
            $db = $this->connection();
            $db->beginTransaction();
            //first we check if the user is yet connected on other device;
            $yetconnect = $db->prepare(' SELECT * FROM members   WHERE mem_login=:mem_login AND mem_online=:mem_online AND mem_tokken=:mem_tokken ');
            $yetconnect->bindParam(':mem_login',$name);
            $yetconnect->bindParam(':mem_online',$onlineTag);
            $yetconnect->bindParam(':mem_tokken',$tokken);
            $yetconnect->execute();
            $db->commit();
            $result = $yetconnect->rowCount();
            if($result == 0){
                $db = $this->connection();
                $db->beginTransaction();
                $query = $db->prepare('UPDATE members SET mem_tokken=?,mem_online=? WHERE mem_login=? ');
                $query->execute(array($tokken,$onlineTag,$name));
                return $db->commit ();                
            } else {
                return "2";
            }        
    }

    // put offline the user when he want ti logout
    private function offline($name){
        $offlineTag=0;
        try{
            $db = $this->connection();
            $db->beginTransaction();
            $query = $db->prepare(" UPDATE members SET mem_online=? WHERE mem_login=? ");
            $query->execute(array($offlineTag,$name));
            return $db->commit();
        }catch(Exception $e){
            return "Error to put members online :".$e->getMessage();
        }
    }
    public function logout($user){
        //return "ok";
      $this->offline($user);
    }


    // fonction to login the user and change the value of the online in the database
    //@param string/string
    // return bollean/int 
    public function enterChat( $login, $password ) {
        try{                    
            $db=$this->connection();
            $db->beginTransaction();
            $query=$db->prepare(' SELECT * FROM members WHERE mem_login=:mem_login ');
            $query->bindParam(':mem_login',$login);
            $query->execute();
            $db->commit();
            if($query->rowCount()==1){
                foreach ( $query as $row ) {
                    $dblogin = $row['mem_login'];
                    $dbpass = $row['mem_password']; // password hashed from database
                }
                $passHash = password_verify($password,$dbpass);
                if($passHash==1){
                   return $this->online($login);      // change value in the database                                  
                }
            } 
        }catch(Exception $e){
            return "Connection of user Failed: Error-> ".$e->getMessage();
        }
    }

    // function to checked if the user us currently online on another browser or computer
    public function isonline($login){
       $onlineTag=1;
        $db=$this->connection();
       $db->beginTransaction();
       $query=$db->prepare("SELECT * FROM members WHERE mem_login=:mem_login ");
       $query->bindParam(':mem_login',$login);
       $query->execute();
       $queryFetch = $query->fetchAll();

       if(($queryFetch[0]['mem_online']==1) AND ($queryFetch[0]['mem_tokken']==session_id())){
           return 1;
       }
       else{
           return 0;
       }

    }

    
    // function get All members to display in the list on the screen
    //@param : no param
    // return array;
    public function getAllMembers(){
        try{
            $db= $this->connection();
            $db->beginTransaction();
            $query=$db->query("SELECT * FROM members WHERE mem_online='1' ")->fetchAll();
            return $query;
        }catch(Exception  $e){
            return "Error get members list :".$e->getMessage();
        }
    }

    public function sendPicture($image,$pseudo){
        $file =  is_uploaded_file($_FILES['$image']);
        return $file;
    }

    public function getIdmUser($login){
        try{
            $db = $this->connection();
            $db->beginTransaction();
            $query = $db->query("SELECT idm FROM members WHERE mem_login='$login' ")->fetch();
            return $query;            
        }catch(Exception $e){
            return $e->getMessage();
        }
    }



    public function insertNewMessage($idm,$mess){
        $today = date("Y-m-d H:i:s");
        try{
        $db = $this->connection();
       // $db->beginTransaction();
        $query = $db->prepare( ' INSERT INTO allmessage (idm,texte,senddate) VALUES (:idm,:texte,:senddate)' );
        $query->execute(array(
            'idm' => $idm,
            'texte' => $mess,
            'senddate' => $today
    ));
        
        }catch(Exception $e){
            return $e->getMessage();
        }

    }

    public function getAllMessages(){
        try{
            $db= $this->connection();
            $db->beginTransaction();
            $query=$db->query("SELECT * FROM allmessage JOIN members")->fetchAll();
            return $query;
        }catch(Exception $e){
            return "Error get members list :".$e->getMessage();
        }
    }
    public function getNewMessages($index){
        try{
            $db= $this->connection();
            $db->beginTransaction();
            $query=$db->query("SELECT * FROM allmessage WHERE (id>$index)")->fetchAll();
            return $query;
        }catch(Exception $e){
            return "Error get members list :".$e->getMessage();
        }
    }
 
    public function addAvatar($mem_picture,$mem_login){      
        try{
            $db = $this->connection();
            $sql="UPDATE members SET mem_picture=:mem_picture WHERE mem_login=:mem_login";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':mem_picture', $mem_picture, PDO::PARAM_LOB);
            $stmt->bindParam(':mem_login', $mem_login); 
            $stmt->execute();                     
            }catch(Exception $e){
            echo $e->getMessage();
        }
    }

 


   

}

?>