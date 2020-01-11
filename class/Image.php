<?php
// Content type

class Image {

    const TEMPODIRECTORY = 'assets/media/upload/';
    const EXT = array( 'jpg', 'png', 'jpeg' );
    const MAXSIZE = 250000;

    public function __construct( $image, $login ) {
        $this->login = $login;
        $this->image = $image;
        $this->sourcePic = $image['name'];
        $this->tempoPic = $image['tmp_name'];
        $this->infoPic = @getimagesize( $image['tmp_name'] );
        $this->extension = pathinfo( $this->sourcePic, PATHINFO_EXTENSION );
        $this->tempoPath = self::TEMPODIRECTORY.'img-'.$login.'.'.$this->extension;
        if ( $this->infoPic[0]>$this->infoPic[1] ) {
            $this->width = 80;
            $this->height = 50;
        } else {
            $this->width = 50;
            $this->height = 80;
        }
    }

    public function saveTempoImage() {
        if ( in_array( $this->extension, self::EXT ) ) {
            // save the tempo file
            $savedPic = move_uploaded_file($this->tempoPic,$this->tempoPath);  
            //create image from the string
            $openSavedPic = fopen($this->tempoPath,"r");    
            // resize this image
            $image = imagecreatefromjpeg($this->tempoPath);
            // create new empty image
            $tmpImage = imagecreatetruecolor($this->width, $this->height);
            imagecopyresampled($tmpImage,$image,0,0,0,0,$this->width,$this->height, $this->infoPic[0],$this->infoPic[1]);
            //  save the thumbanil version
            $pathOfPic=self::TEMPODIRECTORY.'thumb-'.$this->login.'.'.$this->extension;
            imagejpeg($tmpImage,$pathOfPic);
            // transform picture to binary before tu send in the database
            $dataImage = fopen($pathOfPic, 'r' );
            $dataImageSize = filesize($pathOfPic);
            $dataImageContents = fread($dataImage, $dataImageSize);
            fclose($dataImage);
             return  base64_encode($dataImageContents);
             
        



        } else {
            return 'false';
        }
    }

   
}

?>