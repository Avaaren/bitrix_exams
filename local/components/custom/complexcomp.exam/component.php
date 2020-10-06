<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */	

function makeUrl($examTemplatesArray, $arParams)
{
	if ( $arParams["SEF_MODE"] == "Y" )
	{
		// Создаем ссылку на основе шаблона url
		$pageTemplate = $examTemplatesArray["SEF"];
		$resultSefUrl = str_replace("#PARAM_1#", 123, $pageTemplate);
		$resultSefUrl = str_replace("#PARAM_2#", 456, $resultSefUrl);
		$resultSefUrl = $arParams["SEF_FOLDER"].$resultSefUrl;
		// Получаем запрашиваемый url
		$requestURL = Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage();
		// Убираем из него index.php
		$requestURL = substr_replace($requestURL,"",strpos($requestURL, "index.php"), strlen("index.php"));

		return array(
			"request_url" => $requestURL,
			"result_url" => $resultSefUrl
		);
		
	}
	else 
	{
		global $APPLICATION;

		$pageTemplate = $examTemplatesArray["NOT_SEF"];
		$resultSefUrl = str_replace("#PARAM_1#", 123, $pageTemplate);
		$resultSefUrl = str_replace("#PARAM_2#", 456, $resultSefUrl);
	
		$dir = $APPLICATION->GetCurDir();
		$resultSefUrl = $dir.$resultSefUrl;
		$requestURL = Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage();
		$requestURL = substr_replace($requestURL,"",strpos($requestURL, "index.php"), strlen("index.php"));
		
		return array(
			"request_url" => $requestURL,
			"result_url" => $resultSefUrl
		);
	}
}

$examTemplatesArray = array(
	"SEF" => $arParams["SEF_URL_TEMPLATES"]["exampage"],
	"NOT_SEF" => "?".$arParams['VARIABLE_ALIASES']['PARAM_1']."=#PARAM_1#&".$arParams['VARIABLE_ALIASES']['PARAM_2']."=#PARAM_2#",
);

if($arParams["USE_FILTER"]=="Y")
{
	if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
		$arParams["FILTER_NAME"] = "arrFilter";
}
else
	$arParams["FILTER_NAME"] = "";

$arDefaultUrlTemplates404 = array(
	"sections_top" => "",
	"section" => "#SECTION_ID#/",
	"detail" => "#SECTION_ID#/#ELEMENT_ID#/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
	"SECTION_ID",
	"SECTION_CODE",
	"ELEMENT_ID",
	"ELEMENT_CODE",
	"PARAM_1",
	"PARAM_2"
);
if($arParams["SEF_MODE"] == "Y")
{
	$arVariables = array();

	$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

	$engine = new CComponentEngine($this);
	if (CModule::IncludeModule('iblock'))
	{
		$engine->addGreedyPart("#SECTION_CODE_PATH#");
		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
	}
	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_FOLDER"],
		$arUrlTemplates,
		$arVariables
	);

	$b404 = false;
	if(!$componentPage)
	{
		$componentPage = "sections_top";
		$b404 = true;
	}

	if(
		$componentPage == "section"
		&& isset($arVariables["SECTION_ID"])
		&& intval($arVariables["SECTION_ID"])."" !== $arVariables["SECTION_ID"]
	)
		$b404 = true;

	if($b404 && CModule::IncludeModule('iblock'))
	{
		$folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
		if ($folder404 != "/")
			$folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
		if (substr($folder404, -1) == "/")
			$folder404 .= "index.php";

		if ($folder404 != $APPLICATION->GetCurPage(true))
		{
			\Bitrix\Iblock\Component\Tools::process404(
				""
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SHOW_404"] === "Y")
				,$arParams["FILE_404"]
			);
		}
	}

	CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

	$arResult = array(
		"FOLDER" => $arParams["SEF_FOLDER"],
		"URL_TEMPLATES" => $arUrlTemplates,
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases,
	);
}
else
{
	$arVariables = array();

	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
	CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);
	$componentPage = "";

	if(isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0)
		$componentPage = "detail";
	elseif(isset($arVariables["ELEMENT_CODE"]) && strlen($arVariables["ELEMENT_CODE"]) > 0)
		$componentPage = "detail";
	elseif(isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0)
		$componentPage = "section";
	elseif(isset($arVariables["SECTION_CODE"]) && strlen($arVariables["SECTION_CODE"]) > 0)
		$componentPage = "section";
	elseif(isset($arVariables["ELEMENT_CODE"]) && strlen($arVariables["ELEMENT_CODE"]) > 0)
		$componentPage = "detail";
	elseif(isset($arVariables["PARAM_1"]) && strlen($arVariables["PARAM_1"]) > 0)
		$componentPage = "exampage";
	else
		$componentPage = "sections_top";

	$arResult = array(
		"FOLDER" => "",
		"URL_TEMPLATES" => Array(
			"section" => htmlspecialcharsbx($APPLICATION->GetCurPage())."?".$arVariableAliases["SECTION_ID"]."=#SECTION_ID#",
			"detail" => htmlspecialcharsbx($APPLICATION->GetCurPage())."?".$arVariableAliases["SECTION_ID"]."=#SECTION_ID#"."&".$arVariableAliases["ELEMENT_ID"]."=#ELEMENT_ID#",
		),
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}
$examUrls = makeUrl($examTemplatesArray, $arParams);
// Если шаблон схож с шаблоном других страниц
if ( $examUrls["request_url"] == $examUrls["result_url"] )
{
	$componentPage = "exampage";
}
elseif ($componentPage == "sections_top")
{	
	$arResult["EXAM_PAGE_URL"] = $examUrls["result_url"];
}
$this->IncludeComponentTemplate($componentPage);
?>