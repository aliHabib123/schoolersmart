<?php

require "session_start.php";
include "config.php";
include "change_format.php";
include "resize.php";
include '../module/Application/src/Model/include_dao.php';

$id = $_GET['id'];
$albumMysqlExtDAO = new AlbumMySqlExtDAO();

extract($_POST);
$active=radio_button($active);

if ($_FILES['image']['size'] > 0) {
    $newImage=upload_image("image", $imagesPath);
    if (is_file($imagesPath.$newImage)) {
        $simpleImage->load($imagesPath.$newImage);
        $simpleImage->resizeToWidth($medImageW);
        $simpleImage->save($imagesPath."med_".$newImage);
        $simpleImage->resizeToWidth($smallImageW);
        $simpleImage->save($imagesPath."small_".$newImage);

        if (is_file($imagesPath.$current_image)) {
            unlink($imagesPath.$current_image);
            unlink($imagesPath."med_".$current_image);
            unlink($imagesPath."small_".$current_image);
        }
    }
    $image = $newImage;
} else{
	$image = $current_image;
}

$bannerObj =  new AlbumMySqlDAO();
$bannerObj->id = $id;
$bannerObj->title = $title;
$bannerObj->image = $image;
$bannerObj->displayOrder = $display_order;
$bannerObj->active = $active;
$bannerObj->albumType = 'content';

$update = $albumMysqlExtDAO->update($bannerObj);

if ($update) {
    $num++;
}

if ($num>0) {
    $act=3;
} else {
    $act=4;
}

header("Location: display_album.php?act=".$act);
exit();
