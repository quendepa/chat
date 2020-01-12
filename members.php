<?php
        echo $html::openAside("leftFrame",array("aside"));
        // IF WE ARE LOGGED WE DISPLAY THE LIST OF MEMBERS IN THE CHAT
        if(isset($_SESSION['login'])){
        // MEMBERS FRAME
        echo $html::openDiv("membersFrame",array("row"));
            echo $html::span(null,array("title"),"Connected members");           
            echo $html::openDiv("membersList",array("members"));      
           
            $listOfMembers = $connect->getAllMembers();
            foreach ($listOfMembers as $key => $value) { // display list of members
                // afficher avatar;
                $lines = $html->openDiv("mem_".$value['idm'],array("member-list-item"));
                    $lines.=$html->openDiv(null,array("member-picture"));
                        $lines.="<img src=\"data:image/jpg;base64,".$value['mem_picture']."\" class=\"img\">";
                    $lines.= $html->closeDiv();
                    $lines.=$html->span(null,array("member-name"),$value['mem_login']);
                $lines.= $html->closeDiv();
                echo $lines;

            }
            
                
            echo $html::closeDiv();
            echo $html::openDiv(null,array("footer"));
                echo $html::button("logout",array("button"),"logout");
                echo $html::button("avatar",array("button","avatar"),"add Avatar");
            echo $html::closeDiv();
        echo $html::closeDiv();
        // file downloader
        echo $html->openDiv("downloaderFile",array("modal hidden"));   
                echo $form->openForm("avatarForm","post","index.php","multipart/form-data");
                echo $form->input("myfile","file");
                echo $html->span("symbolClose",array("close"),null);
                echo $html->button("sendImage",array("button","Send Image"),"Send Picture");
                echo $form->closeForm();
        echo $html->closeDiv();       


        }else {


        // FRAME OF THE TWO FORMS
        echo $html::openDiv("tabMenu",array("tab"));
            echo $html::span("tabsignup",array("tabform"),"Sign-in");
            echo "<span class=\"separator\">|</span>";
            echo $html::span("tabsignin",array("tabform"),"Sign-up");
        echo $html::closeDiv(); 
            
             //IF AREN'T POST DATA OR POST[LOGIN]  EXIST
             if(sizeof($_POST)==0 OR isset($_POST['login'])){
                echo $html::openDiv("signupFrame",array("form","show"));
             }else {
                echo $html::openDiv("signupFrame",array("form","hidden"));
             }

            //  SIGNUP FRAME  /////////////////////////////////////////////////
            
                echo $html::span(null,array("title","clear"),"Sign-in"); 
                if(isset($error) AND isset($errormessageLogin)){
                if($error>0){
                echo "<div class=\"error\">".$html::span(null,array("span"),implode($errormessageLogin))."</div>";
                }
            }
                echo $form::openForm("loginForm","post","index.php");   
                    echo $html::wrap("p","Login :").$form->input('login','text',null);
                    echo $html::wrap("p","Password :").$form->input('password','password',null);
                    echo $html::button(null,array('btn'),"Enter Chat");
                echo $form::closeform();
            echo $html::closeDiv();

             //IF POST[LOGIN] DOESN'T EXIST AND POST IS SET
             if(isset($_POST["newlogin"])){
                echo $html::openDiv("signinFrame",array("form","show"));
             }else {
                echo $html::openDiv("signinFrame",array("form","hidden"));
             }
            // SIGNIN FRAME  //////////////////////////////////////////////////////
                echo $html::span(null,array("title","clear"),"Sign-up");  
                if(isset($error) AND isset($errormessageSign)){
                 if($error>0){
                echo "<div class=\"error\">".$html::span(null,array("span"),implode($errormessageSign))."</div>";
                }
            }
                echo $form::openForm("signForm","post","index.php");   
                    echo $html::wrap("p","Login :").$form->input("newlogin","text",null);
                    echo $html::wrap("p","Password :").$form->input("password01","password",null);
                    echo $html::wrap("p","Confirm Password :").$form->input("password02","password",null);
                    echo $html::button(null,array("btn"),"signup");
                echo $form->closeForm();
            echo $html::closeDiv();
             }
        
        
       
   echo $html::closeAside();


    ?>