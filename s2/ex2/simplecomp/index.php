<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"custom:simplecomp.exam", 
	".default", 
	array(
		"CACHE_TIME" => "10",
		"CACHE_TYPE" => "A",
		"CLASS_IBLOCK_ID" => "11",
		"LINK_TEMPLATE" => "",
		"PRODUCTS_IBLOCK_ID" => "6",
		"PRODUCT_PROPERTY_CODE" => "FIRMA",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>