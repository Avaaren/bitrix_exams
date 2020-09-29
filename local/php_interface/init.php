<?php

AddEventHandler("main", "OnBeforeProlog", "changeMetaData");

function changeMetaData()
{
    global $APPLICATION;
    CModule::IncludeModule("iblock");
    // Получить урл загружаемой страницы
    $url = $APPLICATION->GetCurPage();
    // Выбираем нужные свойства из созданного инфоблока (ID=10), если текущая страница есть в БД
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_DESCRIPTION","PROPERTY_TITLE");
    $arFilter = Array("IBLOCK_ID"=>10, "NAME"=>$url);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    // Если поиск дал результат - извлекаем данные и устанавливаем свойства страницы
    if ( $ob = $res->Fetch() )
    {
        $APPLICATION->SetPageProperty("description", $ob["PROPERTY_DESCRIPTION_VALUE"]);
        $APPLICATION->SetPageProperty("title", $ob["PROPERTY_TITLE_VALUE"]);
    }

}