<?php

function removeContentFromMenu()
{   
    /* Обращаемся к глобальной переменной $adminMenu не через
    событие, а через admin_header, так как по неведомой причине
    некоторые пункты добавляются в меню ПОСЛЕ отработки события,
    а следовательно их нельзя убрать. admin_header.php подключается
    уже после отработки события, поэтому здесь мы можем почистить уже 
    готовое меню
    */
    global $adminMenu;
    // Убираем все пункты главного меню != Контент
    foreach($adminMenu->aGlobalMenu as $key => $menuItem) 
    {
        if ( $key != "global_menu_content" )
        {
            unset($adminMenu->aGlobalMenu[$key]);
        }

        else
        {
            // Если главное меню == Контент, убираем подпункты != Новости
            foreach ( $menuItem["items"] as $k=>$v )
            {
                if ($v['text'] != "Новости")
                {
                    // aGlobalMenu[global_menu_content][items][цифра]
                    // 1 - файлы, 3 - вакансии и т.д.
                    unset($adminMenu->aGlobalMenu[$key]['items'][$k]);
                }
            }
        }  
    }
}


?>