<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
use \Bitrix\Sale;

CModule::includeModule("sale");
CModule::includeModule("catalog");
global $USER;

$errors = array();
// Проверка введенного номера телефона
if ( !isset($_POST["phoneNumber"]) || strlen($_POST["phoneNumber"]) <= 3)
{
    array_push($errors, "Wrong phone format");
}
else
{
    // Если пользователь не авторизирован
    if( !$USER->IsAuthorized() )
    {
        // Если такой номер уже есть в БД, то берем ID аккаунта
        $filter = Array("LOGIN" => $_POST["phoneNumber"]);
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
            $arFields = Array(
            "EMAIL"             => "order@nomail.com",
            "LOGIN"             => $_POST["phoneNumber"],
            "ACTIVE"            => "Y",
            "PASSWORD"          => $_POST["phoneNumber"].$time,
            "CONFIRM_PASSWORD"  => $_POST["phoneNumber"].$time,
            );
        
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
        $orderPhone->setValue($_POST["phoneNumber"]);
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
echo json_encode(array(
    "errors" => $errors,
    "id" => $resultId,
));


