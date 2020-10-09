<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class CSimpleComponent extends CBitrixComponent
{
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
                    "ID", "IBLOCK_ID", 'NAME',"IBLOCK_TYPE_ID", 
                    "PROPERTY_PRICE", "PROPERTY_MATERIAL", 
                    "PROPERTY_ARTNUMBER", "DETAIL_PAGE_URL"
                );
                $arFilter = Array(
                    "IBLOCK_ID"=>$this->arParams["PRODUCTS_IBLOCK_ID"], 
                    "ID"=>$value, 
                    "ACTIVE"=>"Y",
                    "CHECK_PERMISSIONS" => "Y"
                );
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                // Добавляем в массив под ключ фирмы
                while( $productObject = $res->GetNext() )
                {   
                    if (!$this->arResult["IBLOCK_TYPE"])
                    {
                        $this->arResult["IBLOCK_TYPE"] = $productObject["IBLOCK_TYPE_ID"];
                    }
                    array_push($resultArray[$key]["PRODUCTS"], $productObject);
                }
            }
        }

        $this->arResult["CATALOG"] = $resultArray;
        $this->arResult["COUNTER"] = $counter;
        $this->SetResultCacheKeys(array("COUNTER", "IBLOCK_TYPE"));
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
        global $USER, $APPLICATION;
        $this->loadModules();

        $groups = $USER->GetGroups();
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
        // Задаем путь к админке инфоблока исходя из имеющихся параметров
        $iblockURL = "/bitrix/admin/iblock_element_admin.php?IBLOCK_ID=".$this->arParams["PRODUCTS_IBLOCK_ID"]."&type=".$this->arResult["IBLOCK_TYPE"];
        // Добавляем кнопку ведущую на созданный ранее урл
        $this->AddIncludeAreaIcons(
            Array( //массив кнопок toolbar'a
                Array(
                    "ID" => "ADMIN_IBLOCK",
                    "TITLE" => "ИБ в админке",
                    "URL" => $iblockURL,
                    "ICON" => "menu-delete", //CSS-класс с иконкой
                    "IN_PARAMS_MENU" => true, //показать в контекстном меню
                )
            )
        );
    $APPLICATION->SetTitle("Разделов - ".$this->arResult["COUNTER"]);
    }
} 