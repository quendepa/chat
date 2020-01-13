
<?php 

echo $html::openDiv("messageScreen",array("div"));
    echo $html::openDiv(null,array("roww"));
        echo $html::openDiv("themessages",array("messScreen"));
            // get all messages
            $allMessages = $connect->getAllMessages();
            foreach ($allMessages as $key => $value) {
                if(isset($_SESSION['idm'])){
                    if($value['idm']==$_SESSION['idm']){
                        $alignement="align-message-right";
                }else {
                    $alignement="align-message-left";
                }
                }else {
                    $alignement="align-message-left";
                }
                $idMessage="mess".$value['id'];
                echo $html->openDiv($idMessage,array("message-item"),$value['mem_login']);   
                    echo $html->span(null,array("message-text ",$alignement),$value['texte']);                
                echo $html->closeDiv();
            }

        echo $html->closeDiv();
    echo $html->closeDiv();
    if(isset($_SESSION['login'])){      
    echo $html->openDiv("messScreenForm",array("row"));
        echo $form->openForm("newMessForm","post","index.html");
            echo $form->input("userMessage","text");
            // if message error we display it
            if(isset($errorInMessage)){
            echo $html::span("errorMessage",array("error-message"),$errorInMessage);
            }
            echo $form->submit("SendMessage"); 
        echo $form->closeForm();
    echo $html->closeDiv();
        }
            
echo $html->closeDiv();

?>
