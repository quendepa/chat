<?php
session_start();
$wearelogged="";
//var_dump($_POST);
//  var_dump($_SESSION);    

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
                if(is_array($isOnLine) && !(isset($_POST["login"]))){                
                 $error=1;
                    array_push($errormessageLogin,"This user is yet online ! <br>On another computer or in a other browser");
                }else {
                    // if this user is not online, we can connect it
                    if($connect->enterChat($_POST['login'],$_POST['password'])){                        
                        $_SESSION['login']=$_POST['login'];
                        echo $_SESSION['login'];
                        $_POST=array();
                        $wearelogged="true"; 
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
        //echo $isConnected;
    }
    
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
                    $_POST=array();
                    $wearelogged="true";
                }
            }else {
                $error=1;
                array_push($errormessageSign,"This login exist.<br>Please choose another one");
            }
        }
    }
    if(isset($_POST['userMessage'])){
        $login = $_SESSION['login'];
        //SANITIZE MESSAGE HERE
            //A FAIRE
        // SI ERROR DE SANITIZE CREER  VARIABLE D ERREUR SINON AJOUTER DANS LA BASE
            //A FAIRE
        // SEQUENCE D4AJOUT DANS LA BASE
        $getIdmUser= $connect->getIdmUser($login);
        $idmUser=$getIdmUser[0];
        $inserMessage = $connect->insertNewMessage($idmUser,$_POST['userMessage']);
    
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
            //echo $logi;
            die();
         break;
         case "sendPic": 
            $fileAdd = $connect->sendPicture($_GET['image'],$_SESSION['login']);            
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
<div class="container dflex">
    <?php 
    include('members.php');
    ?>
    <section id="messageFrame" class="">
    <!-- include here the message php files -->
    <?php include "messages.php"; ?>
    </section>
</div>

    <script src="assets/js/actions.js"></script>
</body>

</html>