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
        $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
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
        $counter = 0;
        // Для каждого элемента массива фирм выбираем инфу о фирме и заполняем ее
        foreach( $arFirm as $key => $value )
        {
            $counter++;
            $arSelect = Array("ID", "IBLOCK_ID", "NAME");
            $arFilter = Array(
                "IBLOCK_ID"=>$this->arParams["CLASS_IBLOCK_ID"], 
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
                // Для каждой фирмы из массива выбираем ее продукцию
                $arSelect = Array(
                    "ID", "IBLOCK_ID", "CODE", 'NAME', 
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

                $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
                // Добавляем в массив под ключ фирмы
                while( $productObject = $res->GetNext() )
                {   
                    if ( $this->arParams["LINK_TEMPLATE"] )
                    {
                        $productObject["DETAIL_PAGE_URL"] = CIBlock::ReplaceDetailUrl($this->arParams["LINK_TEMPLATE"],$productObject, false, "E");
                    }
                    array_push($resultArray[$key]["PRODUCTS"], $productObject);
                }
            }
        }

        $this->arResult["CATALOG"] = $resultArray;
        $this->arResult["COUNTER"] = $counter;
        $this->arResult["TEST_LINK"] = $page."?F=Y";

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
        // Если найден валидный кеш, то очищаем его и не кешируем
        if ($_REQUEST["F"])
        {
            // $this->AbortResultCache();
            $this->ClearResultCache($this->user->GetGroups());      
        }

        if ( $this->startResultCache(false, $this->user->GetGroups()) ) 
        {
            if ($_REQUEST["F"])
            {
                // Если не найден, то прерываем кеширование
                $this->AbortResultCache();
                // $this->ClearResultCache($this->user->GetGroups());      
            }
            $this->getResult();
            $this->includeComponentTemplate();
        }
    $this->application->SetTitle("Разделов - ".$this->arResult["COUNTER"]);
    }
}  