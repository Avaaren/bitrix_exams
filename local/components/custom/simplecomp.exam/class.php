<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class CSimpleComponent extends CBitrixComponent
{
    private $user, $application;

    function __construct($component = null)
    {
        global $USER, $APPLICATION;
        parent::__construct($component);

        $this->user = $USER;
        $this->application = $APPLICATION;
    }

    public function getResult()
    {
        $arFirm = array();
        // Выбор с учетом прав доступа и только там где заполнена фирма
        $arFilter = Array(
            'IBLOCK_ID'=>$this->arParams["PRODUCTS_IBLOCK_ID"],
            "ACTIVE"=>"Y",
            "!PROPERTY_FIRMA" => false,
            "CHECK_PERMISSIONS" => "Y"
        );
        $arSelect = Array("ID", "NAME", "PROPERTY_".$this->arParams["PRODUCT_PROPERTY_CODE"]);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        // Заполняем массив фирм полученной продукцией
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
        $arFirmKeys = array_keys($arFirm);
        // Выбираем фирмы листом, чтобы работала пагинация
        $arSelect = Array("ID", "IBLOCK_ID", "NAME");
        $arFilter = Array(
            "IBLOCK_ID"=>$arParams["CLASS_IBLOCK_ID"], 
            "ID"=>$arFirmKeys, 
            "ACTIVE"=>"Y",
            "CHECK_PERMISSIONS" => "Y"
        );
        $arNavParams = array(
            'nPageSize' => $arParams["NUM_ELEMENTS"],   // количество элементов на странице
            'bShowAll' => true, // показывать ссылку «Все элементы»?
        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, $arNavParams, $false);
        
        $this->arResult["NAV_STRING"] = $res->GetPageNavString(
            'Элементы', // поясняющий текст
            'modern',   // имя шаблона
            true       // показывать всегда?
        );
        while ( $obFirm = $res->Fetch() )
        {
            $resultArray[$obFirm["ID"]] = array(
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
                "ID"=>$arFirm[$obFirm["ID"]], 
                "ACTIVE"=>"Y",
                "CHECK_PERMISSIONS" => "Y"
            );
            $newsRes = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            
            while( $productObject = $newsRes->GetNext() )
            {   
                array_push($resultArray[$obFirm["ID"]]["PRODUCTS"], $productObject);
            }
        
        }
        $counter = sizeof(array_keys($resultArray));

        $this->arResult["CATALOG"] = $resultArray;
        $this->arResult["COUNTER"] = $counter;
        $this->SetResultCacheKeys(array("COUNTER"));
    }

    public function loadModules()
    {
        if ( !\Bitrix\Main\Loader::includeModule('iblock') )
        {
            $this->AbortResultCache();
        }
    }

    public function executeComponent()
    {
        $this->loadModules();

        $groups = $this->user->GetGroups();
        if ( $this->startResultCache(false, $groups) ) 
        {
            // Если пользователь не администратор, то не кешировать
            if ( !in_array(1, $groups))
            {
                $this->AbortResultCache();
            }
            $this->getResult();
            $this->includeComponentTemplate();
        }
    $this->application->SetTitle("Разделов - ".$this->arResult["COUNTER"]);
    }
} 