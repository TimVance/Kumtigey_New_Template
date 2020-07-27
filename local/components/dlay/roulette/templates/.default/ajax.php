<?php

header('Content-Type: application/json');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
define("STOP_STATISTICS", true);

use \Bitrix\Main\Context;

\Bitrix\Main\Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('catalog');


$APPLICATION->RestartBuffer();


$request = Context::getCurrent()->getRequest();

$code  = $request["code"];

echo json_encode($form);
