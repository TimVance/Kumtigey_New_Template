<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>


<?
if (CModule::IncludeModule("sale")):

    $arFilter = Array("STATUS_ID" => array("N", "P"));
    $rsSales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter);
    $arOrders = array();
    $test = 5;
    while ($arSales = $rsSales->Fetch())
    {
        if (empty($test)) break;
        $arOrders[] = $arSales;
        $test--;
    }
    echo '<pre>';
    print_r($arOrders);
    echo '</pre>';
endif;
?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>