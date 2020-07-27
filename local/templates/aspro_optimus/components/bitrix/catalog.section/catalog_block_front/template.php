<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?$this->setFrameMode(true);?>
<?if( count( $arResult["ITEMS"] ) >= 1 ){?>
	<div class="top_wrapper">
		<div class="catalog_block items">
		<?foreach($arResult["ITEMS"] as $arItem){?>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			$totalCount = COptimus::GetTotalCount($arItem);
			$arQuantityData = COptimus::GetQuantityArray($totalCount);
			$arAddToBasketData = COptimus::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, array(), 'small', $arParams);

			$item_id = $arItem["ID"];
			$strMeasure = '';

			if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
				$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
				$strMeasure = $arMeasure["SYMBOL_RUS"];
			}
			switch ($arParams["LINE_ELEMENT_COUNT"]){
				case '2':
					$col=2;
					break;
				case '3':
					$col=3;
					break;
				default:
					$col=4;
					break;
			}
			?>
			<div class="catalog_item_wrapp col-<?=$col;?> item" data-col="<?=$col;?>">
				<div class="catalog_item item_wrap " id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="inner_wrap">
						<div class="image_wrapper_block">
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
							<?if((!$arItem["OFFERS"] && $arParams["DISPLAY_WISH_BUTTONS"] != "N" ) || ($arParams["DISPLAY_COMPARE"] == "Y")):?>
								<div class="like_icons">
									<?if($arParams["DISPLAY_WISH_BUTTONS"] == "Y" && !$arItem["OFFERS"]):?>
										<div class="wish_item_button">
											<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
											<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
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
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb">
								<?
								$a_alt=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] );
								$a_title=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] );
								?>
								<?if( !empty($arItem["PREVIEW_PICTURE"]) ):?>
									<img class="noborder" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?elseif( !empty($arItem["DETAIL_PICTURE"])):?>
									<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 170, "height" => 170 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
									<img class="noborder" src="<?=$img["src"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?else:?>
									<img class="noborder" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
								<?endif;?>
							</a>
						</div>
						<div class="item_info main_item_wrapper">

							<div class="item-title">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a>
							</div>
							<div class="cost prices clearfix">
								<?if( $arItem["OFFERS"]){?>
									<?\Aspro\Functions\CAsproSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id);?>
								<?}else{?>
									<?
									$item_id = $arItem["ID"];
									if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
									{?>
										<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
											<?=COptimus::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
										<?endif;?>
										<?=COptimus::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
										<?$arMatrixKey = array_keys($arItem['PRICE_MATRIX']['MATRIX']);
										$min_price_id=current($arMatrixKey);?>
									<?	
									}
									elseif($arItem["PRICES"])
									{?>
										<?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id);?>
									<?}?>
								<?}?>
							</div>
							<?if($arParams["SHOW_DISCOUNT_TIME"]=="Y"){?>
								<?$arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", $min_price_id, SITE_ID);
								$arDiscount=array();
								if($arDiscounts)
									$arDiscount=current($arDiscounts);
								if($arDiscount["ACTIVE_TO"]){?>
									<div class="view_sale_block">
										<div class="count_d_block">
											<span class="active_to hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
											<div class="title"><?=GetMessage("UNTIL_AKC");?></div>
											<span class="countdown values"></span>
										</div>
										<div class="quantity_block">
											<div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
											<div class="values">
												<span class="item">
													<span class="value"><?=$totalCount;?></span>
													<span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
												</span>
											</div>
										</div>
									</div>
								<?}?>
							<?}?>


                            <?
                            // Прятать ли кнопку заказа в один клик
                            $isShtilOrViking = false;
                            if(
                                $arItem["PROPERTIES"]["TORGOVAYA_MARKA"]["VALUE"] == "STIHL" ||
                                $arItem["PROPERTIES"]["TORGOVAYA_MARKA"]["VALUE"] == "VIKING"
                            ) {
                                $isShtilOrViking = true;
                                $arAddToBasketData["HTML"] = str_replace("В корзину", "Резерв", $arAddToBasketData["HTML"]);
                            }

                            ?>


							<br />
                            <? if(
                                ($arItem["PROPERTIES"]["TORGOVAYA_MARKA"]["VALUE"] == "VIKING" ||
                                    $arItem["PROPERTIES"]["TORGOVAYA_MARKA"]["VALUE"] == "STIHL") &&
                                COption::GetOptionString( "askaron.settings", "UF_SAMOVYVOZ" )
                            )   echo '<div class="order-message">Только самовывоз!</div>'; ?>
							<div class="counter_wrapp <?=($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '')?>">
                                <?if(!$isShtilOrViking):?>
                                    <p class="wrapp_one_click">
                                                <span class="transparent big_btn type_block button transition_bg one_click" data-item="<?=$arItem["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arAddToBasketData["MIN_QUANTITY_BUY"];?>" onclick="oneClickBuy('<?=$arItem["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
                                                    <span><?=GetMessage('ONE_CLICK_BUY')?></span>
                                                </span>
                                    </p>
                                <? endif; ?>
								<div id="<?=$arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER"/*&& !$arItem["CAN_BUY"]*/)  || !$arItem["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : "");?>">
									<!--noindex-->
										<?=$arAddToBasketData["HTML"]?>
									<!--/noindex-->
								</div>
							</div>




							<div class="footer_button">
								<div class="counter_wrapp">
									<div class="button_block">
										<!--noindex-->
											<!--<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="button basket read_more"><?=GetMessage("CATALOG_READ_MORE");?></a>-->
										<!--/noindex-->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?}?>
		</div>
	</div>
<?}?>