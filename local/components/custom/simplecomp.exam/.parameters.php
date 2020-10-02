
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
        "CLASS_IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока с классификатором",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        "LINK_TEMPLATE" => array(
            "PARENT" => "BASE",
            "NAME" => "Шаблон ссылки на детальный просмотр товара",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        "PRODUCT_PROPERTY_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => "Код свойства товара, в котором хранится привязка товара к классификатору",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
        ),
        "CACHE_TIME" => array(),
    ),
);