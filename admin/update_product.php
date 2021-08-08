<?php

require "session_start.php";
include "config.php";
include "change_format.php";
include "resize.php";
include '../module/Application/src/Model/include_dao.php';

$itemTagMappingMySqlExtDAO = new ItemTagMappingMySqlExtDAO();
$itemCategoryMappingMySqlExtDAO = new ItemCategoryMappingMySqlExtDAO();
$itemBrandMappingMySqlExtDAO = new ItemBrandMappingMySqlExtDAO();
$itemMySqlExtDAO = new ItemMySqlExtDAO();
$num = 0;
extract($_POST);

$item = $itemMySqlExtDAO->load($id);

if ($_FILES['image']['size'] > 0) {
    $newImage = upload_image("image", $imagesPath);
    if (is_file($imagesPath . $newImage)) {
        $simpleImage->load($imagesPath . $newImage);
        $simpleImage->resizeToWidth($medImageW);
        $simpleImage->save($imagesPath . "med_" . $newImage);
        $simpleImage->resizeToWidth($smallImageW);
        $simpleImage->save($imagesPath . "small_" . $newImage);

        if (is_file($imagesPath . $current_image)) {
            unlink($imagesPath . $current_image);
            unlink($imagesPath . "med_" . $current_image);
            unlink($imagesPath . "small_" . $current_image);
        }
    }
    $image = $newImage;
} else {
    $image = $current_image;
}

$item->image = $image;

$item->title = $title;
$item->description = $description;
$item->specification = $specification;
$item->regularPrice = $regular_price;
$item->salePrice = $sale_price;
$item->weight = $weight;
$item->height = $height;
$item->width = $width;
$item->sku = $sku;
$item->color = $color;
$item->warranty = $warranty;
$item->exchange = $exchange;

$update = $itemMySqlExtDAO->update($item);

if ($update) {
    $num++;
}

// Update Tags
$best_deals = radio_button($best_deals);
$hot_selling = radio_button($hot_selling);
$featured = radio_button($featured);

$tags = [
    '1' => $best_deals, // best-deals
    '2' => $hot_selling, //hot-selling
    '3' => $featured, //featured
];

$delete = $itemTagMappingMySqlExtDAO->deleteByItemId($id);
if ($delete) {
    $num++;
}
foreach ($tags as $key => $val) {
    if ($val) {
        $obj = new ItemTagMapping();
        $obj->itemId = $id;
        $obj->tagId = $key;
        $insert = $itemTagMappingMySqlExtDAO->insert($obj);
        if ($insert) {
            $num++;
        }
    }
}
// End of update tags


// Update categories
$categories = $_POST['c'];
$deleteCategoriesMapping = $itemCategoryMappingMySqlExtDAO->deleteByItemId($id);
if ($deleteCategoriesMapping) {
    $num++;
}
foreach ($categories as $row) {
    $obj = new ItemCategoryMapping();
    $obj->itemId = $id;
    $obj->categoryId = $row;
    $insertCategoryMapping = $itemCategoryMappingMySqlExtDAO->insert($obj);
    if ($insertCategoryMapping) {
        $num++;
    }
}
// End of update categories

// Update brands
$brands = $_POST['brand'];
$deleteBrandsMapping = $itemBrandMappingMySqlExtDAO->deleteByItemId($id);
if ($deleteBrandsMapping) {
    $num++;
}
foreach ($brands as $row1) {
    $obj = new ItemBrandMapping();
    $obj->itemId = $id;
    $obj->brandId = $row1;
    $insertBrandMapping = $itemBrandMappingMySqlExtDAO->insert($obj);
    if ($insertBrandMapping) {
        $num++;
    }
}
// End of update brands

if ($num > 0) {
    $act = 3;
} else {
    $act = 4;
}
$append = parse_str($query_string, $output);
unset($output['act']);
unset($output['id']);
$append = http_build_query($output);
header("Location: display_product.php?act=" . $act . '&' . $append);
exit();
