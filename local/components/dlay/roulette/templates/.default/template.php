<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (count($arResult["items"])): ?>


<div class="wrapper_roulette">
    <div class="wrapper_roulette_inner">
        <span class="center-line"></span>
        <div class="window">
            <div class="list">
                <? $i = 0; ?>
                <?foreach ($arResult["items"] as $item):?>
                    <div class="item" data-number="<?=$i?>" data-id="<?=$item["ID"]?>">
                        <div class="image-wrapper"><img src="<?=$item["IMG"]?>" alt="<?=$item["NAME"]?>"></div>
                        <div class="roulette-name"><?=$item["NAME"]?></div>
                    </div>
                    <? $i++; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="roulette_info">
        <div class="roulette-form after-win">
            <form method="post">
                <input type="hidden" value="check" name="action">
                <div class="field-group">
                    <label>ФИО Полностью<span class="star">*</span><span class="error">Заполните поле</span></label>
                    <div class="fio-field">
                        <input type="text" name="fio" required autocomplete="off">
                        <div class="suggestions"></div>
                    </div>
                </div>
                <div class="field-group">
                    <label>Телефон<span class="star">*</span><span class="error">Заполните поле</span></label>
                    <input class="phone" type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                </div>
                <div class="field-group">
                    <label>E-mail<span class="star">*</span><span class="error">Заполните поле</span></label>
                    <input type="email" name="email" required>
                </div>
                <div class="field-group">
                    <label>Адрес магазина Кум-Тигей инструмент выдачи купона<span class="star">*</span><span class="error">Заполните поле</span></label>
                    <select name="shop">
                        <option value="2766">ул. Калинина 106</option>
                        <option value="2767">ул. Мечникова 46А</option>
                        <option value="2768">пр. Красноярский рабочий 37</option>
                    </select>
                </div>
                <div class="field-group">
                    <label>Код купона<span class="star">*</span><span class="error">Заполните поле</span></label>
                    <input type="text" name="code" required>
                </div>
            </form>
        </div>
        <div class="roulette-button after-win">
            <button class="start-roulette">Выиграть приз!</button>
            <p class="politic">Участвуя в акции вы соглашаетесь на обработку <a target="_blank" href="/include/licenses_detail.php">персональных данных</a>.</p>
            <div class="roulette-text">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/include/roulette_text.php"
                    )
                );?>
            </div>
        </div>
        <div class="win"></div>
    </div>
</div>

<?php endif; ?>