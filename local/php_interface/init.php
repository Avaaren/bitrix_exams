<?
$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'productChange');

    function productChange($arFields)
    {  
        global $_SESSION;
        if ($arFields["IBLOCK_ID"] == 7)
        {
            $_SESSION["UPDATED"] = "Y";
        }   
    }


?>