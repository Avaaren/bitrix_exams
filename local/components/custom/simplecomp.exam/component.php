<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$groups = $USER->GetGroups();

if ($_REQUEST["F"])
{
        echo "чистим";
        $this->AbortResultCache();
        $this->ClearResultCache($groups);
        
}

if ($this->StartResultCache(false, $groups))
{
    // Если запущено кеширование, то при включенном фильтре
    // Выходим из кеширования и чистим кеш
    if ($_REQUEST["F"])
    {
        echo "Абортнули";
        $this->AbortResultCache();
        $this->ClearResultCache($groups);      
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
            if ($_REQUEST["F"])
            {
                $arFilter = Array(
                    "IBLOCK_ID"=>$arParams["PRODUCTS_IBLOCK_ID"], 
                    "ID"=>$value, 
                    "ACTIVE"=>"Y",
                    "CHECK_PERMISSIONS" => "Y",
                    array(
                        "LOGIC" => "OR",
                        array("<=PROPERTY_PRICE" => 1700, "=PROPERTY_MATERIAL" => "Дерево, ткань"),
                        array("<PROPERTY_PRICE" => 1500, "=PROPERTY_MATERIAL" => "Металл, пластик"),
                    )
                );
            }
            else 
            {
                $arFilter = Array(
                    "IBLOCK_ID"=>$arParams["PRODUCTS_IBLOCK_ID"], 
                    "ID"=>$value, 
                    "ACTIVE"=>"Y",
                    "CHECK_PERMISSIONS" => "Y",
                );
            }
            
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            
            while( $productObject = $res->GetNext() )
            {   
                if ( $arParams["LINK_TEMPLATE"] )
                {
                    $productObject["DETAIL_PAGE_URL"] = SITE_DIR.$arParams["LINK_TEMPLATE"]."/".$productObject["ID"];
                }
                array_push($resultArray[$key]["PRODUCTS"], $productObject);
            }
        }
    }
    $APPLICATION->SetTitle("Разделов - ".$counter); 
    $arResult["CATALOG"] = $resultArray;
    $arResult["TEST_LINK"] = $page."?F=Y";

    $this->IncludeComponentTemplate();
}

?>