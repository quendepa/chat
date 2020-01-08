<?php
// Content type
header( 'Content-Type: image/jpeg' );

class Image {

    public  function goodImage( $image ) {
        $info = @getimagesize( $image );

       // var_dump( $info );
        if ( $info[0]>$info[1] ) {
            // landscape
            $w = 180;
            $h = 150;

        } else {
            //portrait
            $w = 150;
            $h = 180;
        }
        // Load
        /*list($width, $height) = getimagesize($image);
        $thumb = imagecreatetruecolor( $w, $h );
        $source = imagecreatefromjpeg($image);
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $w, $h, $width, $height);
        $myNewImage = imagejpeg($thumb);
        imagedestroy($thumb);
        imagedestroy($source);
        return "ok";*/
    }

}

?>