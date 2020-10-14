<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use \Bitrix\Sale;

class COneClickOrder extends CBitrixComponent implements Controllerable
{

	public function configureActions()
	{
		return [
			'oneClickOrder' => [
				'prefilters' => [],
			],
		];
	}

	public function oneClickOrderAction($number)
	{
		CModule::includeModule("sale");
		CModule::includeModule("catalog");
		global $USER;

		$errors = array();

		if ( !isset($number) || strlen($number) <= 3)
		{
			array_push($errors, "Wrong phone format");
		}
		else
		{
			// Если пользователь не авторизирован
			if( !$USER->IsAuthorized() )
			{
				// Если такой номер уже есть в БД, то берем ID аккаунта
				$filter = Array("LOGIN" => $number);
				$rsUsers = CUser::GetList(($by = "NAME"), ($order = "desc"), $filter);
			
				if ($arUser = $rsUsers->Fetch())
				{
					$ID = $arUser["ID"];
				}
				// Если такого пользователя нету, то создаем его
				else 
				{
					$time = time();
					$user = new CUser;
					$arFields = [
					"EMAIL"             => "order@nomail.com",
					"LOGIN"             => $number,
					"ACTIVE"            => "Y",
					"PASSWORD"          => $number.$time,
					"CONFIRM_PASSWORD"  => $number.$time,
					];
				
					$ID = $user->Add($arFields);
				}
			}
			// Если пользователь авторизован, то берем его ID
			else
			{
				$ID = $USER->GetID();
			}
			// Получаем текущую корзину
			$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
			// Если корзина не пуста
			if( !count($basket)<=0 )
			{
				// Создаем объект заказа
				$order = Bitrix\Sale\Order::create(SITE_ID, $ID);
				// Привязываем к нему корзину
				$order->setBasket($basket);
				// Получаем доступ к свойствам заказа и задаем телефон полученный из формы
				$propertyCollection = $order->getPropertyCollection();
				$orderPhone = $propertyCollection->getPhone();
				$orderPhone->setValue($number);
				// Получаем колекцию отгрузок заказа и создаем новую отгрузку
				$shipmentCollection = $order->getShipmentCollection();
				$shipment = $shipmentCollection->createItem();
				
				// Тут можно задать ИД службы доставки
				$deliveryId = 1;
				// Тут к отгрузке привязывается указанная служба доставки
				$service = Bitrix\Sale\Delivery\Services\Manager::getById($deliveryId);
				$delivery = $service['NAME'];
				$shipment->setFields(array(
					'DELIVERY_ID' => $service['ID'],
					'DELIVERY_NAME' => $service['NAME'],
				));
				// Получаем коллекцию элементов отгрузки
				$shipmentItemCollection = $shipment->getShipmentItemCollection();
				// Проходим циклом по корзине
				foreach ($basket as $basketItem)
				{
					// Заполняем коллекцию элеменов товарами из корзины
					$item = $shipmentItemCollection->createItem($basketItem);
					$item->setQuantity($basketItem->getQuantity());
				}

				// Тут можно задать ИД службы оплаты
				$paymentId = 3;
				// Повторяем по аналогии с отгрузками
				$paymentCollection = $order->getPaymentCollection();
				$payment = $paymentCollection->createItem();
			
				$payment->setField('SUM', $order->getPrice());
				$payment->setField("CURRENCY", $order->getCurrency());
				
				$paySystemService = \Bitrix\Sale\PaySystem\Manager::getObjectById($paymentId);
				$payment->setFields(array(
					'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
					'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
				));    
				// Сохраняем заказ
				$result = $order->save();
		
				$resultId = $order->getId();   
				// Если возникли ошибки, то распечатываем их
				if (!$result->isSuccess())
				{
					array_push($errors, $result->getErrorMessages());
				}
			}
			else
			{
				array_push($errors, "Cart is empty");
			}
		}
		// Возвращаем ответ в виде листа
		return [
			"errors" => $errors,
			"id" => $resultId,
		];
	}

	public function executeComponent()
	{
		$this->IncludeComponentTemplate();
	}
}
