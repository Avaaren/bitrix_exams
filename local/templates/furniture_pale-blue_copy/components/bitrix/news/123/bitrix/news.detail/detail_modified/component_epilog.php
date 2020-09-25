<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?php
if ( $arResult["IS_CANONICAL"] == "Y" )
{
    global $APPLICATION;
    $APPLICATION->SetPageProperty("canonical", $arResult["CANONICAL_NAME"]);
}
?>