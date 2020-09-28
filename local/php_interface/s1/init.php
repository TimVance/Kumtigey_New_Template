<?php

use Bitrix\Sale;

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/s1/lib/calcDiscount.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/s1/lib/calcDiscount.php");

// Проверка активности разделов
function checkActivitySection()
{
    $iblock_catalogs = [14, 26];
    foreach ($iblock_catalogs as $iblock_catalog) {
        $res_sections = CIBlockSection::GetList(
            [],
            ["IBLOCK_ID" => $iblock_catalog],
            ["CNT_ACTIVE" => "ACTIVE"],
            ["IBLOCK_ID", "ID", "NAME", "ACTIVE"]
        );
        while ($res_section = $res_sections->GetNext()) {
            if (!empty($res_section["ELEMENT_CNT"])) $active = "Y";
            else $active = "N";
            $bs       = new CIBlockSection;
            $arFields = ["ACTIVE" => $active];
            if ($active != $res_section["ACTIVE"])
                $bs->Update($res_section["ID"], $arFields);
        }
    }
    return "checkActivitySection();";
}


// Проверка активности брендов
function checkActivityBrands()
{
    $iblock_brand   = 9;
    $iblock_catalog = 14;
    $res_brands     = CIBlockElement::GetList(
        [],
        ["IBLOCK_ID" => $iblock_brand],
        false,
        [],
        ["IBLOCK_ID", "ID", "NAME", "ACTIVE"]
    );
    while ($res_brand = $res_brands->GetNext()) {
        $cnt_elements = CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $iblock_catalog, "PROPERTY_BRAND" => $res_brand["ID"], "ACTIVE" => "Y"],
            [],
            [],
            ["IBLOCK_ID", "ID"]
        );
        if (!empty($cnt_elements)) $active = "Y";
        else $active = "N";
        if ($active != $res_brand["ACTIVE"]) {
            $el = new CIBlockElement();
            $el->Update($res_brand["ID"], ["ACTIVE" => $active]);
        }
    }
    return "checkActivityBrands();";
}


// Рандомизация призов в рулетке
function shuffleItems()
{
    $res = CIBlockElement::GetList(
        [],
        ["IBLOCK_ID" => 67],
        false,
        false,
        ["ID", "IBLOCK_ID"]
    );

    $items = [];
    while ($ob = $res->GetNext()) {
        $items[] = $ob;
    }

    shuffle($items);

    foreach ($items as $i => $item) {
        $el    = new CIBlockElement;
        $props = ["SORT" => $i];
        $el->Update($item["ID"], $props);
    }

    return "shuffleItems();";
}


function ChangeStatusOldOrders() {
    $parameters = [
        'filter' => [
            "<=DATE_INSERT" => date('d.m.Y', strtotime("-3 days")),
            "!STATUS_ID" => "F"
        ],
        'order' => ["DATE_INSERT" => "ASC"],
        'limit' => 500
    ];

    $dbRes = \Bitrix\Sale\Order::getList($parameters);
    while ($order = $dbRes->fetch())
    {
        $order_id = \Bitrix\Sale\Order::load($order["ID"]);
        $order_id->setField('STATUS_ID', 'F');
        $order_id->save();
    }
    return "ChangeStatusOldOrders();";
}