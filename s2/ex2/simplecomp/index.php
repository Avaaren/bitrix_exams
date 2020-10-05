<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"custom:simplecomp.exam",
	".default",
	Array(
		"CACHE_TIME" => "10",
		"CACHE_TYPE" => "A",
		"CLASS_IBLOCK_ID" => "11",
		"COMPONENT_TEMPLATE" => ".default",
		"LINK_TEMPLATE" => "",
		"NUM_ELEMENTS" => "2",
		"PRODUCTS_IBLOCK_ID" => "6",
		"PRODUCT_PROPERTY_CODE" => "FIRMA"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>