<?php

header('Content-Type: application/json');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
define("STOP_STATISTICS", true);

use \Bitrix\Main\Context;

\Bitrix\Main\Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('catalog');
\Bitrix\Main\Loader::includeModule('iblock');


$APPLICATION->RestartBuffer();


$request = Context::getCurrent()->getRequest();

if ($request["action"] == "check") {

    $code = intval($request["code"]);
    $phone = $request["tel"];
    $mail = $request["email"];
    $fio = $request["fio"];

    $arSelect = array("ID", "NAME");
    $arFilter = array("IBLOCK_ID" => 64, "NAME" => $code, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
    $res      = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
    $coupon   = array();
    while ($ob = $res->GetNextElement()) {
        $coupon = $ob->GetFields();
    }
    $result = array();
    if ($coupon["ID"]) {
        $result["success"] = "Y";
        $result["number"] = rand(1, 100);

        $el = new CIBlockElement;

        $PROP = array();
        $PROP[1700] = $code;
        $PROP[1701] = $phone;
        $PROP[1702] = $mail;
        $PROP[1703] = $fio;
        $PROP[1704] = $result["number"];

        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => 66,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $code,
            "ACTIVE"         => "Y",
        );

        if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            $result["success"] = "Y";
        }
        else {
            $result["success"] = "N";
            $result["text"] = $el->LAST_ERROR;
        }

    } else {
        $result["success"] = "N";
        $result["text"] = 'Такого промокода не существует!';
    }

    echo json_encode($result);

}