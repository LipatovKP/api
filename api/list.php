<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Type\DateTime;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 204 No Content");
    exit();
}

Loader::includeModule('iblock');

$iblockId = 1;
$currentDate = new DateTime();

$filter = [
    'IBLOCK_ID' => $iblockId,
    '<ACTIVE_FROM' => $currentDate,
    'ACTIVE' => 'Y'
];

if (isset($_GET['doSort'])) {
    $doSort = (int)$_GET['doSort'];
    if ($doSort === 1 && isset($_GET['doDate'])) {
        $doDate = $_GET['doDate'];
        $filter['<=ACTIVE_FROM'] = new DateTime($doDate);
    } elseif ($doSort === 9 && isset($_GET['doTax'])) {
        $doTax = (int)$_GET['doTax'];
        $filter['SECTION_ID'] = $doTax;
    }
}

$select = ['ID', 'NAME', 'PREVIEW_TEXT', 'ACTIVE_FROM', 'DETAIL_PICTURE'];

$elements = ElementTable::getList([
    'filter' => $filter,
    'select' => $select
]);

$items = [];
while ($element = $elements->fetch()) {
    $items[] = [
        'id' => $element['ID'],
        'title' => $element['NAME'],
        'intro' => $element['PREVIEW_TEXT'],
        'date' => $element['ACTIVE_FROM']->format('Y-m-d'),
        'time' => $element['ACTIVE_FROM']->format('H:i:s'),
        'img' => $element['DETAIL_PICTURE'] ? CFile::GetPath($element['DETAIL_PICTURE']) : 'none'
    ];
}

$response = [
    'status' => !empty($items),
    'items' => $items
];

echo json_encode($response);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>