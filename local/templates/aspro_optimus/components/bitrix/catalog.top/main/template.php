<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><? $this->setFrameMode( true ); ?>
<?
$sliderID  = "specials_slider_wrapp_".$this->randString();
$notifyOption = COption::GetOptionString("sale", "subscribe_prod", "");
$arNotify = unserialize($notifyOption);
?>
<div class="rows_block">
<?if($arResult["ITEMS"]):?>
	<?foreach($arResult["ITEMS"] as $key => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
	$totalCount = COptimus::GetTotalCount($arItem);
	$arQuantityData = COptimus::GetQuantityArray($totalCount);
	$arItem["FRONT_CATALOG"]="Y";
	
	$strMeasure='';
	if($arItem["OFFERS"]){
		$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
	}else{
		if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
			$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
			$strMeasure=$arMeasure["SYMBOL_RUS"];
		}
	}
	?>
	<li id="<?=$this->GetEditAreaId($arItem['ID']);?>" style="border: 1px solid #eaebec;" class="catalog_item item_block col-4 top-block-item">
		<div class="image_wrapper_block">
			<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
				<?if($arItem["PROPERTIES"]["HIT"]["VALUE"]){?>
					<div class="stickers">
						<?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
							<?foreach($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"] as $key=>$class){?>
								<div><div class="sticker_<?=strtolower($class);?>"><?=$arItem["PROPERTIES"]["HIT"]["VALUE"][$key]?></div></div>
							<?}?>
						<?endif;?>
						<?if($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
							<div><div class="sticker_sale_text"><?=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div></div>
						<?}?>
					</div>
				<?}?>
				<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
					<div class="like_icons">
						<?if($arItem["CAN_BUY"] && empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
							<div class="wish_item_button">
								<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>"><i></i></span>
								<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>"><i></i></span>
							</div>
						<?endif;?>
						<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
							<div class="compare_item_button">
								<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
								<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
							</div>
						<?endif;?>
					</div>
				<?endif;?>
				<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
					<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
				<?elseif(!empty($arItem["DETAIL_PICTURE"])):?>
					<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array("width" => 170, "height" => 170), BX_RESIZE_IMAGE_PROPORTIONAL, true );?>
					<img src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
				<?else:?>
					<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
				<?endif;?>
			</a>
		</div>
		<div class="item_info">
			<div class="item-title" style="height: 43px;">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
			</div>
			<?if($arParams["SHOW_RATING"] == "Y"):?>
			<div class="rating" style="display:none">
					<?$APPLICATION->IncludeComponent(
					   "bitrix:iblock.vote",
					   "element_rating_front",
					   Array(
						  "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						  "IBLOCK_ID" => $arItem["IBLOCK_ID"],
						  "ELEMENT_ID" =>$arItem["ID"],
						  "MAX_VOTE" => 5,
						  "VOTE_NAMES" => array(),
						  "CACHE_TYPE" => $arParams["CACHE_TYPE"],
						  "CACHE_TIME" => $arParams["CACHE_TIME"],
						  "DISPLAY_AS_RATING" => 'vote_avg'
					   ),
					   $component, array("HIDE_ICONS" =>"Y")
					);?>
				</div>
			<?endif;?>
			<?//=$arQuantityData["HTML"];?>
			<?$arAddToBasketData = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, array(), 'small', $arParams);?>


<?

$price = CCatalogProduct::GetOptimalPrice($arItem['ID'], 1, $USER->GetUserGroupArray(), 'N');


?>


<? if($price['RESULT_PRICE']["DISCOUNT"] > 0) { ?>
<div class="cost prices clearfix" style="margin: 5px 0 0;line-height: 0;background-color: #f4f4f4;padding: 5px;text-align:center">
	<div class="price_matrix_block">
		<div class="price_matrix_wrapper" style="margin-bottom: 8px !important;">
		
			<div class="price"><span class="values_wrapper"><? echo $price['RESULT_PRICE']["DISCOUNT_PRICE"] ?> руб.</span></div>
			<div class="price discount"><span class="values_wrapper"><? echo $price['RESULT_PRICE']["BASE_PRICE"] ?> руб.</span></div>
		
		</div>

		

		<div class="sale_block">
			<div class="sale_wrapper">
				<div class="value">-<span><? echo $price['RESULT_PRICE']["PERCENT"]; ?></span>%</div>
				<div class="text">
					<span class="title">Экономия</span>
					<span class="values_wrapper"><? echo $price['RESULT_PRICE']["DISCOUNT"]; ?> руб.</span>
				</div>
			</div>
		</div>
	</div>
</div>
<?} else {?>


			<div style="margin: 5px 0 0;line-height: 0;background-color: #f4f4f4;padding: 5px;text-align:center" class="cost prices clearfix">
				<?if($arItem["OFFERS"]):?>
					<?\Aspro\Functions\CAsproSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id);?>
				<?else:?>
					<?
					if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
					{?>
						<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
							<?=COptimus::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
						<?endif;?>
						<?=COptimus::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
					<?	
					}
					elseif($arItem["PRICES"])
					{?>
						<?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id);?>
					<?}?>
				<?endif;?>
			</div>


<? } ?>








				<div <? if($arItem["PROPERTIES"]["TORGOVAYA_MARKA"]["VALUE"] == "VIKING" || $arItem["PROPERTIES"]["TORGOVAYA_MARKA"]["VALUE"] == "STIHL") echo 'style="visibility:hidden"'; ?> class="counter_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '')?>">
					<p class="wrapp_one_click">
						<span style="padding: 0;height: 30px;max-width: 130px; width: 130px;display: block;line-height: 25px;" class="transparent big_btn type_block button transition_bg one_click" data-item="<?=$arItem["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arAddToBasketData["MIN_QUANTITY_BUY"];?>" onclick="oneClickBuy('<?=$arItem["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
									<span><?=GetMessage('ONE_CLICK_BUY')?></span>
								</span>
					</p>
					<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER"/*&& !$arItem["CAN_BUY"]*/)  || !$arItem["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : "");?>">
						<!--noindex-->
							<?=$arAddToBasketData["HTML"]?>
						<!--/noindex-->
					</div>
				</div>

		</div>
	</li>
<?endforeach;?>
<?else:?>
	<div class="empty_items"></div>
	<script type="text/javascript">			
		$('.top_blocks li[data-code=BEST]').remove();
		$('.tabs_content tab[data-code=BEST]').remove();
		if(!$('.slider_navigation.top li').length){
			$('.tab_slider_wrapp.best_block').remove();
		}
		if($('.bottom_slider').length){
			if($('.empty_items').length){
				$('.empty_items').each(function(){
					var index=$(this).closest('.tab').index();
					$('.top_blocks .tabs>li:eq('+index+')').remove();
					$('.tabs_content .tab:eq('+index+')').remove();
				})				
				$('.tabs_content .tab.cur').trigger('click');
			}
		}
	</script>
<?endif;?>
</div>