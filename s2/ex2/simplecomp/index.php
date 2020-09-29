<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"custom:simplecomp.exam",
	"",
	Array(
		"NEWS_IBLOCK_ID" => "",
		"PRODUCTS_IBLOCK_ID" => "",
		"UF_CODE" => ""
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>