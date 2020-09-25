<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?
if ( $arParams['CANONICAL_REL'] )
{
	// Получаем элементы инфоблока заданного в canonical rel
	$arSelect = Array("ID", "NAME");
	$arFilter = Array("IBLOCK_ID" => $arParams['CANONICAL_REL']);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	// Если он не пуст, то проверяем его элементы
	if ( $res->SelectedRowsCount() )
	{
        while($ob = $res->GetNextElement())
        // Для каждого элемента получаем выбранные ранее поля
		{
            $arFields = $ob->GetFields();
            // По полю ид получаем свойства для текущего элемента
            $db_props = CIBlockElement::GetProperty(9, $arFields["ID"], false, false);
            // Так как свойство только одно, можно обойтись без цикла и сразу получить нужное
            $ar_props = $db_props->Fetch();
            // Сравниваем ид текущей новости и ид новости в записи в инфоблоке
            // Если они равны, то мы нашли запись с привязкой к текущей новости
            if ( $arParams["ELEMENT_ID"] == $ar_props["VALUE"] )
            {
                $cp = $this->__component;
                if (is_object($cp))
                {
                    $cp->arResult['IS_CANONICAL'] = "Y";
                    $cp->arResult['CANONICAL_NAME'] = $arFields["NAME"] ;
                    $cp->SetResultCacheKeys(array('IS_CANONICAL','CANONICAL_NAME'));
                }
            }
		}
	}
}
?>
