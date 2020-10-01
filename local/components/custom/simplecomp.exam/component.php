<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// ПОлучаем текущий ид пользователя
$currentUserID = $USER->GetID();

$arAuthors = array();
// Выбираем новости из указанного в параметрах инфоблока
$arFilter = Array('IBLOCK_ID'=>$arParams["NEWS_IBLOCK_ID"]);
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_".$arParams["AUTHOR_FIELD_CODE"]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
// Проходим циклом по выбранным новостям и состовляем массив авторов
while($newsObject = $res->Fetch())
{
    // Если автор уже есть в массиве, добавляем текущую новость ему в массив
    if ( array_key_exists($newsObject["PROPERTY_AUTHOR_VALUE"], $arAuthors) )
    {
        array_push($arAuthors[$newsObject["PROPERTY_AUTHOR_VALUE"]], $newsObject["ID"]);
    }
    // Если еще нету, то создаем запись этого автора с текущей новостью
    else
    {
        $arAuthors[$newsObject["PROPERTY_AUTHOR_VALUE"]] = array($newsObject["ID"]);
    }
}
// Инициализируем массив, который будет использоваться в шаблоне
/*
Массив будет вида:
Array(
 [id_автора] => Array(
  [AUTHOR] => Array(
   [LOGIN] => логин автора
   [TYPE] => тип автора
  )

  [NEWS] => Array(
   [id новости] => Array(
    [NAME] => заголовок
    [DATE] => дата
    [AUTHORS] => Array(
     [0] => id автора
     [1] => id автора
    )
   )
  )
 )
)
*/
$resultArray = array();
$counter = 0;

foreach ($arAuthors as $key => $value)
{
    // Перебираем массив с ключами авторов и их новостями
    $arFilter = array("ID" => $key);
    $arParameters = array(
        "SELECT" => array($arParams["AUTHOR_FIELD_TYPE_CODE"]),
        "FIELDS" => array("ID", "LOGIN", "UF_AUTHOR_TYPE")
    );
    // Получаем автора текущей итерации цикла
    $rsUser = CUser::GetList(
        $by = 'ID', 
        $order = 'ASC', 
        $arFilter,
        $arParameters
    );
    // Если автор получен, то добавляем информацию о нем в массив
    if ($userObject = $rsUser->Fetch())
    {
        $author = array(
            "LOGIN" => $userObject["LOGIN"],
            "TYPE" => $userObject["UF_AUTHOR_TYPE"]
        );

        $resultArray[$key] = array(
            "AUTHOR" => $author,
            "NEWS" => array(),
        );
        // И выбиреаем все новости где есть этот автор
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_AUTHOR");
        $arFilter = Array('IBLOCK_ID'=>$arParams["NEWS_IBLOCK_ID"], "ID"=>$value);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        // Для каждой новости создаем ее массив
        while($ob = $res->Fetch())
        {
            $news = array(
                "NAME" => $ob["NAME"],
                "DATE" => $ob["DATE_ACTIVE_FROM"],
                "AUTHORS" => array(
                    $ob["PROPERTY_AUTHOR_VALUE"]
                )
            );
            // Если этой новости еще нет в итоговом массиве, то добавляем ее целиком
            if ( !array_key_exists($ob["ID"], $resultArray[$key]["NEWS"]) )
            {
                $resultArray[$key]["NEWS"][$ob["ID"]] = $news;
            }
            // Если она там есть, значит нужно добавить только автора
            else
            {
                array_push(
                    $resultArray[$key]["NEWS"][$ob["ID"]]["AUTHORS"],
                    $ob["PROPERTY_AUTHOR_VALUE"]
                );
            }
        }
    }
}

print_r($resultArray);


$APPLICATION->SetTitle("Разделов - ".$counter); 
$arResult["CATALOG"] = $resultArray;

$this->IncludeComponentTemplate();



?>