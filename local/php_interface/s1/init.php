<?php

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/s1/lib/calcDiscount.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/s1/lib/calcDiscount.php");

// Проверка активности разделов
function checkActivitySection()
{
    $iblock_catalog = 14;
    $res_sections   = CIBlockSection::GetList(
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