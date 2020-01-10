<?php

class Form {
    const  MAXINPUTMESSAGE = 250;
    const  MININPUT = 8; // minimum for the length of password and login
    const  MAXINPUT = 15; // maximum for the length of the password and the login

    public function __construct($datas) {
        $this->data = $datas; // data that we receive in the $POST
        $this->errorlist=Array();       
   
    }

    //@param string
    //return string
    private function getValue( $index ) {
        if ( isset( $this->data[$index] ) ) {
            return $this->data[$index];
        }
    }
    // @param string
    // return string
    public static function openForm( $id, $method, $action,$enctype=null ) {
        return "<form enctype=\"".$enctype."\" id=\"".$id."\" action=\"#\" method=\"".$method."\">";
    }
    //@param none
    // return string
    public static function closeForm() {
        return '</form>';
    }
    //@param string
    //return string

    public function input( $name, $type, $value = null ) {
        ( $value == null ) ? $value = $this->getValue( $name ) : $value = $value;
        return "<p><input type=\"".$type."\" name=\"".$name."\" value=\"".$value."\" required></p>";
    }
    //@param string (text in button)
    //return string
    public function submit($text) {
        return '<button class=\'btn\'>'.$text.'</button>';
    }

    //@param string
    //return string
    public function sanitize($line){
        $line = filter_var($line,FILTER_SANITIZE_STRING);
        $pattern = preg_replace("/[\W]/", "", $line);
        return $pattern;
    }

   
    //@param $element string
    //return integer/bollean
    public function checkData($name){
        //checking login
        if($this->data[$name] == "") { // test if empty
            array_push($this->errorlist,$name);
            return "Empty data : $name<br>";
        }else if (strlen($this->data[$name])< self::MININPUT || strlen($this->data[$name])> self::MAXINPUT ){//$test the length
            array_push($this->errorlist,$name);
            return "Please enter a valid $name min:".self::MININPUT." - max:".self::MAXINPUT."<br>";
            }else if($this->data[$name]!==$this->sanitize($this->data[$name])){ // test after sanitization
                array_push($this->errorlist,$name);
                return "Please use only alphanumeric character for the $name<br>";    
        
            }   
    }

    public function sanitMessage($userMess){
        $erreur=[];
        if(trim($userMess)=="") { // test if empty
            array_push($erreur,"Message empty");
            echo "Empty data ";            
        }else if (strlen($userMess)> self::MAXINPUTMESSAGE ){//$test the length
            array_push($erreur,"Message empty");
            echo "Please enter max 250 caract".self::MAXINPUTMESSAGE."<br>";
            }else if($userMess!==$this->sanitize($userMess)){  // test after sanitization
                array_push($erreur,"Please use only alphanumeric character");                 
        
            }   

    }
    
    //function to check the equality between two variable
    //@param string / string
    //return bollean/int
    public function equalData($val1,$val2){
        if(strcmp($this->data[$val1],$this->data[$val2])!==0){
            array_push($this->errorlist,$val1);
            array_push($this->errorlist,$val2);
            return "The 2 passwords are not equal <br>";
        }
    }

    public function downloadfile($file){
        return $file;        
    }

    
    //@param string/string
    //return string
    public function errorMessage($name,$text){
        if(isset($this->data['action'])){
            if(in_array($name,$this->errorlist)==1){
                return "<span class=\"error\">$text</span>";
            }
        }
    }

    public function ifError() {
        //  var_dump($this->errorlist); 
        return sizeof($this->errorlist);
    }

}

?>