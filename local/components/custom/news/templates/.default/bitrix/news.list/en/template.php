<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<?if ( isset($arItem['DISPLAY_PROPERTIES']['ENGLISH_NAME']) && isset( $arItem['DISPLAY_PROPERTIES']['ENGLISH_PREVIEW']) ):?>
	<p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<h3><?echo $arItem['DISPLAY_PROPERTIES']['ENGLISH_NAME']['VALUE']?></h3>
		<p><?echo $arItem['DISPLAY_PROPERTIES']['ENGLISH_PREVIEW']['VALUE']['TEXT']?></p>
		<p>Дата начала активности: <?echo $arItem["DATE_ACTIVE_FROM"];?></p>
	</p>
	<?endif;?>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
