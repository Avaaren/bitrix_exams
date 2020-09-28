<?
//Обработчик в файле /bitrix/php_interface/init.php
AddEventHandler("main", "OnBeforeEventAdd", array("MailChanger", "OnBeforeEventAddHandler"));
class MailChanger
{   
    public static function logging($description, $severity = "INFO", $auditType = "CUSTOM_DEFAULT") 
    {    
        CEventLog::Add(array(
         "SEVERITY" => $severity,
         "AUDIT_TYPE_ID" => $auditType,
         "MODULE_ID" => "main",
         "DESCRIPTION" => $description,
      ));
    }

    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {   
        // Если событие добавления принадлежит форме, то работаем с ним
        if ($event = "FEEDBACK_FORM")
        {
            global $USER;
            // После проверки авторизации пользователя выбираем содержание макроса
            if ( $USER->IsAuthorized() )
            {   
                // Получаем данные текущего пользователя в массив
                $arUser = $USER->GetByID( $USER->GetID() )->Fetch();
                // Если авторизован - заполняем данными аккаунта
                $resultString = "Пользователь авторизован:  Логин - ".$arUser["LOGIN"].
                ", имя - ".$arUser["NAME"].", данные из формы: ".$arFields["AUTHOR"];
                $arFields["AUTHOR"] = $resultString;
            }
            else
            {
                // Если не авторизован, то заполняем данными из формы
                $resultString = "Пользователь не авторизован:  Данные из формы: ".$arFields["AUTHOR"];
                $arFields["AUTHOR"] = $resultString; 
            }
            // Записываем в журнал событий 
            self::logging("Замена данных в отсылаемом письме – $resultString");
        }  
    }
}
?>