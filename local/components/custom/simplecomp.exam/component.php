<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult["NEWS_ID"] = $arParams["NEWS_IBLOCK_ID"];
$arResult["PRODUCT_ID"] = $arParams["PRODUCT_IBLOCK_ID"];
$arResult["UF_CODE"] = $arParams["UF_CODE"];


function selectNewsArray($arParams)
{
    $arSections = array();
    $arNews = array();
    $arFilter = Array('IBLOCK_ID'=>$arParams["PRODUCTS_IBLOCK_ID"], "ACTIVE"=>"Y");
    $arSelect = Array("ID", "NAME", "UF_*");
// Выбираем все разделы продукции, вместе с массивом новостей, привязанным к ним
    $db_list = CIBlockSection::GetList(false, $arFilter, true, $arSelect);
    while($ar_result = $db_list->Fetch())
    {
        // Каждый из них отправляем в отдельный массив разделов
        array_push($arSections, $ar_result);
        // И также для каждого просматриваем все привязанные новости
        foreach ($ar_result[$arParams["UF_CODE"]] as $news)
        {
            // Если новости еще нет в массиве новостей, то отправляем ее туда
            //  в виде $arNews[ID новости]=>["ID раздела"]
            if (!array_key_exists($news, $arNews))
            {
                $arNews[$news]["IDS"] = array($ar_result["ID"]);
                $arNews[$news]["NAMES"] = array($ar_result["NAME"]);
            }
            else 
            {
                // Если есть, то пушим в уже существующий массив новости текущий раздел
                array_push($arNews[$news]["IDS"], $ar_result["ID"]);
                array_push($arNews[$news]["NAMES"], $ar_result["NAME"]);
            }
            // По итогу получим массив вида
            /*
                $arNews (
                    [321] => (15,18,20) (name1, name3),
                    [348] => (4,11,32), (name1, name2)
                )
            */ 
        }
    }
      return $arNews;
}

function selectCatalog($arParams)
{
    // Получаем массив новостей и категорий
    $newsArray = selectNewsArray($arParams);
    $resultArray = array();
    // Для каждой новости создадим массив с категориями и товарами
    foreach ($newsArray as $key => $value)
    {
        // Выбираем поля новости на которой находится цикл
        $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
        $arFilter = Array("IBLOCK_ID"=>$arParams["NEWS_IBLOCK_ID"], "ID"=>$key, "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($newsObject = $res->Fetch())
        {
            // Для каждой новости создаем массив, который будет разбирать шаблон
            // Добавляем в массив данные необходимые в задании
            $resultArray[$key] = array(
                "NEWS_NAME"=>$newsObject["NAME"],
                "NEWS_DATE"=>$newsObject["DATE_ACTIVE_FROM"],
                "CATEGORIES"=>$value["NAMES"],
                "PRODUCTS"=>array(),

            );
            // Для каждой новости необходима продукция категорий, которые привязаны к этой новости
            $products = CIBlockElement::GetList(
                false,
                ['IBLOCK_ID' => $arParams["PRODUCTS_IBLOCK_ID"], 'SECTION_ID' => $value["IDS"], "ACTIVE"=>"Y"],
                false, false,
                ["ID", "IBLOCK_ID", 'NAME', "PROPERTY_PRICE", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER"]
             );
            //  Все полученные продукты добавляем в массив для последующего отображения
             while( $productObject = $products->Fetch() )
             {
                array_push($resultArray[$key]["PRODUCTS"], $productObject);
             }
        }
    }
    return $resultArray;
}
$arResult["NEWS_CATALOG"] = selectCatalog($arParams);


$this->IncludeComponentTemplate();
?>