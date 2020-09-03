<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

$block_id = 67;

function getList($block_id)
{
    $items = array();
    $arSelect = array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "CATALOG_QUANTITY");
    $arFilter = array(
        "IBLOCK_ID" => $block_id,
        "ACTIVE_DATE" => "Y",
        "ACTIVE" => "Y",
        ">CATALOG_QUANTITY" => 0
    );
    $res      = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, Array(), $arSelect);
    while ($arFields = $res->GetNext()) {
        $items[$arFields["ID"]] = $arFields;
        $items[$arFields["ID"]]["IMG"] = CFile::GetPath($arFields["PREVIEW_PICTURE"]);
    }
    return $items;
}

$arResult["items"] = [];
//if ($this->StartResultCache(600)) {
    $arResult["items"] = getList($block_id);
//}

$this->IncludeComponentTemplate();
