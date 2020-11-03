<?
namespace Sale\Handlers\Delivery;

use \Bitrix\Main\Error;
use \Bitrix\Sale\Shipment;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Sale\Delivery\Services\Manager;
use \Bitrix\Sale\Delivery\CalculationResult;
use \Bitrix\Main\Loader;
use \Bitrix\Sale\Delivery;

Loc::loadMessages(__FILE__);

Loader::includeModule('russianpost.post');

class ElonsoftpostProfile extends \Bitrix\Sale\Delivery\Services\Base
{
	protected static $isProfile = true;
	protected $parent = null;
	protected $serviceType = 0;

	public function __construct(array $initParams)
	{
		if(empty($initParams["PARENT_ID"]))
			throw new ArgumentNullException('initParams[PARENT_ID]');

		parent::__construct($initParams);
		$this->parent = \Bitrix\Sale\Delivery\Services\Manager::getObjectById($this->parentId);

		if(!($this->parent instanceof ElonsoftpostHandler))
			throw new ArgumentNullException('this->parent is not instance of ElonsoftpostHandler');

		if(isset($initParams['PROFILE_ID']) && intval($initParams['PROFILE_ID']) > 0)
			$this->serviceType = intval($initParams['PROFILE_ID']);
		elseif(isset($this->config['MAIN']['SERVICE_TYPE']) && intval($this->config['MAIN']['SERVICE_TYPE']) > 0)
			$this->serviceType = $this->config['MAIN']['SERVICE_TYPE'];

		if($this->id <= 0 && $this->serviceType > 0)
		{
			$srvRes = $this->parent->getServiceTypes();
			$srvTypes = $srvRes->getData();

			$srvTypes = $this->parent->getProfilesList();

			if(!empty($srvTypes[$this->serviceType]))
			{
				$this->name = $srvTypes[$this->serviceType]['Name'];
				$this->description = $srvTypes[$this->serviceType]['ShortDescription'];
			}
		}

		$this->inheritParams();
	}

	public static function getClassTitle()
	{
		return Loc::getMessage("SALE_DLV_ELONSOFT_POST_PROFILE_TITLE");
	}

	public static function getClassDescription()
	{
		return Loc::getMessage("SALE_DLV_ELONSOFT_POST_PROFILE_DESCRIPTION");
	}

	public function getParentService()
	{
		return $this->parent;
	}

	public function isCalculatePriceImmediately()
	{
		return $this->getParentService()->isCalculatePriceImmediately();
	}

	public static function isProfile()
	{
		return self::$isProfile;
	}

