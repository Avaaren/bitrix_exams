<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ( $arParams["SPECIAL_DATE"] == "Y")
{
    $APPLICATION->SetPageProperty('specialdate', $arResult["FIRST_NEWS_DATE"]);
}


