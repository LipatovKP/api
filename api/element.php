<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $elementId = (int)$_GET['id'];
    $filter = [
        'IBLOCK_ID' => $iblockId,
        'ID' => $elementId,
        '<ACTIVE_FROM' => $currentDate,
        'ACTIVE' => 'Y'
    ];
    $select = ['ID', 'NAME', 'DETAIL_TEXT', 'ACTIVE_FROM', 'DETAIL_PICTURE'];

    $element = ElementTable::getList([
        'filter' => $filter,
        'select' => $select
    ])->fetch();

    $response = ['status' => (bool)$element];
    if ($element) {
        $response['item'] = [
            'id' => $element['ID'],
            'title' => $element['NAME'],
            'text' => $element['DETAIL_TEXT'],
            'date' => $element['ACTIVE_FROM']->format('Y-m-d'),
            'time' => $element['ACTIVE_FROM']->format('H:i:s'),
            'img' => $element['DETAIL_PICTURE'] ? CFile::GetPath($element['DETAIL_PICTURE']) : 'none'
        ];
    }

    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data && isset($data['ID'], $data['NAME'], $data['DETAIL_TEXT'])) {
        $elementId = (int)$data['ID'];
        $updateFields = [
            'NAME' => $data['NAME'],
            'DETAIL_TEXT' => $data['DETAIL_TEXT']
        ];
        $result = (new CIBlockElement())->Update($elementId, $updateFields);
        $response = ['status' => $result];

        if (!$result) {
            $response['message'] = (new CIBlockElement())->LAST_ERROR;
        }

        echo json_encode($response);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['status' => false]);
}


?>