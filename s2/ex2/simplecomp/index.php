<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"custom:simplecomp.exam",
	"",
	Array(
		"AUTHOR_FIELD_CODE" => "AUTHOR",
		"AUTHOR_FIELD_TYPE_CODE" => "UF_AUTHOR_TYPE",
		"CACHE_TIME" => "10",
		"CACHE_TYPE" => "A",
		"NEWS_IBLOCK_ID" => "5"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>