	protected function calculateConcrete(\Bitrix\Sale\Shipment $shipment = null)
	{
		$result = new \Bitrix\Sale\Delivery\CalculationResult();
		if(!empty($shipment))
		{
			$order = $shipment->getCollection()->getOrder();
			$deliveryIds = $order->getDeliverySystemId();
			$idCalculatedDelivery = 0;
			foreach($deliveryIds as $deliveryId)
			{
				$service = Delivery\Services\Manager::getById($deliveryId);
				if($service['CLASS_NAME'] === '\Sale\Handlers\Delivery\ElonsoftpostProfile')
				{
					$deliveryType = $service['CONFIG']['MAIN']['SERVICE_TYPE'];
					$idCalculatedDelivery = $deliveryId;
					break;
				}
			}
			if($deliveryType == 1)
			{
				if((isset($_REQUEST['order']['elonsoft_result_price']) && $_REQUEST['order']['elonsoft_result_price']>=0)
					&& (isset($_REQUEST['order']['DELIVERY_ID']) && $_REQUEST['order']['DELIVERY_ID'] == $idCalculatedDelivery))
				{
					$result->setDeliveryPrice(
						roundEx(
							$_REQUEST['order']['elonsoft_result_price']/100,
							SALE_VALUE_PRECISION
						)
					);
					if(LANG_CHARSET == 'windows-1251')
					{
						$_REQUEST['order']['elonsoft_delivery_description'] = iconv("UTF-8", "WINDOWS-1251", $_REQUEST['order']['elonsoft_delivery_description']);
					}
					if(isset($_REQUEST['order']['elonsoft_delivery_description']) && $_REQUEST['order']['elonsoft_delivery_description'] != '')
					{
						$result->setPeriodDescription($_REQUEST['order']['elonsoft_delivery_description']);
						$_SESSION['russianpost_post_calc']['delivery_description'] = $_REQUEST['order']['elonsoft_delivery_description'];
					}
					if(isset($_REQUEST['order']['DELIVERY_ID']) && $_REQUEST['order']['DELIVERY_ID'] == $idCalculatedDelivery)
					{
						$_SESSION['russianpost_post_calc']['price'] = $_REQUEST['order']['elonsoft_result_price'];
						$_SESSION['russianpost_post_calc']['shipment_type'] = $_REQUEST['order']['elonsoft_result_type'];
					}

				}
				else
				{
					if(isset($_SESSION['russianpost_post_calc']['price'])
						&& (isset($_SESSION['russianpost_post_calc']['checked_delivery']) && $_SESSION['russianpost_post_calc']['checked_delivery'] == $idCalculatedDelivery))
					{
						$result->setDeliveryPrice(
							roundEx(
								$_SESSION['russianpost_post_calc']['price']/100,
								SALE_VALUE_PRECISION
							)
						);
						if(isset($_SESSION['russianpost_post_calc']['delivery_description']))
						{
							$result->setPeriodDescription($_SESSION['russianpost_post_calc']['delivery_description']);
						}
					}
					else
					{
					$weight = $shipment->getWeight(); // вес отгрузки
					$order = $shipment->getCollection()->getOrder(); // заказ
					$orderId = $order->getId();
					$props = $order->getPropertyCollection();
					$locProp = $props->getDeliveryLocation();
					if($locProp)
					{
						$locationCode = $locProp->getValue();
						if($locationCode != '')
						{
							$item = \Bitrix\Sale\Location\LocationTable::getByCode($locationCode, array(
								'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID),
								'select' => array('*', 'NAME_RU' => 'NAME.NAME')
							))->fetch();
						}
					}
					$addrProp = $props->getAddress();
					if($addrProp)
						$addrPropValue = $addrProp->getValue();
					$zipProp = $props->getDeliveryLocationZip();
					if($zipProp)
						$zipPropValue = $zipProp->getValue();
					$arParams['WEIGHT'] = intval($weight);
					$arParams['ZIP'] = $zipPropValue;
					$arParams['ADDRESS'] = $item['NAME_RU'];
					if(empty($arParams['ADDRESS']))
						$arParams['ADDRESS'] = '';
					$arParams['PRICE'] = $order->getPrice()-$order->getDeliveryPrice();

					$request = new \Elonsoft\Post\Request();

					$res = $request->PickUpCalculate($arParams);
					$answer = $res[0];
					if(empty($answer))
					{
						$result->addError(new \Bitrix\Main\Error(Loc::getMessage("SALE_DLV_ELONSOFT_POST_SELECT_POST")));
					}
					else
					{
						if(!empty($answer['errors']))
						{
							if(is_array($answer['errors']))
							{
								foreach ($answer['errors'] as $error)
								{
									$result->addError(new \Bitrix\Main\Error($error));
								}
							}
							else
							{
								$result->addError(new \Bitrix\Main\Error($answer['errors']));
							}
						}
						else
						{
							$result->setDeliveryPrice(
								roundEx(
									$answer['price'],
									SALE_VALUE_PRECISION
								)
							);
							if(LANG_CHARSET == 'windows-1251')
							{
								$answer['delivery_interval']['description'] = iconv("UTF-8", "WINDOWS-1251", $answer['delivery_interval']['description']);
							}
							$result->setPeriodDescription($answer['delivery_interval']['description']);
							if(isset($_SESSION['russianpost_post_calc']['checked_delivery']) && $_SESSION['russianpost_post_calc']['checked_delivery'] == $idCalculatedDelivery)
							{
								$_SESSION['russianpost_post_calc']['shipment_type'] = $answer['shipment_type'];
								if(!isset($_REQUEST['order']['elonsoft_result_address']))
								{
									$_SESSION['russianpost_post_calc']['clear_address'] = 'Y';
								}
							}
						}
					}
					}

				}
			}
			if($deliveryType == 2)
			{
				#REQUEST PO INDEXY

				$weight = $shipment->getWeight(); // вес отгрузки
				$order = $shipment->getCollection()->getOrder(); // заказ
				$orderId = $order->getId();
				$props = $order->getPropertyCollection();
				$locProp = $props->getDeliveryLocation();
				if($locProp)
				{
					$locationCode = $locProp->getValue();
					if($locationCode != '')
					{
						$item = \Bitrix\Sale\Location\LocationTable::getByCode($locationCode, array(
							'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID),
							'select' => array('*', 'NAME_RU' => 'NAME.NAME')
						))->fetch();
					}
				}
				$addrProp = $props->getAddress();
				if($addrProp)
					$addrPropValue  = $addrProp->getValue();
				$zipProp = $props->getDeliveryLocationZip();
				if($zipProp)
					$zipPropValue   = $zipProp->getValue();
				$arParams['WEIGHT'] = intval($weight);
				$arParams['ZIP'] = $zipPropValue;
				$arParams['ADDRESS'] = $item['NAME_RU'].' '.$addrPropValue;
				$arParams['PRICE'] = $order->getPrice()-$order->getDeliveryPrice();
				$request = new \Elonsoft\Post\Request();

				$res = $request->CourierCalculate($arParams);
				$answer = $res[0];

				if(empty($answer))
				{
					$result->addError(new \Bitrix\Main\Error(Loc::getMessage("SALE_DLV_ELONSOFT_POST_CALC_ERROR")));
				}
				else
				{
					if(is_array($answer['errors_detailed']))
					{
						$tarifErr = $answer['errors_detailed']['tariff']['errors'];
						$deliveryErr = $answer['errors_detailed']['delivery']['errors'];
						foreach ($tarifErr as $key=>$arInfo)
						{
							$arInfo['message'] = str_replace(array("'", "\""), array('',''), $arInfo['message']);
							$tarifErr[$key] = $arInfo;
						}
						foreach ($deliveryErr as $key=>$arInfo)
						{
							$arInfo['message'] = str_replace(array("'", "\""), array('',''), $arInfo['message']);
							$deliveryErr[$key] = $arInfo;
						}
						$answer['errors_detailed']['tariff']['errors'] = $tarifErr;
						$answer['errors_detailed']['delivery']['errors'] = $deliveryErr;
						$_SESSION['russianpost_post_calc']['error_detailed'] = $answer['errors_detailed'];
					}
					if(!empty($answer['errors']))
					{
						if(is_array($answer['errors']))
						{
							foreach ($answer['errors'] as $error)
							{
								$result->addError(new \Bitrix\Main\Error($error));
							}
						}
						else
						{
							$result->addError(new \Bitrix\Main\Error($answer['errors']));
						}
					}
					else
					{
						$result->setDeliveryPrice(
							roundEx(
								$answer['price'],
								SALE_VALUE_PRECISION
							)
						);
						if(LANG_CHARSET == 'windows-1251')
						{
							$answer['delivery_interval']['description'] = iconv("UTF-8", "WINDOWS-1251", $answer['delivery_interval']['description']);
						}
						$result->setPeriodDescription($answer['delivery_interval']['description']);
						if((isset($_REQUEST['order']['DELIVERY_ID']) && $_REQUEST['order']['DELIVERY_ID'] == $idCalculatedDelivery)
							|| $_SESSION['russianpost_post_calc']['checked_delivery'] == $idCalculatedDelivery)
						{
							$_SESSION['russianpost_post_calc']['shipment_type'] = $answer['shipment_type'];
						}
					}
				}
			}
		}
		return $result;
	}

	protected function 	inheritParams()
	{
		if(strlen($this->name) <= 0) $this->name = $this->parent->getName();
		if(intval($this->logotip) <= 0) $this->logotip = $this->parent->getLogotip();
		if(strlen($this->description) <= 0) $this->description = $this->parent->getDescription();
		//if(empty($this->trackingParams)) $this->trackingParams = $this->parent->getTrackingParams();
		//if(strlen($this->trackingClass) <= 0) $this->trackingClass = $this->parent->getTrackingClass();

		/*$parentES = \Bitrix\Sale\Delivery\ExtraServices\Manager::getExtraServicesList($this->parentId);
		$allowEsCodes = self::getProfileES($this->serviceType);

		if(!empty($parentES))
		{
			foreach($parentES as $esFields)
			{
				if(
					strlen($esFields['CODE']) > 0
					&& !$this->extraServices->getItemByCode($esFields['CODE'])
					&& in_array($esFields['CODE'], $allowEsCodes)
				)
				{
					$this->extraServices->addItem($esFields, $this->currency);
				}
			}
		}*/
	}
}
?>