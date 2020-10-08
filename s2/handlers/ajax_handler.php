<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
// Подключаем пролог и инфоблокеи
CModule::IncludeModule("iblock");
// Проверка отправлен запрос через аякс или нет
if ( isset($_POST["elementId"]) && $_POST["elementId"] )
{
    $elementID = $_POST["elementId"];
}
elseif ( isset($_GET["elementId"]) && $_GET["elementId"] )
{
    $elementID = $_GET["elementId"];
    $method = "get";
}
// Создаем объект инфоблока, который будем использовать для добавления жалоб
$el = new CIBlockElement; 

// Если пользователь не авторизирован, то присваиваем ему любое имя
if ( !$USER->IsAuthorized() )
{
    $reportUser = "ANON";
}
// Если авторизирован то создаем имя из его данных
else
{
    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();
    $reportUser = $arUser["ID"]." - ".$arUser["LOGIN"]." - ".$arUser["NAME"];
}

// Заполняем поля создаваемой жалобе
$arFields = array(
    "MODIFIED_BY"    => $USER->GetID(),
    "IBLOCK_ID"      => 12,
    "ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL"),
    "PROPERTY_VALUES" => array(
        "42" => $reportUser,
        "43" => $elementID
    ),
    "NAME"           => $elementID."-".time(),
    "ACTIVE"         => "Y",
);
// Добавляем жалобу с созданными полями, получаем ее ID
$elementIDAdded = $el->Add($arFields);
// Если метод гет - возвращаем id строкой
if ($method == "get")
{
    echo $elementIDAdded;
}
// Если запрос был аякс, то создаем правильный формат ответа
else
{
    echo json_encode(array("addedElementId" => $elementIDAdded));
}
