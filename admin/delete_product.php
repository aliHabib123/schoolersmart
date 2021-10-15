<?php

require 'config.php';
include '../module/Application/src/Model/include_dao.php';

$response = [
	'status' => false,
	'msg' => 'Error',
];

$itemMySqlExtDAO = new ItemMySqlExtDAO();
$itemCategoryMappingMySqlExtDAO = new ItemCategoryMappingMySqlExtDAO();
$itemBrandMappingMySqlExtDAO = new ItemBrandMappingMySqlExtDAO();

if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];

	$delete = $itemMySqlExtDAO->delete($id);
	if ($delete) {
		$deleteCategoryMapping = $itemCategoryMappingMySqlExtDAO->deleteByItemId($id);
		$deleteBrandMapping = $itemBrandMappingMySqlExtDAO->deleteByItemId($id);
		$response = [
			'status' => true,
			'msg' => 'Deleted',
		];
	}
}
echo json_encode($response);
