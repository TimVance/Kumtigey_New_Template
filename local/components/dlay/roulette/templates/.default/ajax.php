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

    $code  = intval($request["code"]);
    $phone = $request["phone"];
    $mail  = $request["email"];
    $fio   = $request["fio"];
    $shop   = $request["shop"];

    $arSelect = array("ID", "NAME", "PROPERTY_used");
    $arFilter = array("IBLOCK_ID" => 64, "NAME" => $code, "ACTIVE" => "Y");
    $res      = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
    $coupon   = array();
    while ($ob = $res->GetNextElement()) {
        $coupon = $ob->GetFields();
    }
    $result = array();
    if ($coupon["ID"]) {

        if ($coupon["PROPERTY_USED_VALUE"] == "Нет") {

            // Получаем товары, которые участвуют в акции
            $items    = array();
            $arSelect = array("ID", "IBLOCK_ID", "NAME", "CATALOG_QUANTITY", "DETAIL_PAGE_URL");
            $arFilter = array(
                "IBLOCK_ID" => 67,
                "ACTIVE" => "Y",
                ">CATALOG_QUANTITY" => 0
            );
            $res      = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, array(), $arSelect);
            while ($arFields = $res->GetNext()) {
                $items[] = $arFields;
            }
            // Получаем товары, которые участвуют в акции


            $result["success"] = "Y";
            $rand_number       = rand(0, count($items) - 1);

            $el = new CIBlockElement;

            $PROP       = array();
            $PROP[1700] = $code;
            $PROP[1701] = $phone;
            $PROP[1702] = $mail;
            $PROP[1703] = $fio;
            $PROP[1704] = $rand_number;
            $PROP[1706] = $items[$rand_number]["ID"];
            $PROP[1716] = $shop;

            $arLoadProductArray = array(
                "MODIFIED_BY"       => $USER->GetID(),
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID"         => 66,
                "PROPERTY_VALUES"   => $PROP,
                "NAME"              => $code,
                "ACTIVE"            => "Y",
            );

            if (!empty($items[$rand_number]["CATALOG_QUANTITY"])) {
                if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                    $result["success"] = "Y";
                    $result["number"]  = $rand_number;
                    $result["id"]  = $items[$rand_number]["ID"]; // Передаем ID в фронтенд

                    CIBlockElement::SetPropertyValuesEx($coupon["ID"], false, ["used" => 2727]);

                    $obProduct = new CCatalogProduct();
                    $obProduct->Update(
                        $items[$rand_number]["ID"],
                        ['QUANTITY' => intval($items[$rand_number]["CATALOG_QUANTITY"]) - 1]
                    );

                    // Send Mail
                    $arEventFields = array(
                        "EMAIL_TO" => $mail,
                        "NAME"     => $fio,
                        "PRICE"    => $items[$rand_number]["NAME"],
                    );
                    CEvent::Send("win_makita", 's1', $arEventFields);

                    /*
                     *  Отсылаем уведомление в телеграмм
                     *
                     */

$message = '
Новый победитель в рулетке!
ID: '.$PRODUCT_ID.'
Товар: '.$items[$rand_number]["NAME"].' (ID: '.$items[$rand_number]["ID"].')
ФИО: '.$fio.'
E-mail: '.$mail.'

https://kumtigey.ru/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=66&type=aspro_max_content&lang=ru&ID='.$PRODUCT_ID.'&find_section_section=-1&WF=Y
';

                    $pluginOptions = array();
                    $pluginOptions['token'] = '1385253137:AAGYiTvfbOjADVQa6nxtV60650WGysrQ644';
                    $pluginOptions['id_chat'] = '-1001334631205';
                    if (empty($pluginOptions)) {
                        return false;
                    }
                    if (empty($pluginOptions['token']) || empty($pluginOptions['id_chat'])) {
                        return false;
                    }
                    $arResult = array();
                    $tbot     = file_get_contents("https://api.telegram.org/bot" . $pluginOptions['token'] . "/sendMessage?chat_id=" . $pluginOptions['id_chat'] . "&text=" . urlencode($message));
                    $arResult = json_decode($tbot, TRUE);

                    /*
                     *  Отсылаем уведомление в телеграмм
                     *
                     */

                } else {
                    $result["success"] = "N";
                    $result["text"]    = 'Произошла ошибка! Перезагрузите страницу и попробуйте еще раз!';
                }
            } else {
                $result["success"] = "N";
                $result["text"]    = 'Произошла ошибка! Перезагрузите страницу и попробуйте еще раз!';
            }

        } else {
            $result["success"] = "N";
            $result["text"]    = 'Промокод уже использован!';
        }

    } else {
        $result["success"] = "N";
        $result["text"]    = 'Такого промокода не существует!';
    }

    echo json_encode($result);

}