<?php
// afficher le formulaire

// ouvrir div messageScreen
    //ouvrir formulaire
        //div allmessage
            // allr chercher les messages et les afficher
                // si idm = session idm float right
                 //sinon float left

        // div input message + bouton envoie

    //fermer le formulaire
// fermer div messageScreen

?>
<?php 

$form=new Form($_POST);
echo $html::openDiv("messageScreen",array("div"));
    echo $html::openDiv(null,array("roww"));
        echo $html::openDiv("themessages",array("messScreen"));
            // get all messages

           
            
        $idm= $connect->getIdmUser($_SESSION['login']); 
        echo $html::openDiv(null,array("message-container"));
        $allMessages = $connect->getAllMessages($_SESSION['login']);  
        foreach ($allMessages as $key => $value) {  
          if ($value['idm'] == $idm[0]){             
                echo $html::openDiv(null,array("messages-to-right"));
                echo $html::span(null,array("toright"),$value['texte']);
                echo $html::closeDiv();
          }else{
                
                echo $html::openDiv(null,array("messages-to-left"));
                echo $value['texte'];
                echo $html::closeDiv();
            }
            
            
        }
        echo $html::closeDiv();


        echo $html->closeDiv();
    echo $html->closeDiv();
    echo $html->openDiv("messScreenForm",array("row"));
        echo $form->openForm("newMessForm","post","index.html");
            echo $form->input("userMessage","text").$form->submit("SendMessage"); 
            // AJOUTER ICI UNE BALISE P TYPE ERROR SI VARIABLE ERROR DE SANATIZATION EXISTE
        echo $form->closeForm();
    echo $html->closeDiv();
echo $html->closeDiv();

?>
