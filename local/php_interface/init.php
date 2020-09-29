<?php

AddEventHandler("main", "OnBeforeProlog", "changeMetaData");

function changeMetaData()
{
    global $APPLICATION;
    CModule::IncludeModule("iblock");
    $url = $APPLICATION->GetCurPage();
    // print_r($url);
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_DESCRIPTION","PROPERTY_TITLE");
    $arFilter = Array("IBLOCK_ID"=>10, "NAME"=>$url);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    
    if ( $ob = $res->Fetch() )
    {
        $APPLICATION->SetPageProperty("description", $ob["PROPERTY_DESCRIPTION_VALUE"]);
        $APPLICATION->SetPageProperty("title", $ob["PROPERTY_TITLE_VALUE"]);
    }

}