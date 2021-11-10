<?php 

require "session_start.php";
include "config.php";
include 'change_format.php';
include "resize.php";
include '../module/Application/src/Model/include_dao.php';

$contentMysqlExtDAO = new ContentMySqlExtDAO();
extract($_POST);

$active=radio_button($active);
$image=upload_image("image", "$imagesPath");
if (is_file($imagesPath.$image)) {
    $simpleImage->load($imagesPath.$image);
    $simpleImage->resizeToWidth($medImageW);
    $simpleImage->save($imagesPath."med_".$image);
    $simpleImage->resizeToWidth($smallImageW);
    $simpleImage->save($imagesPath."small_".$image);
} else {
    $image="";
}

$obj =  new ContentMySqlDAO();
$obj->title = $title;
$obj->customUrl = $link;
$obj->image = $image;
$obj->displayOrder = $display_order;
$obj->type = 'shopping_slider';
$obj->canDelete = 1;

$insert = $contentMysqlExtDAO->insert($obj);
if ($insert) {
    $act=1;
} else {
    $act=2;
}

header("Location: display_shopping_slider.php?act=".$act);
exit;
