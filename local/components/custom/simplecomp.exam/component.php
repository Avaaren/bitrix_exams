<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult["NEWS_ID"] = $arParams["NEWS_IBLOCK_ID"];
$arResult["PRODUCT_ID"] = $arParams["PRODUCT_IBLOCK_ID"];
$arResult["UF_CODE"] = $arParams["UF_CODE"];


$arSections = array();
$arNews = array();

$arFilter = Array('IBLOCK_ID'=>6);
$arSelect = Array("ID", "NAME", "UF_*");
// Выбираем все разделы продукции, вместе с массивом новостей, привязанным к ним
  $db_list = CIBlockSection::GetList(false, $arFilter, true, $arSelect);
  while($ar_result = $db_list->Fetch())
  {
    // Каждый из них отправляем в отдельный массив разделов
    array_push($arSections, $ar_result);
    // И также для каждого просматриваем все привязанные новости
    foreach ($ar_result['UF_NEWS_LINK'] as $news)
    {
        // Если новости еще нет в массиве новостей, то отправляем ее туда
        //  в виде $arNews[ID новости]=>["ID раздела"]
        if (!array_key_exists($news, $arNews))
        {
            $arNews[$news] = array($ar_result["ID"]);
        }
        else 
        {
            // Если есть, то пушим в уже существующий массив новости текущий раздел
            array_push($arNews[$news], $ar_result["ID"]);
        }
        // По итогу получим массив вида
        /*
            $arNews (
                [321] => (15,18,20),
                [348] => (4,11,32)
            )
        */ 
    }
  }

 


$this->IncludeComponentTemplate();
?>