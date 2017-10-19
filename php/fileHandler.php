<?php
require_once "functions.php";
$target_dir = "uploads/";
$realName = prettify(trim($_POST['realName']));
$part = $_POST['part'];
$log = $_POST['log'];
$fileNameNoEx = $target_dir . $realName . "#" . $part ."#". time();
$target_file = $fileNameNoEx . ".mp3";
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
//var_dump($ans);
//var_dump($log);
//var_dump($_POST);
//var_dump($_FILES);
//var_dump($target_file);
// Check if image file is a actual image or fake image
//if(isset($_POST["submit"])) {
//    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
//        $uploadOk = 1;
//    } else {
//        echo "File is not an image.";
//        $uploadOk = 0;
//    }
//}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
//if ($_FILES["fileToUpload"]["size"] > 500000) {
//    echo "Sorry, your file is too large.";
//    $uploadOk = 0;
//}
// Allow certain file formats
//if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "mp3"
//    && $imageFileType != "gif" ) {
//    echo "Sorry, only JPG, JPEG, PNG & GIF & mp3 files are allowed.";
//    $uploadOk = 0;
//}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //保存log
        $f = fopen($fileNameNoEx.".txt", "w");
        fwrite($f, $log);
        fclose($f);
        echo "ok";
    } else {
        var_dump($log);
        var_dump($_POST);
        var_dump($_FILES);
        var_dump($target_file);
        echo "asdsad";
        echo "Sorry, there was an error uploading your file.";
    }
}
