<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("feedback");
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.feedback", 
	".default", 
	array(
		"EMAIL_TO" => "sale@bitrix.site",
		"EVENT_MESSAGE_ID" => array(
			0 => "7",
		),
		"OK_TEXT" => "Спасибо, ваше сообщение принято.",
		"REQUIRED_FIELDS" => array(
		),
		"USE_CAPTCHA" => "Y",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>