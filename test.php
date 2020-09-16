<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>


<?php



$q = "DCD701";
$module_id = "iblock";
$obSearch = new CSearch;
$obSearch->Search(array(
    "QUERY" => $q,
    "SITE_ID" => SITE_ID,
    "MODULE_ID" => $module_id,
), array(), array("STEMMING" => false));
if ($obSearch->errorno!=0):
    ?>
    <font class="text">В поисковой фразе обнаружена ошибка:</font>
    <?echo ShowError($obSearch->error);?>
    <font class="text">Исправьте поисковую фразу и повторите поиск.</font>
<?
else:
    while($arResult = $obSearch->GetNext())
    {?>
        <a href="<?echo $arResult["URL"]?>"><?echo $arResult["TITLE_FORMATED"]?></a>
        <?echo $arResult["BODY_FORMATED"]?>
        <hr size="1" color="#DFDFDF">
    <?}
endif;



?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>