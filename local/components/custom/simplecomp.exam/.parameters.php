<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 $arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "PRODUCTS_IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока с каталогом товаров",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        "NEWS_IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока с новостями",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        "UF_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => "Код пользовательского свойства разделов каталога, в котором хранится привязка к
            новостям",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        "CACHE_TIME" => array(),
    ),
);