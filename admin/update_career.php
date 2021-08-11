<?php

require "session_start.php";
include "config.php";
include "change_format.php";
include "resize.php";
include '../module/Application/src/Model/include_dao.php';

$contentMysqlExtDAO = new ContentMySqlExtDAO();

extract($_POST);
$active = radio_button($active);
$obj =  $contentMysqlExtDAO->load($id);

$obj->title = $position;
$obj->subtitle = $employment_type;
$obj->details = $details;
$obj->active = $active;
$obj->displayOrder = $display_order;

$update = $contentMysqlExtDAO->update($obj);

if ($update) {
    $num++;
}

if ($num > 0) {
    $act = 3;
} else {
    $act = 4;
}

header("Location: display_career.php?act=" . $act);
exit();
