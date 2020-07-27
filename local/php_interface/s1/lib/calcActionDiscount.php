<?php

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Sale;


Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleBasketItemRefreshData',
    array("calcActionDiscount", "OnGetDiscountResult")
);

class calcActionDiscount
{

    private static $block_id = 14;
    private static $code = "TORGOVAYA_MARKA";
    private static $brand = "BOSCH";

    // Скидки
    private static $s1 = 0.05;
    private static $s2 = 0.1;
    private static $s3 = 0.15;

    private function getBasket()
    {
        return Sale\Basket::loadItemsForFUser(
            Sale\Fuser::getId(),
            Bitrix\Main\Context::getCurrent()->getSite()
        );
    }

    private function findBrands($basketItems)
    {
        $ids = array();
        foreach ($basketItems as $item) {
            $brand   = CIBlockElement::GetProperty(
                self::$block_id,
                $item->getProductId(),
                array("sort" => "asc"),
                array("CODE" => self::$code)
            );
            $arBrand = $brand->GetNext();
            if ($arBrand["VALUE"] == self::$brand) {
                $ids[] = $item->getProductId();
            }
        }
        return $ids;
    }

    private function calcPrice($price, $s) {
        return round(intval($price) - intval($price) * intval($s));
    }

    public static function OnGetDiscountResult(\Bitrix\Main\Event $event)
    {
        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log1.txt");



        AddMessage2Log($event);
        /*

        $basket      = self::getBasket();
        $basketItems = $basket->getBasketItems();

        $ids = array();
        $ids = self::findBrands($basketItems);

        // Change prices
        if (!empty($ids[1])) {

            $id          = $basketItem->getProductId();
            $customPrice = 0;
            AddMessage2Log($id);

            if ($ids[0] == $id) {
                $customPrice = self::calcPrice($basketItem->getBasePrice(), self::$s1);
            } elseif ($ids[1] == $id) {
                $customPrice = self::calcPrice($basketItem->getBasePrice(), self::$s2);
            } elseif ($ids[2] == $id) {
                $customPrice = self::calcPrice($basketItem->getBasePrice(), self::$s3);
            }

            if (!empty($customPrice)) {
                $basketItem->setFields([
                    'PRICE'        => $customPrice,
                    "CUSTOM_PRICE" => 'Y',
                ]);
                $basketItem->save();
            }

        }
        return $basketItem;
        */
    }

}