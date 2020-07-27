<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<a href="#" class="close jqmClose"><i></i></a>
<div class="form <?=$arResult["arForm"]["SID"]?>">
	<!--noindex-->
	<div class="form_head">
		<?if($arResult["isFormTitle"] == "Y"):?>
			<h2><?=$arResult["FORM_TITLE"]?></h2>
		<?endif;?>
		<?if($arResult["isFormDescription"] == "Y"):?>
			<div class="form_desc"><?=$arResult["FORM_DESCRIPTION"]?></div>
		<?endif;?>
	</div>
	<?if(strlen($arResult["FORM_NOTE"])){?>
		<div class="form_result <?=($arResult["isFormErrors"] == "Y" ? 'error' : 'success')?>">
			<?if($arResult["isFormErrors"] == "Y"):?>
				<?=$arResult["FORM_ERRORS_TEXT"]?>
			<?else:?>
				<script type="text/javascript">
				$(document).ready(function(){
					if(arOptimusOptions['COUNTERS']['USE_FORMS_GOALS'] !== 'NONE'){
						var eventdata = {goal: 'goal_webform_success' + (arOptimusOptions['COUNTERS']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?=$arParams['WEB_FORM_ID']?>')};
						BX.onCustomEvent('onCounterGoals', [eventdata]);
					}
				});
				</script>
				<?$successNoteFile = SITE_DIR."include/form/success_{$arResult["arForm"]["SID"]}.php";?>
				<?if(file_exists($_SERVER["DOCUMENT_ROOT"].$successNoteFile)):?>
				<?$APPLICATION->IncludeFile($successNoteFile, array(), array("MODE" => "html", "NAME" => "Form success note"));?>
				<?else:?>
					<?=GetMessage("FORM_SUCCESS");?>
				<?endif;?>
			<?endif;?>
		</div>
	<?}else{?>
		<?if($arResult["isFormErrors"] == "Y"):?>
			<div class="form_body error"><?=$arResult["FORM_ERRORS_TEXT"]?></div>
		<?endif;?>
		<?=$arResult["FORM_HEADER"]?>
		<?=bitrix_sessid_post();?>
		<div class="form_body">
			<?if(is_array($arResult["QUESTIONS"])):?>
				<?foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
					<?COptimus::drawFormField($FIELD_SID, $arQuestion);?>
				<?endforeach;?>
			<?endif;?>
			<div class="clearboth"></div>
			<?if($arResult["isUseCaptcha"] == "Y"):?>
				<div class="form-control captcha-row clearfix">
					<label><span><?=GetMessage("FORM_CAPRCHE_TITLE")?> <span class="star">*</span></span></label>
					<div class="captcha_image">
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" border="0" />
						<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" />
						<div class="captcha_reload"></div>
					</div>
					<div class="captcha_input">
						<input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50" value="" required />
					</div>
				</div>
			<?endif;?>
			<div class="clearboth"></div>
		</div>
		<div class="form_footer">
			<?if(COption::GetOptionString("aspro.optimus", "SHOW_LICENCE", "N") == "Y"):?>
				<div class="licence_block filter label_block">
					<input type="checkbox" id="licenses_popup_<?=$arResult["arForm"]["SID"]?>" <?=(COption::GetOptionString("aspro.optimus", "LICENCE_CHECKED", "N") == "Y" ? "checked" : "");?> name="licenses_popup" required value="Y">
					<label for="licenses_popup_<?=$arResult["arForm"]["SID"]?>">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
					</label>
				</div>
			<?endif;?>
			<?/*<button type="submit" class="button medium" value="submit" name="web_form_submit" ><span><?=$arResult["arForm"]["BUTTON"]?></span></button>*/?>
			<input type="submit" class="button medium" value="<?=$arResult["arForm"]["BUTTON"]?>" name="web_form_submit">
			<button type="reset" class="button medium transparent" value="reset" name="web_form_reset" ><span><?=GetMessage('FORM_CLOSE')?></span></button>

		</div>
		<?=$arResult["FORM_FOOTER"]?>
	<?}?>
	<!--/noindex-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').validate({
			highlight: function( element ){
				$(element).parent().addClass('error');
			},
			unhighlight: function( element ){
				$(element).parent().removeClass('error');
			},
			submitHandler: function( form ){
				if( $('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').valid() ){
					setTimeout(function() {
						$(form).find('button[type="submit"]').attr("disabled", "disabled");
					}, 300);

					var eventdata = {type: 'form_submit', form: form, form_name: '<?=$arResult["arForm"]["VARNAME"]?>'};
					BX.onCustomEvent('onSubmitForm', [eventdata]);

					// form.submit();
				}
			},
			errorPlacement: function( error, element ){
				error.insertBefore(element);
			},
			messages:{
				licenses_popup: {
					required : BX.message('JS_REQUIRED_LICENSES')
				}
			}
		});

		$('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').attr('placeholder','+7 999 999 99 99');

		if(arOptimusOptions['THEME']['PHONE_MASK'].length){
			var base_mask = arOptimusOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
			$('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').inputmask('mask', {'mask': arOptimusOptions['THEME']['PHONE_MASK'] });
			$('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').blur(function(){
				if( $(this).val() == base_mask || $(this).val() == '' ){
					if( $(this).hasClass('required') ){
						$(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
					}
				}
			});
		}
		$('.popup').jqmAddClose('a.jqmClose');
		$('.popup').jqmAddClose('button[name="web_form_reset"]');
	});
	</script>
</div>