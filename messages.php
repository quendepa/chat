
<?php 

echo $html::openDiv("messageScreen",array("div"));
    echo $html::openDiv(null,array("roww"));
        echo $html::openDiv("themessages",array("messScreen"));
            // get all messages
            $allMessages = $connect->getAllMessages();
            foreach ($allMessages as $key => $value) {
                    echo $value['texte']."<br>";
                          }

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
