<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$IS_MAIN = 'N';
if ($APPLICATION->GetCurPage(true)==SITE_DIR.'index.php') {
$IS_MAIN = 'Y';
}
if($GET["debug"] == "y"){
	error_reporting(E_ERROR | E_PARSE);
}
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $TEMPLATE_OPTIONS, $arSite;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
?><!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" xmlns="http://www.w3.org/1999/xhtml" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
<head>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NMZ2NG7');</script>
<!-- End Google Tag Manager -->

	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowMeta("viewport");?>
	<?$APPLICATION->ShowMeta("HandheldFriendly");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
	<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
	<?$APPLICATION->ShowHead();?>
	<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
	<?$APPLICATION->AddHeadString('<meta name="yandex-verification" content="f3aa31cd26d8c839"/>');?>
	<?if(CModule::IncludeModule("aspro.optimus")) {COptimus::Start(SITE_ID);}?>
	<!--[if gte IE 9]><style type="text/css">.basket_button, .button30, .icon {filter: none;}</style><![endif]-->
	<link href='<?=CMain::IsHTTPS() ? 'https' : 'http'?>://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link href='/local/templates/aspro_optimus/css/Magnific.css' rel='stylesheet' type='text/css'>
	<script src='/local/templates/aspro_optimus/js/Magnific.js'></script>

