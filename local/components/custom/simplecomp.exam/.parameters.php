<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 $arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "NEWS_IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока с новостями",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        // В блоке Новостей
        "AUTHOR_FIELD_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => "Код свойства информационного блока, в котором хранится Автор",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        // В блоке пользователей
        "AUTHOR_FIELD_TYPE_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => "Код пользовательского свойства пользователей, в котором хранится тип автора",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        "CACHE_TIME" => array(),
    ),
);