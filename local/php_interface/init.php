<?php

AddEventHandler("main", "OnEpilog", "_Check404Error", 1);

function _Check404Error() {
    if (defined("ERROR_404") && ERROR_404 == "Y" || CHTTP::GetLastStatus() == "404 Not Found") {
        global $APPLICATION;
        $page = $APPLICATION->GetCurPage();

        CEventLog::Add(array(
            "SEVERITY" => "INFO",
            "AUDIT_TYPE_ID" => "ERROR_404",
            "MODULE_ID" => "main",
            "DESCRIPTION" => $page,
        ));

        $APPLICATION->RestartBuffer();
        require $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php";
        require $_SERVER["DOCUMENT_ROOT"] . "/404.php";
        require $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php";
    }
}

?>