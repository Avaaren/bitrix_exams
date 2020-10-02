<?
foreach($arResult["CATALOG"] as $key =>$value) {
    foreach($value["PRODUCTS"] as $p_key=>$arItem){

	$arButtons = CIBlock::GetPanelButtons(
		$arItem["IBLOCK_ID"],
		$arItem["ID"],
		0,
		array("SECTION_BUTTONS"=>false, "SESSID"=>false)
    );
    
    // echo '<pre>', print_r($arButtons), '</pre>';
    $template_id = intval("$key".$arItem["ID"]);
    $arResult["CATALOG"][$key]["PRODUCTS"][$p_key]["TEMPLATE_ID"] = $template_id;
	$arItem["ADD_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];
	$arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
	$arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

	$arItem["ADD_LINK_TEXT"] = $arButtons["edit"]["add_element"]["TEXT"];
	$arItem["EDIT_LINK_TEXT"] = $arButtons["edit"]["edit_element"]["TEXT"];
	$arItem["DELETE_LINK_TEXT"] = $arButtons["edit"]["delete_element"]["TEXT"];

    // print_r($arItem); 
	$this->AddEditAction($template_id, $arItem['ADD_LINK'], $arItem["ADD_LINK_TEXT"]);
	$this->AddEditAction($template_id, $arItem['EDIT_LINK'], $arItem["EDIT_LINK_TEXT"]);
	$this->AddDeleteAction($template_id, $arItem['DELETE_LINK'], $arItem["DELETE_LINK_TEXT"], array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));   
}
}
?>