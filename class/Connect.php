<?php

class Connect{

    const HOST = "localhost";
    const DBNAME = "chat";
    const USER = "root";
    const PASS = "root";

    private function connection(){
        try {
            return new PDO('mysql:host='.self::HOST.';dbname='.self::DBNAME, self::USER, self::PASS);
        } catch (PDOException $e) {
            return "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function ifMemExist($login){
        try{
        $db=$this->connection();
        $db->beginTransaction();
        $query=$db->query("SELECT login FROM members WHERE login='$login' ")->fetch();
        ($query>1) ? $exist = "true" : $exist = "false";
        return $exist;
        }catch(EXception $e){
            return "Error :".$e->getMessage();
        }
        
           
    }

    private function hashed($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function newMember($nom,$passWord){
       // we hashed the password before to insert
       $hashPass = $this->hashed($passWord);
       try{
       $db = $this->connection(); // connection
       $db->beginTransaction();
       $query=$db->query("INSERT INTO members (login,password,online) VALUES ('$nom','$hashPass','0')");
       return $db->commit();
       }catch(Exception $e){
           
           return "Failled : ".$e->getMessage();
       }
       
    }

    public function enterChat($login,$password){
        try {
            $db=$this->connection();
            $db->beginTransaction();
            $query=$db->query("SELECT login,password FROM members WHERE login='$login'");
            $existe = $query->rowCount();
            if($existe){
            foreach ($query as $row) {
                $dblogin = $row['login'];
                $dbpass = $row['password'];
                        }               
             if(password_verify($password,$dbpass)){
                return true;
             }else {
                 return "Wrong password with this login !";
             }
            
         }else{
           return "Wrong user !";
         }
}catch(Exceptions $e){
            echo "Error :".$e->getMessage();
        }
    }




}

?>