<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?
if ($arParams['SEF_MODE'] == "Y")
{
    echo "PARAM_1 = ".$arResult['VARIABLES']["PARAM_1"];
    echo "<br> PARAM_2 = ".$arResult['VARIABLES']["PARAM_2"];
}
else
{
    echo "PARAM_1 = ".$_REQUEST[$arParams['VARIABLE_ALIASES']['PARAM_1']];
    echo "<br> PARAM_2 = ".$_REQUEST[$arParams['VARIABLE_ALIASES']['PARAM_2']];
}
?>
