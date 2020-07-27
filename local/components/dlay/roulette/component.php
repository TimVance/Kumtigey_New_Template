<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

$block_id = 14;

function getList($block_id)
{
    $items = array();
    $arSelect = array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE");
    $arFilter = array("IBLOCK_ID" => $block_id, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
    $res      = CIBlockElement::GetList(array(), $arFilter, false, Array("nPageSize"=>150), $arSelect);
    while ($arFields = $res->GetNext()) {
        $items[$arFields["ID"]] = $arFields;
        $items[$arFields["ID"]]["IMG"] = CFile::GetPath($arFields["PREVIEW_PICTURE"]);
    }
    return $items;
}

$arResult["items"] = getList($block_id);

$this->IncludeComponentTemplate();
