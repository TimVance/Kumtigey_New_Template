<?php

namespace Sale\Handlers\Delivery;

use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Sale\Delivery\CalculationResult;
use \Bitrix\Sale\Location\GroupTable;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Error;
use Bitrix\Sale\Internals\CompanyTable;
use Bitrix\Sale\Result;
use \Bitrix\Sale\Shipment;
use Bitrix\Main\EventManager;
use Bitrix\Main\Text\Encoding;
use Bitrix\Sale\BusinessValue;
use Bitrix\Main\SystemException;
use Sale\Handlers\Delivery\Spsr\Cache;
use Sale\Handlers\Delivery\Spsr\Request;
use Sale\Handlers\Delivery\Spsr\Location;
use Bitrix\Sale\Delivery\Services\Manager;
use Sale\Handlers\Delivery\Spsr\Calculator;
use Bitrix\Sale\Delivery\ExtraServices\Table;

Loc::loadMessages(__FILE__);

Loader::includeModule('russianpost.post');
Loader::registerAutoLoadClasses(
    null,
    array(
        '\Sale\Handlers\Delivery\ElonsoftpostProfile' => '/bitrix/php_interface/include/sale_delivery/elonsoftpost/profile.php',
    )
);

Loader::registerAutoLoadClasses(
    'russianpost.post',
    array(
        '\Elonsoft\Post\Request' => '/lib/Request.php',
    )
);

/*
 * @package Bitrix\Sale\Delivery\Services
 */
class ElonsoftpostHandler extends \Bitrix\Sale\Delivery\Services\Base
{
	protected static $isCalculatePriceImmediately = true;
	protected  static $whetherAdminExtraServicesShow = true;
    /** @var bool $canHasProfiles This handler can has profiles */
    protected static $canHasProfiles = true;

	/**
	 * @param array $initParams
	 * @throws \Bitrix\Main\ArgumentTypeException
	 */
    public function __construct(array $initParams)
    {
        parent::__construct($initParams);
    }

    public static function getClassTitle()
    {
        return Loc::getMessage("SALE_DLVR_HANDL_ELONSOFT_POST_TITLE");
    }

    public static function getClassDescription()
    {
        return Loc::getMessage("SALE_DLVR_HANDL_ELONSOFT_POST_DESCRIPTION");
    }

    public function isCalculatePriceImmediately()
    {
        return self::$isCalculatePriceImmediately;
    }

    public static function whetherAdminExtraServicesShow()
    {
        return self::$whetherAdminExtraServicesShow;
    }

    protected function getConfigStructure()
    {
        $result = array(
            /*'MAIN' => array(
                'TITLE' => Loc::getMessage("SALE_DLV_ELONSOFT_POST_MAIN_SETTINGS"),
                'DESCRIPTION' => Loc::getMessage("SALE_DLV_ELONSOFT_POST_MAIN_SETTINGS_DESCR"),
                'ITEMS' => array(
                    /*'API_KEY' => array(
                        'TYPE' => 'STRING',
                        'NAME' => '���� API',
                    ),
                    'TEST_MODE' => array(
                        'TYPE' => 'Y/N',
                        'NAME' => Loc::getMessage("SALE_DLV_ELONSOFT_POST_TEST"),
                        'DEFAULT' => 'N'
                    ),*/
                    /*'PACKAGING_TYPE' => array(
                        'TYPE' => 'ENUM',
                        'NAME' => '��� ��������',
                        'DEFAULT' => 'BOX',
                        'OPTIONS' => array(
                            'BOX' => '�������',
                            'ENV' => '�������',
                        )
                    ),
                )
            )*/
        );
        return $result;
    }

    protected function calculateConcrete(\Bitrix\Sale\Shipment $shipment = null)
    {
        // �����-�� �������� �� ��������� ��������� � �����...

        throw new \Bitrix\Main\SystemException('Only profiles can calculate concrete');
    }

    public static function canHasProfiles()
    {
        return self::$canHasProfiles;
    }

    /**
     * @return array Class names for profiles.
     */
    public static function getChildrenClassNames()
    {
        return array(
            '\Sale\Handlers\Delivery\ElonsoftpostProfile'
        );
    }

    public function getProfilesList()
    {
        $arProfiles = self::getPostProfiles();
        $arNames = array();
        foreach ($arProfiles as $profile)
        {
            $arNames[] = $profile['Name'];
        }
        return $arNames;
    }

    protected static function getPostProfiles()
    {
        return array(
            "1" => array(
                "ID" => "1",
                "Name" => Loc::getMessage('SALE_DLV_ELONSOFT_POST_PVZ'),
                "ShortDescription" => Loc::getMessage('SALE_DLV_ELONSOFT_POST_PVZ_SDESCR'),
                "Description" => Loc::getMessage('SALE_DLV_ELONSOFT_POST_PVZ_DESCR'),
            ),
            "2" => array(
                "ID" => "2",
                "Name" => Loc::getMessage('SALE_DLV_ELONSOFT_POST_COURIER'),
                "ShortDescription" => Loc::getMessage('SALE_DLV_ELONSOFT_POST_COURIER_SDESCR'),
                "Description" => Loc::getMessage('SALE_DLV_ELONSOFT_POST_COURIER_DESCR'),
            ),
        );
    }

    public function getProfilesDefaultParams()
    {
        $result = array();

        $srvTypes = self::getPostProfiles();


        if(is_array($srvTypes))
        {
            $sort = 1;
            foreach($srvTypes as $profId => $params)
            {
                if($profId == 1)
                {
                    $arFile = \CFile::MakeFileArray('/bitrix/php_interface/include/sale_delivery/elonsoftpost/delivery.png');
                    $arFileds1 = \CFile::SaveFile($arFile, "sale/delivery/logotip");
                }
                elseif ($profId == 2)
                {
                    $arFile = \CFile::MakeFileArray('/bitrix/php_interface/include/sale_delivery/elonsoftpost/courier.png');
                    $arFileds1 = \CFile::SaveFile($arFile, "sale/delivery/logotip");
                }
                $result[] = array(
                    "CODE" => "",
                    "PARENT_ID" => $this->id,
                    "NAME" => $params["Name"],
                    "ACTIVE" => $this->active ? "Y" : "N",
                    "SORT" => $sort,
                    "DESCRIPTION" => $params["ShortDescription"],
                    "CLASS_NAME" => '\Sale\Handlers\Delivery\ElonsoftpostProfile',
                    "CURRENCY" => $this->currency,
                    'LOGOTIP'             => $arFileds1,
                    "CONFIG" => array(
                        "MAIN" => array(
                            "SERVICE_TYPE" => $profId,
                            "SERVICE_TYPE_NAME" => $params["Name"],
                            "DESCRIPTION_INNER" => $params["Description"]
                        )
                    )
                );
                $sort++;
            }
        }

        return $result;
    }

    public static function onAfterAdd($serviceId, array $fields = array())
    {
        if($serviceId <= 0)
            return false;

        $result = true;

        //Add profiles
        $fields["ID"] = $serviceId;
        $srv = new self($fields);
        $profiles = $srv->getProfilesDefaultParams();

        if(is_array($profiles))
        {
            foreach($profiles as $profile)
            {
                $res = Manager::add($profile);
                $result = $result && $res->isSuccess();
            }
        }

        return $result;
    }
}
?>