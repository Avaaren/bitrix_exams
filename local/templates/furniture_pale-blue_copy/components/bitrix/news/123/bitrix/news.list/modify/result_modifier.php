<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$firstNewsDate = $arResult["ITEMS"][0]["ACTIVE_FROM"];

$cp = $this->__component;

$cp->arResult['FIRST_NEWS_DATE'] = $firstNewsDate;
$cp->SetResultCacheKeys(array('FIRST_NEWS_DATE'));