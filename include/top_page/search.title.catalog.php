<?$APPLICATION->IncludeComponent(
	"arturgolubev:search.title", 
	"search_cat", 
	array(
		"CATEGORY_0" => array(
			0 => "iblock_aspro_optimus_catalog",
		),
		"CATEGORY_0_TITLE" => "ALL",
		"CATEGORY_0_iblock_aspro_max_catalog" => array(
			0 => "54",
			1 => "49",
		),
		"CATEGORY_0_iblock_aspro_optimus_catalog" => array(
			0 => "14",
			1 => "26",
		),
		"CATEGORY_OTHERS_TITLE" => "OTHER",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "search_cat",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONTAINER_ID" => "title-search_fixed",
		"CONVERT_CURRENCY" => "N",
		"FILTER_NAME" => "",
		"INPUT_ID" => "title-search-input_fixed",
		"NUM_CATEGORIES" => "1",
		"ORDER" => "date",
		"PAGE" => CMax::GetFrontParametrValue("CATALOG_PAGE_URL"),
		"PREVIEW_HEIGHT" => "38",
		"PREVIEW_TRUNCATE_LEN" => "50",
		"PREVIEW_WIDTH" => "38",
		"PRICE_CODE" => array(
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"SEARCH_ICON" => $arParams["SEARCH_ICON"]=="Y"?"Y":"N",
		"SHOW_ANOUNCE" => "N",
		"SHOW_INPUT" => "Y",
		"SHOW_INPUT_FIXED" => "Y",
		"SHOW_OTHERS" => "N",
		"SHOW_PREVIEW" => "Y",
		"TOP_COUNT" => "10",
		"USE_LANGUAGE_GUESS" => "Y",
		"SHOW_PREVIEW_TEXT" => "Y"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>