<?php

class Form {

    const  MININPUT = 8;
    const  MAXINPUT = 15;

    public function __construct($datas) {
        $this->data = $datas;
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
    public static function openForm( $id, $method, $action ) {
        return "<form id=\"".$id."\" action=\"".$action."\" method=\"".$method."\">";
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

    public function sanitize($line){
        $line = filter_var($line,FILTER_SANITIZE_STRING);
        $pattern = preg_replace("/[\W]/", "", $line);
        return $pattern;
    }

    //error message

    
    public function checkData($name){
        //checking login
        if($this->data[$name] == " ") { // test if empty
            array_push($this->errorlist,$name);
        }else if (strlen($this->data[$name])< self::MININPUT || strlen($this->data[$name])> self::MAXINPUT ){//$test the length
            array_push($this->errorlist,$name);
            }else if($this->data[$name]!==$this->sanitize($this->data[$name])){ // test after sanitization
                array_push($this->errorlist,$name);
            }   
    }
    
    public function equalData($val1,$val2){
        if(strcmp($this->data[$val1],$this->data[$val2])!==0){
            array_push($this->errorlist,$val1);
            array_push($this->errorlist,$val2);
        }
    }

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