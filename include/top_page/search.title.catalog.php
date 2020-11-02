<?$APPLICATION->IncludeComponent(
    "datainlife:iblocksearch",
    ".default",
    array(
        "CATALOG_FOLDER" => "/catalog/",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "CONTAINER_ID" => "di-search",
        "EXT_FILTER" => "shopFilter",
        "INPUT_ID" => "di-search-input",
        "INPUT_SECTION_ID" => "di-search-section-id-input",
        "PRICE_CODE" => array(
            0 => "Розничная цена",
        ),
        "PROPERTY_CODE" => array(
        ),
        "RANDOM_ELEMENTS_COUNT" => "4",
        "RANDOM_ELEMENTS_PICTURE_FIELD" => "PREVIEW_PICTURE",
        "RANDOM_ELEMENTS_PICTURE_HEIGHT" => "100",
        "RANDOM_ELEMENTS_PICTURE_WIDTH" => "100",
        "RESULT_CONTAINER_ID" => "di-search-result",
        "SECTIONS_COUNT_TOP" => "5",
        "SECTION_ID" => "",
        "COMPONENT_TEMPLATE" => ".default"
    ),
    false
);?>
<style>
    #di-search-input {
        width: calc(100% - 90px);
        height: 40px;
    }
    .iblock-search {
        display: flex;
        justify-content: space-around;
        align-items: center;
        height: 100%;
    }
    .iblock-search > form {
        width: 100%;
    }
    .iblock-search button:hover, .iblock-search button:focus {
        background: #f77724;
    }
    .iblock-search button {
        display: inline-block;
        vertical-align: top;
        border: 1px solid #ef6508;
        background: #ef6508;
        color: #fff;
        padding: 6px 10px 5px;
        height: 40px;
        margin-right: 10px;
    }
</style>
