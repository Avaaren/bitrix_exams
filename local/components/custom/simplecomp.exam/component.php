<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$groups = $USER->GetGroups();
if ( $this->StartResultCache(false, $groups,$APPLICATION->GetCurDir()) )
{
    // Если пользователь контент менеджер, то не кешировать
    if ( in_array(8, $groups) && !in_array(1, $groups))
    {
        $this->AbortResultCache();
    }
    $arFirm = array();
    $arFilter = Array(
        'IBLOCK_ID'=>$arParams["PRODUCTS_IBLOCK_ID"],
        "ACTIVE"=>"Y",
        "!PROPERTY_FIRMA" => false,
        "CHECK_PERMISSIONS" => "Y"
    );
    $arSelect = Array("ID", "NAME", "PROPERTY_".$arParams["PRODUCT_PROPERTY_CODE"]);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while($ar_result = $res->Fetch())
    {

            if ( array_key_exists($ar_result["PROPERTY_FIRMA_VALUE"], $arFirm) )
            {
                array_push($arFirm[$ar_result["PROPERTY_FIRMA_VALUE"]], $ar_result["ID"]);
            }
            else
            {
                $arFirm[$ar_result["PROPERTY_FIRMA_VALUE"]] = array($ar_result["ID"]);
            }
    }
    $resultArray = array();
    $counter = 0;
    $prices = array();
    foreach( $arFirm as $key => $value )
    {
        $counter++;
        $arSelect = Array("ID", "IBLOCK_ID", "NAME");
        $arFilter = Array(
            "IBLOCK_ID"=>$arParams["CLASS_IBLOCK_ID"], 
            "ID"=>$key, 
            "ACTIVE"=>"Y",
            "CHECK_PERMISSIONS" => "Y"
        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

        if ( $obFirm = $res->Fetch() )
        {
            $resultArray[$key] = array(
                "FIRM_NAME" => $obFirm["NAME"],
                "PRODUCTS" => array(),
            );

            $arSelect = Array(
                "ID", "IBLOCK_ID", 'NAME', 
                "PROPERTY_PRICE", "PROPERTY_MATERIAL", 
                "PROPERTY_ARTNUMBER", "DETAIL_PAGE_URL"
            );
            $arFilter = Array(
                "IBLOCK_ID"=>$arParams["PRODUCTS_IBLOCK_ID"], 
                "ID"=>$value, 
                "ACTIVE"=>"Y",
                "CHECK_PERMISSIONS" => "Y"
            );
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            
            while( $productObject = $res->GetNext() )
            {   
                $prices[$productObject["NAME"]] = $productObject["PROPERTY_PRICE_VALUE"];
                array_push($resultArray[$key]["PRODUCTS"], $productObject);
            }
        }
    }
    $APPLICATION->SetTitle("Разделов - ".$counter); 
    $arResult["CATALOG"] = $resultArray;
    $arResult["PRICES"] = array(
        "MAX" => array(
            "NAME" => array_keys($prices,max($prices))[0],
            "PRICE" => max($prices)
        ),
        "MIN" => array(
            "NAME" => array_keys($prices,min($prices))[0],
            "PRICE" => min($prices)
        ),
    );
    $this->IncludeComponentTemplate();
}

?>