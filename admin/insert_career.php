<?php 

require "session_start.php";
include "config.php";
include 'change_format.php';
include "resize.php";
include '../module/Application/src/Model/include_dao.php';

$contentMysqlExtDAO = new ContentMySqlExtDAO();
extract($_POST);

$active=radio_button($active);

$obj =  new ContentMySqlDAO();
$obj->title = $position;
$obj->subtitle = $employment_type;
$obj->details = $details;
$obj->displayOrder = $display_order;
$obj->type = 'career';
$obj->active = $active;
$obj->canDelete = 1;

$insert = $contentMysqlExtDAO->insert($obj);
if ($insert) {
    $act=1;
} else {
    $act=2;
}

header("Location: display_career.php?act=".$act);
exit;
