<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности");
?>Наиболее тяжелая страница:<br>
 /s2/news/index.php - 40.39% - 2.92 сек<br>
 <br>
 Компоненты с наибольшим числом запросов:<br>
bitrix:furniture.catalog.random - 5 запросов - 0.0053 сек<br>
bitrix:news.list - 4 запроса - 0.01сек<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>