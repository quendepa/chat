<?php

class Connect {

    const HOST = 'localhost';
    const DBNAME = 'chat';
    const USER = 'root';
    const PASS = 'root';

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
            $query = $db->query( "SELECT login FROM members WHERE login='$login' " )->fetch();
            ( $query>1 ) ? $exist = 'true' : $exist = 'false';
            return $exist;
        } catch(Exception $e ) {
            return 'Cannot verified if user exist : Error ->'.$e->getMessage();
        }
    }

    private function hashed( $password ) {
        return password_hash( $password, PASSWORD_DEFAULT );
    }

    public function newMember( $nom, $passWord ) {
        // we hashed the password before to insert
        $hashPass = $this->hashed( $passWord );
        try {
            $db = $this->connection();
            // connection
            $db->beginTransaction();
            $query = $db->query( "INSERT INTO members (login,password,online) VALUES ('$nom','$hashPass','1')" );
            return $db->commit();
        } catch( Exception $e ) {
            return 'insertion of the new member Failed : Error-> '.$e->getMessage();
        }

    }

    public function online($name){                   
            $db = $this->connection();
            $db->beginTransaction();
            //first we check if the user is yet connected on other device;
            $yetconnect = $db->query("SELECT * FROM members WHERE (login='$name' AND online=1)");
            $connected = $yetconnect->rowCount();
            //echo $connected;
            if($connected == 0){
            $db = $this->connection();
            $db->beginTransaction();
            $query = $db->query("UPDATE members SET online='1' WHERE login='$name' ");
            return $db->commit();            
            }         
    }

    // put offline the user when he want ti logout
    private function offline($name){
        try{
            $db = $this->connection();
            $db->beginTransaction();
            $query = $db->query("UPDATE members SET online='0' WHERE login='$name' ");
            return $db->commit();
        }catch(exceptions $e){
            return "Error to put members online :".$e->getMessages();
        }
    }
    public function logout($user){
        //return "ok";
      $this->offline($user);
    }


    // fonction to login the user and change the value of the online in the database
    public function enterChat( $login, $password ) {
        try{                    
            $db=$this->connection();
            $db->beginTransaction();
            $query=$db->query("SELECT * FROM members WHERE login='$login'");
            $num= $query->rowCount();            
            if($query->rowCount()==1){
                foreach ( $query as $row ) {
                    $dblogin = $row['login'];
                    $dbpass = $row['password']; // password hashed from database
                }
                $passHash = password_verify($password,$dbpass);
                if($passHash==1){
                   return $this->online($login);      // change value in the database                                  
                }
            } 
        }catch(Exception $e){
            return "Connection of user Failed: Error-> ".$e->getMessages();
        }
    }

    // function tio checked if the user us currently online on another browser or computer
    public function isonline($login){
       $db=$this->connection();
       $db->beginTransaction();
       $query=$db->query("SELECT online FROM members WHERE (login='$login' AND online='1') ")->fetch();
       return $query;
    }

    
    // function get All members to display in the list on the screen
    public  function getAllMembers(){
        try{
            $db= $this->connection();
            $db->beginTransaction();
            $query=$db->query("SELECT * FROM members WHERE online='1' ")->fetchAll();
            return $query;
        }catch(Exceptions $e){
            return "Error get members list :".$e->getMessages();
        }
    }
}

?>