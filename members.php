<?php
session_start();
require 'class/Autoloader.php';
Autoloader::register() ;
$html = new Html();
$form = new Form( $_POST );
$dbconnect = new Connect();
// checking if members existe with ajax

if(isset($_GET['action'])){
    if($_GET['action']=='checkMember'){
        $dbconnect = new Connect();
        $form = new Form( $_POST );
        $thelog = $_GET['login']; 
        //sanitization du log
        $thelogSan = $form->sanitize($thelog);
        if($thelog !== $thelogSan){
            echo "error";
        }else{
        echo $dbconnect->ifMemExist($thelog);
        }    
        die();        
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

<title>Document</title>
</head>

<body>

<?php



// checking data
if ( isset( $_POST['action'] ) ) {
    $action = $_POST['action'];
    switch( $action ) {
        case 'signup':
            $form->checkData( 'userlogin' );
            $form->checkData( 'userpassword' );    
            $numError = $form->ifError();
            // if not error we check if the user can enter
            $userExist = $dbconnect->enterChat($_POST['userlogin'],$_POST['userpassword']);
            if($userExist==1){
                $_SESSION["login"]=$_POST["userlogin"];
            }else {
                echo $userExist;
            }
            
            
        break;
        case 'signin':
        $form->checkData( 'login' );
        $form->checkData( 'password01' );
        $form->checkData( 'password02' );
        $form-> equalData( 'password01', 'password02' );
        // we check the result
        $numError = $form->ifError();
            // insertion of the data
            if ( $numError == 0 ) {
                //echo $numError;
                //$inserred = $dbconnect->newMember( $_POST['login'], $_POST['password01'] );
                // add login to session when finish
                $_SESSION["login"]=$_POST["login"];
            }
        break;
        }

        
    }

echo $html::openAside("asideElem",array("aside-elem"));
    if(isset($_SESSION["login"])) {
        //if we are logged we display the windows of members
        echo $html::openDiv("selectForm",array('row'));
        echo "members";
        echo $html::closeDiv();

    }else{ // if we are not logged
        //div for select the form login or singup
        echo $html::openDiv("selectForm",array('row'));
         echo $html::span("tabLogin",array("select-form","border-r"),"Login");
         echo $html::span("tabSignup",array("select-form"),"Sign-in");
         echo $html::closeDiv();   
        
         if(isset($_POST['action'])=='signup'){
            echo $html::openDiv( 'loginForm', array( 'show form' ) );
         }else if(!(isset($_POST['action']))){
            echo $html::openDiv( 'loginForm', array( 'show form' ) );
         }else {
            echo $html::openDiv( 'loginForm', array( 'hidden form' ) );
         }
        
        // LOGIN FORM
       
        $line = '<span class=\'form-title\'';
        $line .= '>SignUp</span>';
        echo $line;
        echo $form::openForm( 'formSignup', 'post', 'members.php' );
        echo $html::wrap( 'p', 'Choose a login : '.$form->input( 'userlogin', 'text' ) );
        echo $form->errorMessage( 'userlogin', 'use only alphanumeric caracter and 15max' );
        echo $html::wrap( 'p', 'Choose a password : '.$form->input( 'userpassword', 'password' ) );
        echo $form->errorMessage( 'userpassword', 'use only alphanumeric caracter and 15max' );
        echo $form->input( 'action', 'hidden', 'signup' );
        echo $html::wrap( 'p', $form->submit( 'Sign up' ) );
        echo $form::closeForm();
        //closeform
        echo $html::closeDiv();

        if(isset($_POST['action'])){
            if($_POST['action']=='signin'){
            echo $html::openDiv( 'signForm', array( 'show form' ) );
            }
        }else {
            echo $html::openDiv( 'signForm', array( 'hidden form' ) );
        }
        // SIGNIN FORMS

        $line = '<span class=\'form-title\'';
        $line .= '>SignUp</span>';
        echo $line;
        echo $form::openForm( 'formSign', 'post', 'members.php' );
        echo $html::wrap( 'p', 'Choose a login : '.$form->input( 'login', 'text' ) );
        echo $form->errorMessage( 'login', 'use only alphanumeric caracter and 15max' );
        echo $html::wrap( 'p', 'Choose a password : '.$form->input( 'password01', 'password' ) );
        echo $form->errorMessage( 'password01', 'use only alphanumeric caracter and 15max' );
        echo $html::wrap( 'p', 'Comfirm password : '.$form->input( 'password02', 'password' ) );
        echo $form->errorMessage( 'password02', 'use only alphanumeric caracter and 15max' );
        echo $form->input( 'action', 'hidden', 'signin' );
        echo $html::wrap( 'p', $form->submit( 'Sign in' ) );
        echo $form::closeForm();
        //closeform
        echo $html::closeDiv();
    }
echo $html::closeAside();
?>
<?php
//scripts
echo $html::addscript( 'assets/js/actions.js' );

?>

</body>

</html>