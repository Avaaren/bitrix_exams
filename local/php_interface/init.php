<?php

function CheckUserCount()
{
    // Получаем дату последней проверки
    $lastCheck = date(COption::GetOptionString("main", "lastAgentCheck"));
    // Вычисляем разницу между последней проверкой
    $diffInSec = time() - $lastCheck;
    
    $dt1 = new DateTime("@0");
    $dt2 = new DateTime("@$diffInSec");
    $dateDiff = $dt1->diff($dt2)->format('%a days, %h hours');
    // Приводим ее к нормальному формату
    $lastCheck = ConvertTimeStamp($lastCheck, "FULL");

    $counter = 0;
    // Добавляем параметр фильтрации по дате
    $arFilter = Array(
        "DATE_REGISTER_1" => $lastCheck,
    );
    // Выбираем пользователей, созданных после даты последней проверки
    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $arFilter);
    while($rsUser = $rsUsers->Fetch())
    {
        $counter++;
    }

    // Выбираем список админов
    $rsAdmins = CUser::GetList(($by="id"), ($order="desc"), array("GROUPS_ID" => array(1)));
    $arAdmin = array();
    while($rsAdmin = $rsAdmins->Fetch())
    {
        array_push($arAdmin, $rsAdmin["EMAIL"]);
    }
    
    $emailTo = implode(", ", $arAdmin);

    $arEventFields = array(
        'EMAIL_TO' => $emailTo,
        'COUNT' => $counter,
        'DAYS' => $dateDiff
    );
    
    CEvent::Send('USER_COUNT_CHECKED','s2',$arEventFields);
    // Задаем время последней проверки снова
    COption::SetOptionString("main", "lastAgentCheck", time());
    
    return "CheckUserCount();";
}

?>