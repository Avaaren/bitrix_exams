<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 
$arComponentDescription = array(
    "NAME" => GetMessage("Каталог товаров"),
    "DESCRIPTION" => GetMessage("Каталог товаров с новостями"),
    "PATH" => array(
        "ID" => "custom_components",
        "CHILD" => array(
            "ID" => "custom_catalog",
            "NAME" => "Каталог товаров"
        )
    ),
    "ICON" => "/images/icon.gif",
    );
?>