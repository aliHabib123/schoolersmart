<?php 

require "session_start.php";
include "config.php";
include 'change_format.php';
include "resize.php";
include '../module/Application/src/Model/include_dao.php';

$contentMysqlExtDAO = new ContentMySqlExtDAO();
extract($_POST);

$slug = slugify($title);
$c=1;
while($contentMysqlExtDAO->queryBySlug($slug)){
    $slug = slugify($title);
    $slug = $slug.'-'.$c;
    $c++;
}

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
$obj->title = $name;
$obj->subtitle = $position;
$obj->image = $image;
$obj->displayOrder = $display_order;
$obj->type = 'team';
$obj->slug = $slug;
$obj->canDelete = 1;

$insert = $contentMysqlExtDAO->insert($obj);
if ($insert) {
    $act=1;
} else {
    $act=2;
}

header("Location: display_team.php?act=".$act);
exit;