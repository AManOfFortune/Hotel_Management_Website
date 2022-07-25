<?php
    //Starts or continues a session on every page that is loaded
    session_start();

    //Connects to the database on every page that is loaded
    include_once($_SERVER["DOCUMENT_ROOT"]. "/Semesterprojekt/db/dbconnect.php");

    //Function to create Thumbnails (for serviceticket upload)
    function makeThumbnails($updir, $img, $id, $MaxWe=720, $MaxHe=480){
        $arr_image_details = getimagesize($img); 
        $width = $arr_image_details[0];
        $height = $arr_image_details[1];
    
        $percent = 100;
        if($width > $MaxWe) $percent = floor(($MaxWe * 100) / $width);
    
        if(floor(($height * $percent)/100)>$MaxHe)  
        $percent = (($MaxHe * 100) / $height);
    
        if($width > $height) {
            $newWidth=$MaxWe;
            $newHeight=round(($height*$percent)/100);
        }else{
            $newWidth=round(($width*$percent)/100);
            $newHeight=$MaxHe;
        }
    
        //Can currently create thumbnails from 3 filetypes, even though only 2 are permitted
        if ($arr_image_details[2] == 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        if ($arr_image_details[2] == 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
    
        if ($imgt) {
            $old_image = $imgcreatefrom($img);
            $new_image = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
           $imgt($new_image, $updir."/".$id."_t.jpg");
            return;    
        }
    }
?>

<html id="page" class="hide-scrollbar">
<head>
    <title><?= $title ?></title>
    <meta charset="UTF-8" lang="de">
    <meta name="description" content="Hotelverwaltung">
    <meta name="keywords" content="Hotel, Verwaltung, Ski, Skifahren, Österreich, Resort, Spaß, Entspannung, Wellness">
    <meta name="author" content="Samuel Muskovich, Natalia Guarnizo Duran">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Custom CSS, Custom JS -->
    <link rel="stylesheet" href="/Semesterprojekt/css/style.css">
    <script type="text/javascript" src="/Semesterprojekt/js/script.js"></script>

    <!-- Fonts Roboto and Lora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>    
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet"> 
</head>