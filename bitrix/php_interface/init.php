<?
AddEventHandler("search", "BeforeIndex", "BeforeIndexHandler");
function BeforeIndexHandler($arFields)
{
    $clearIndex = 0;

    if($arFields["PARAM1"] == 'catalog' && IntVal($arFields["ITEM_ID"]))
    {
        if(CModule::IncludeModule("iblock")){
            $res = CIBlockElement::GetList(Array(), array("ID"=>$arFields["ITEM_ID"], "CATALOG_AVAILABLE" => "Y"), false, Array("nPageSize"=>1), array("ID", "NAME", "IBLOCK_SECTION_ID"));
            while($fields = $res->Fetch())
            {
                if(empty($fields["ID"])){
                    $clearIndex = 1;
                }
            }
        }
    }

    if($clearIndex)
    {
        $arFields["TITLE"] = '';
        $arFields["BODY"] = '';
        $arFields["TAGS"] = '';
    }

    return $arFields;
}
?>