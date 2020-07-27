<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>

<?if( !empty( $arResult ) ){
	global $TEMPLATE_OPTIONS;?>
	<div class="menu_top_block catalog_block">
		<ul class="menu dropdown">
			<?foreach( $arResult as $key => $arItem ){?>
				<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current" : "");?> m_<?=strtolower($TEMPLATE_OPTIONS["MENU_POSITION"]["CURRENT_VALUE"]);?>">
					<a class="icons_fa <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["SECTION_PAGE_URL"]?>" >
						<img src="<?=$arItem["IMAGESTEST"]["src"];?>" alt="<?/*=$arChildItem["NAME"];*/?>" /><?=$arItem["NAME"]?></a>
					<?if($arItem["CHILD"]){?>
						<ul class="dropdown">
							<?foreach($arItem["CHILD"] as $arChildItem){?>
                                <? if(empty($arChildItem["ELEMENT_CNT"])) continue; ?>
								<li class="<?=($arChildItem["CHILD"] ? "has-childs" : "");?> <?if($arChildItem["SELECTED"]){?> current <?}?>">
									<?if($arChildItem["IMAGES"]){?>
										<span class="image"><a href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><img src="<?=$arChildItem["IMAGES"]["src"];?>" alt="<?=$arChildItem["NAME"];?>" /></a></span>
									<?}?>
									<a class="section" href="<?=$arChildItem["SECTION_PAGE_URL"];?>">
										<span><?=$arChildItem["NAME"];?></span> <?="<span class='count'>(".$arChildItem["ELEMENT_CNT"].")</span>"; ?></a>
									<?if($arChildItem["CHILD"]){?>
										<ul class="dropdown">
											<?foreach($arChildItem["CHILD"] as $arChildItem1){?>
                                                <? if(empty($arChildItem1["ELEMENT_CNT"])) continue; ?>
												<li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>">
													<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span><?=$arChildItem1["NAME"];?></span> <?="<span class='count'>(".$arChildItem1["ELEMENT_CNT"].")</span>"; ?></a>
												</li>
											<?}?>
										</ul>
									<?}?>
									<div class="clearfix"></div>
								</li>
							<?}?>
						</ul>
					<?}?>
				</li>
			<?}?>
            <li class="full m_line"><a href="/milwaukeetools/">Milwaukee</a></li>
		</ul>
	</div>
<?}?>