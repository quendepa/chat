<?php 
class Html{
    //@param array of attributes
    // return string
    public static  function addInHead($type,$attributes){
        $line="<$type ";
        foreach ($attributes as $key => $value) {
            $line.=$key."=\"".$value."\"";
        }
        $line.=">";
        return $line;
   }

    //@param array of attributes
    // return string
    public static  function addscript($adress){
         return ("<script src=\"".$adress."\"></script>");
    }

    //@param tag - string
    //return string
    public static function wrap($tag="p",$line) {
        return "<".$tag.">".$line."</".$tag.">";
    } 

    public static function errorMessage($mess){
        return "<span class=\"error \">".$mess."</span>";
    }
    public static function button($id,$class,$text){
        return "<button id=\"$id\" class=\"".implode(" ",$class)."\">$text</button>";
    }

    public static function openDiv($id=null,$class){
        $line = "<div id=\"".$id."\" ";
        $line.="class=\"".implode(" ",$class)."\"";
        $line.=" >";
        return $line;
        
    }
    public static function closeDiv(){
        return "</div>";
    }
    public static function openAside($id=null,$class){
        $line = "<aside id=\"".$id."\" ";
        foreach ($class as $key => $value) {
            $line.=" class=\"".$value."\"";
        }
        $line.=" >";
        return $line;
        
    }
    public static function closeAside(){
        return "</aside>";
    }

    public static function span($id=null,$class,$text){
        $line="<span id=\"".$id."\" class=\"";
        foreach ($class as $key => $value) {
            $line.=$value." ";
        }
        $line.="\" >".$text."</span>";
        return $line;
    }

}
?>