</head>
	<body id="main">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NMZ2NG7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<?if(!CModule::IncludeModule("aspro.optimus")){?><center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?><?}?>
		<?$APPLICATION->IncludeComponent("aspro:theme.optimus", ".default", array("COMPONENT_TEMPLATE" => ".default"), false);?>
		<?COptimus::SetJSOptions();?>
		<div class="<?if($IS_MAIN == 'Y'){?> home-page <?} ?> wrapper <?=(COptimus::getCurrentPageClass());?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]);?> <?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?> banner_auto">
			<div class="header_wrap <?=strtolower($TEMPLATE_OPTIONS["HEAD_COLOR"]["CURRENT_VALUE"])?>">
				<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]=="NORMAL"){?>
					<div class="top-h-row">
						<div class="wrapper_inner">
							<div class="top_inner">
								<div class="content_menu">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
										array(
											"COMPONENT_TEMPLATE" => ".default",
											"PATH" => SITE_DIR."include/topest_page/menu.top_content_row.php",
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "",
											"AREA_FILE_RECURSIVE" => "Y",
											"EDIT_TEMPLATE" => "standard.php"
										),
										false
									);?>
								</div>
								<div class="phones">
									<div class="phone_block">
										<span class="phone_wrap">
											<span class="icons fa fa-phone"></span>
											<span class="phone_text">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
													array(
														"COMPONENT_TEMPLATE" => ".default",
														"PATH" => SITE_DIR."include/phone.php",
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "",
														"AREA_FILE_RECURSIVE" => "Y",
														"EDIT_TEMPLATE" => "standard.php"
													),
													false
												);?>
											</span>
										</span>
										<span class="order_wrap_btn">
											<span class="callback_btn"><?=GetMessage("CALLBACK")?></span>
										</span>
									</div>
								</div>
								<div class="h-user-block" id="personal_block">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
										array(
											"COMPONENT_TEMPLATE" => ".default",
											"PATH" => SITE_DIR."include/topest_page/auth.top.php",
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "",
											"AREA_FILE_RECURSIVE" => "Y",
											"EDIT_TEMPLATE" => "standard.php"
										),
										false
									);?>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				<?}?>
                <?
                $header_fixed = true;
                if($APPLICATION->GetCurPage(false) == "/basket/") {
                    $header_fixed = false;
                }
                ?>
				<header id="header" <? if(!$header_fixed) echo 'class="no-fixed"'; ?>>
					<div class="wrapper_inner">
						<div class="top_br"></div>
						<table class="middle-h-row">
							<tr>
								<td class="logo_wrapp">
									<div class="logo nofill_<?=strtolower(\Bitrix\Main\Config\Option::get('aspro.optimus', 'NO_LOGO_BG', 'N'));?>">
										<?COptimus::ShowLogo();?>
									</div>
								</td>
								<td class="text_wrapp">
									<div class="slogan">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/top_page/slogan.php",
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "",
												"AREA_FILE_RECURSIVE" => "Y",
												"EDIT_TEMPLATE" => "standard.php"
											),
											false
										);?>	
									</div>
								</td>
								<td  class="center_block">																	
									<div class="search">
										<?$APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            ".default",
                                            array(
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
                                                "AREA_FILE_SHOW" => "file",
                                                "AREA_FILE_SUFFIX" => "",
                                                "AREA_FILE_RECURSIVE" => "Y",
                                                "EDIT_TEMPLATE" => "standard.php",
                                                "COMPOSITE_FRAME_MODE" => "Y",
                                                "COMPOSITE_FRAME_TYPE" => "AUTO"
                                            ),
                                            false
                                        );?>
									</div>
								</td>
								<td class="basket_wrapp">
									<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"] == "NORMAL"){?>
										<div class="wrapp_all_icons">
											<div class="header-compare-block icon_block iblock" id="compare_line" >
												<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
													array(
														"COMPONENT_TEMPLATE" => ".default",
														"PATH" => SITE_DIR."include/top_page/catalog.compare.list.compare_top.php",
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "",
														"AREA_FILE_RECURSIVE" => "Y",
														"EDIT_TEMPLATE" => "standard.php"
													),
													false
												);?>
											</div>
											<div class="header-cart" id="basket_line">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
													array(
														"COMPONENT_TEMPLATE" => ".default",
														"PATH" => SITE_DIR."include/top_page/comp_basket_top.php",
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "",
														"AREA_FILE_RECURSIVE" => "Y",
														"EDIT_TEMPLATE" => "standard.php"
													),
													false
												);?>												
											</div>
										</div>
									<?}else{?>
										<div class="header-cart fly" id="basket_line">
											<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
												array(
													"COMPONENT_TEMPLATE" => ".default",
													"PATH" => SITE_DIR."include/top_page/comp_basket_top.php",
													"AREA_FILE_SHOW" => "file",
													"AREA_FILE_SUFFIX" => "",
													"AREA_FILE_RECURSIVE" => "Y",
													"EDIT_TEMPLATE" => "standard.php"
												),
												false
											);?>											
										</div>
										<div class="middle_phone">
											<div class="phones">
												<span class="phone_wrap">
													<span class="phone">
														<span class="icons fa fa-phone"></span>
														<span class="phone_text">
															<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
																array(
																	"COMPONENT_TEMPLATE" => ".default",
																	"PATH" => SITE_DIR."include/phone.php",
																	"AREA_FILE_SHOW" => "file",
																	"AREA_FILE_SUFFIX" => "",
																	"AREA_FILE_RECURSIVE" => "Y",
																	"EDIT_TEMPLATE" => "standard.php"
																),
																false
															);?>
														</span>
													</span>
													<span class="order_wrap_btn">
														<span class="callback_btn"><?=GetMessage("CALLBACK")?></span>
													</span>
												</span>
											</div>
										</div>
									<?}?>
									<div class="clearfix"></div>
								</td>
							</tr>
						</table>
                        <div class="mobile-search-wrapper">
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
                                    "RANDOM_ELEMENTS_COUNT" => "5",
                                    "RANDOM_ELEMENTS_PICTURE_FIELD" => "PREVIEW_PICTURE",
                                    "RANDOM_ELEMENTS_PICTURE_HEIGHT" => "100",
                                    "RANDOM_ELEMENTS_PICTURE_WIDTH" => "100",
                                    "RESULT_CONTAINER_ID" => "di-search-result",
                                    "SECTIONS_COUNT_TOP" => "6",
                                    "SECTION_ID" => "",
                                    "COMPONENT_TEMPLATE" => ".default"
                                ),
                                false
                            );?>
                        </div>
					</div>
					<div class="catalog_menu menu_<?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?>">
						<div class="wrapper_inner">
							<div class="wrapper_middle_menu wrap_menu" id="return-back">
								<ul class="menu adaptive">
									<li class="menu_opener"><div class="text">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/menu/menu.mobile.title.php",
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "",
												"AREA_FILE_RECURSIVE" => "Y",
												"EDIT_TEMPLATE" => "standard.php"
											),
											false
										);?>
								</div></li>
								</ul>				
								<div class="catalog_menu_ext">
                                    <?if(!COptimus::IsMainPage()):?>
									<?$APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "front",
                                        array(
                                            "COMPONENT_TEMPLATE" => "front",
                                            "PATH" => SITE_DIR."include/menu/menu.catalog.php",
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "",
                                            "AREA_FILE_RECURSIVE" => "Y",
                                            "EDIT_TEMPLATE" => "standard.php",
                                            "COMPOSITE_FRAME_MODE" => "Y",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO"
                                        ),
                                        false
                                    );?>
                                    <?else:?>
                                        <ul class="menu top menu_top_block catalogfirst">
                                            <li class="catalog icons_fa has-child ">
                                                <a href="#header" class="parent">Каталог</a>
                                            </li>
                                        </ul>
                                    <? endif; ?>
								</div>
								<div class="inc_menu">
									<?$APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "front",
                                        array(
                                            "COMPONENT_TEMPLATE" => "front",
                                            "PATH" => SITE_DIR."include/menu/menu.top_content_multilevel.php",
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "",
                                            "AREA_FILE_RECURSIVE" => "Y",
                                            "EDIT_TEMPLATE" => "standard.php",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO"
                                        ),
                                        false
                                    );?>
								</div>
                                <span class="phonefixed">
										<span class="icons fa fa-phone"></span>
										<span class="phone_text">
											<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                                array(
                                                    "COMPONENT_TEMPLATE" => ".default",
                                                    "PATH" => SITE_DIR."include/phone.php",
                                                    "AREA_FILE_SHOW" => "file",
                                                    "AREA_FILE_SUFFIX" => "",
                                                    "AREA_FILE_RECURSIVE" => "Y",
                                                    "EDIT_TEMPLATE" => "standard.php"
                                                ),
                                                false
                                            );?>
										</span>
									</span>
							</div>
						</div>
					</div>
				</header>
			</div>
			<div class="wraps" id="content">
				<div class="wrapper_inner <?=(COptimus::IsMainPage() ? "front" : "");?> <?=((COptimus::IsOrderPage() || COptimus::IsBasketPage()) ? "wide_page" : "");?>">
					<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage()){?>
						<div class="left_block">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>					

							<?$APPLICATION->ShowViewContent('left_menu');?>

							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_banners_left.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_subscribe.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_news.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_news_articles.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
						</div>
						<div class="right_block">
					<?}?>
						<div class="middle">
							<?if(!COptimus::IsMainPage()):?>
								<div class="container">
									<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"optimus", 
	array(
		"START_FROM" => "1",
		"PATH" => "",
		"SITE_ID" => "s1",
		"SHOW_SUBSECTIONS" => "N",
		"COMPONENT_TEMPLATE" => "optimus",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
									<h1 id="pagetitle"><?=$APPLICATION->ShowTitle(false);?></h1>								
							<?endif;?>
<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") $APPLICATION->RestartBuffer();?>