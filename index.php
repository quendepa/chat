<?php
session_start();

$wearelogged="";
   

// class
require 'class/Autoloader.php';
Autoloader::register() ;
$html = new Html();
$form=new Form($_POST);
$connect = new Connect();

// check when we receive post data
if($_POST){
    //var_dump($_POST);
    if(isset($_POST['login'])){
         // we check if the data is correct
         $errormessageLogin=Array();
         array_push($errormessageLogin,$form->checkData('login')); // check if empty,if length correct, if have no incorrect caracter;
         array_push($errormessageLogin,$form->checkData('password')); // check if empty, if correct length, if have no incorrect caracter;
         $error = $form->ifError(); // we check is wa have some error
         if($error==0){ /// if no error we checking the login and the password
            $memberExist = $connect->ifMemExist($_POST['login']); // we check if the login existe
            if($memberExist=="true"){
                //on regarde si il est deja connecter ailleur ou pas
                $isOnLine=$connect->isonline($_POST['login']);
                if( $isOnLine==1 && !(isset($_POST["login"]))){                
                 $error=1;
                    array_push($errormessageLogin,"This user is yet online ! <br>On another computer or in a other browser");
                }else {
                    // if this user is not online, we can connect it
                    $weconnect=0;
                    $weconnect = $connect->enterChat($_POST['login'],$_POST['password']);
                    echo $weconnect;
                    if( $weconnect ==1 ){                        
                        $_SESSION['login']=$_POST['login'];
                        $userIdm = $connect->getIdmUser($_SESSION['login']);
                        $_SESSION['idm']=$userIdm[0];
                        //echo $_SESSION['login'];
                        $_POST=array();
                        $wearelogged="true"; 
                    }else if( $weconnect==2) {
                        $error=1;
                        array_push($errormessageLogin,"Your are connected yet on another computer or browser !");
                    }else {
                        $error=1;
                        array_push($errormessageLogin,"Wrong password !");
                    }
                 }
            }else {
                // member doesn't exist
                $error=1;
                array_push($errormessageLogin,"This login with this password doesn't existe.<br>Enter the right login and password or Signup");
            }
         }
    }
    // NEW USER CHECKING DATA
    if(isset($_POST['newlogin'])){
        // we check if the data is correct
        $errormessageSign=Array();
        array_push($errormessageSign,$form->checkData('newlogin')); // check if empty,if length correct, if have no incorrect caracter;
        array_push($errormessageSign,$form->checkData('password01')); // check if empty, if correct length, if have no incorrect caracter;
        array_push($errormessageSign,$form->checkData('password02')); // check if empty, if correct length, if have no incorrect caracter;
        array_push($errormessageSign,$form->equalData('password01','password02')); // check if the two password is the same;
        $error = $form->ifError(); // we check is we have some error
        if($error==0){
            // we checked if the login existe 
            $memberExist = $connect->ifMemExist($_POST['newlogin']);
            if($memberExist=="false"){
                // we cant add the new user in the database
                $adduser = $connect->newMember($_POST['newlogin'],$_POST['password01']);
                if($adduser==1){
                    $_SESSION['login']=$_POST['newlogin'];
                    $userIdm = $connect->getIdmUser($_SESSION['login']);
                    $_SESSION['idm']=$userIdm[0];
                    $_POST=array();
                    $wearelogged="true";
                }else {
                    $error=1;
                    array_push($errormessageSign,"Not possible to create the new user. Contact the webmaster");
                }
            }else {
                $error=1;
                array_push($errormessageSign,"This login exist.<br>Please choose another one");
            }
        }
    }
    if(isset($_POST['userMessage'])){
        $login = $_SESSION['login'];
        $message = $_POST['userMessage'];
        //SANITIZE MESSAGE HERE
        $messageSanat = $form::sanitiMess($message);
        //echo $messageSanat;
        if($messageSanat !=="1"){
        
        $getIdmUser= $connect->getIdmUser($login);
        $idmUser=$getIdmUser[0];
        $connect->insertNewMessage($idmUser,$messageSanat);
        }else {
            $errorInMessage="You may not use a html tag !";
        }

    
    }
}


// picture managment

if(isset($_FILES['myfile'])){
    $img = new Image($_FILES['myfile'],$_SESSION['login']);
    $imageInBinary = $img->saveTempoImage();
    // we push the picture in the database
    $connect->addAvatar($imageInBinary,$_SESSION['login']);
    
       
    
}


// AJAX REQUEST

 if(isset($_GET['action'])){    
         $action = $_GET['action'];
        switch ($action){         
         case "logout": 
            $logi= $_SESSION['login'];    
            session_unset();
            session_destroy();
            $_POST=array();
            $wearelogged="false";
            $connect->logout($logi);
            echo $logi;
            die();
         break;
         case "sendPic": 
            $fileAdd = $connect->sendPicture($_GET['image'],$_SESSION['login']);            
            die();
         break;
         case "getMessage": 
            $index = $_GET['lastmessage'] ;     
            $allNewMessage = $connect->getNewMessages($index);
          // echo $allNewMessage[0][0]; 
           foreach ($allNewMessage as $key => $value) {
            if(isset($_SESSION['idm'])){
                if($allNewMessage[$key]['idm']==$_SESSION['idm']){
                    $alignement="align-message-right";
            }else {
                $alignement="align-message-left";
            }
            }else {
                $alignement="align-message-left";
            }
            $idMessage="mess".$allNewMessage[$key]['id'];
            $line = $html->openDiv($idMessage,array("message-item"));   
            $line.= $html->span(null,array("message-text ",$alignement),$allNewMessage[$key]['texte']);                
            $line.= $html->closeDiv();
            echo $line;
               //echo $allNewMessage[$key]['texte'];
           }  
            die();
         break;
         case "getMember":
            $allConnected = explode(",",$_GET['allconnected']);
            $newMembers = $connect->getAllMembers();
            foreach ($newMembers as $key => $value) {
               
                    $lines = $html->openDiv("mem_".$value['idm'],array("member-list-item"));
                    $lines.=$html->openDiv(null,array("member-picture"));
                        $lines.="<img src=\"data:image/jpg;base64,".$value['mem_picture']."\" class=\"img\">";
                    $lines.= $html->closeDiv();
                    $lines.=$html->span(null,array("member-name"),$value['mem_login']);
                    $lines.= $html->closeDiv();
                    echo $lines;
                
            }
            
            die();
         break;
        
   }    

 }



?>

<!DOCTYPE html>
<html lang = 'en'>

<head>
<?php
echo $html::addInHead( 'meta', array( 'charset'=>'UTF-8' ) );
echo $html::addInHead( 'meta', array( 'name'=>'viewport', 'content'=>'width=device-width, initial-scale=1.0' ) );
echo $html::addInHead( 'meta', array( 'http-equiv'=>'X-UA-Compatible', 'content'=>'ie=edge' ) );
echo $html::addInHead( 'link', array( 'rel'=>'stylesheet', 'href'=>'assets/css/style.css' ) );
?>

<title>Chat</title>
</head>

<body>
<div class="container">
    <?php 
    include('members.php');
    ?>
    <section id="messageFrame">
    <!-- include here the message php files -->
    <?php include "messages.php"; ?>
    </section>
</div>

    <script src="assets/js/actions.js"></script>
</body>

</html>