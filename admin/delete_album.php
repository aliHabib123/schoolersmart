<?php

require 'config.php';
include '../module/Application/src/Model/include_dao.php';

$response = [
	'status' => false,
	'msg' => 'Error',
];

$albumMySqlExtDAO = new AlbumMySqlExtDAO();
$albumImageMySqlExtDAO = new ImageMySqlExtDAO();

if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	$hasChildren = $albumImageMySqlExtDAO->queryByAlbumId($id);
	if ($hasChildren) {
		$response['msg'] = "This Album has images, delete them first";
	} else {
		$banner = $albumMySqlExtDAO->load($id);
		if (is_file(IMAGES_PATH . $banner->image)) {
			unlink(IMAGES_PATH . $banner->image);
			unlink(IMAGES_PATH . "med_" . $banner->image);
			unlink(IMAGES_PATH . "small_" . $banner->image);
		}
		$delete = $albumMySqlExtDAO->delete($id);
		if ($delete) {
			$response = [
				'status' => true,
				'msg' => 'Deleted',
			];
		}
	}
}
echo json_encode($response);
