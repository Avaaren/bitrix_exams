<?
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'productChange');

    function productChange($arFields)
    {  
        $elementId = $arFields["ID"];
        $res = CIBlockElement::GetByID($elementId);
            if($ob = $res->GetNext())
            {
                if ( $ob["SHOW_COUNTER"] > 2 && $arFields["ACTIVE"] == "N")
                {
                    global $APPLICATION;
                    $APPLICATION->throwException("Нельзя деактивировать, количество просмотров = ".$ob["SHOW_COUNTER"]);
                    return false;
                }
            }
            
    }


?>