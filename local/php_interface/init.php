<?
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'productChange');

    function productChange($arFields)
    {  
        $elementId = $arFields["ID"];
        $arSelect = array("ID", "IBLOCK_ID", "SHOW_COUNTER");
        $arFilter = Array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "ID"=>$elementId);
        
        $res = CIBlockElement::GetList(
            array(),
            $arFilter,
            false,
            false,
            $arSelect
        );
            if($ob = $res->Fetch())
            {
                if ( $ob["SHOW_COUNTER"] > 2 && $arFields["ACTIVE"] == "N")
                {
                    $APPLICATION->throwException("Нельзя деактивировать, количество просмотров = ".$ob["SHOW_COUNTER"]);
                    return false;
                }
            } 
    }