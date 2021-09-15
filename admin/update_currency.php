<?php

require "session_start.php";
include "config.php";
include "change_format.php";
include "resize.php";
include '../module/Application/src/Model/include_dao.php';

$currencyMysqlExtDAO = new CurrencyMySqlExtDAO();

extract($_POST);

$obj =  $currencyMysqlExtDAO->load($id);
$obj->currencyName = $name;
$obj->currencySymbol = $symbol;
$obj->conversionRate = $rate;
$obj->displayOrder = $display_order;

$num = 0;
$update = $currencyMysqlExtDAO->update($obj);
if ($update) {
    $num++;
}
// Start of brand category mapping
$currencyCountryMappingMySqlExtDAO = new CurrencyCountryMySqlExtDAO();
$mapping = $currencyCountryMappingMySqlExtDAO->queryByCurrencyId($id);
$mapping = array_map(function ($a) {
    return $a->countryId;
}, $mapping);
$newMapping = [];
foreach ($countries as $row) {
    array_push($newMapping, $row);
}

$toDelete = implode(',', array_diff($mapping, $newMapping));
$toAdd = array_diff($newMapping, $mapping);
if (!empty($toDelete)) {
    $delete = $currencyCountryMappingMySqlExtDAO->deleteByCurrencyIdAndCond($id, "country_id IN (" . $toDelete . ")");
    if ($delete) {
        $num++;
    }
}
foreach ($toAdd as $row) {
    $obj = new CurrencyCountry();
    $obj->currencyId = $id;
    $obj->countryId = $row;
    $obj->isDefault = 0;
    $update = $currencyCountryMappingMySqlExtDAO->insert($obj);
    if ($update) {
        $num++;
    }
}
// End of brand category mapping

if ($num > 0) {
    $act = 3;
} else {
    $act = 4;
}

header("Location: display_currency.php?act=" . $act);
exit();
