<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class CSimpleComponent extends CBitrixComponent
{

    private $user, $application;

    function __construct($component = null)
    {
        global $USER, $APPLICATION;
        parent::__construct($component);

        $this->user = $USER;
        $this->application = $APPLICATION;
    }

    public function checkResultArray(&$resultArray)
    {
        // Выбираем текущего пользователя и получаем нужные параметры
        $currentUserID = $this->user->GetID();
        
        $rsUser = CUser::GetList(
            $by = 'ID', 
            $order = 'ASC', 
            array("ID" => $currentUserID),
            array(
                "SELECT" => array($this->arParams["AUTHOR_FIELD_TYPE_CODE"]),
                "FIELDS" => array("ID", "LOGIN")
                )
        );
        $arUser = $rsUser->Fetch();
        $currentUserType = $arUser["UF_AUTHOR_TYPE"];

        // Нужно реализовать доп. логику в выборке:
        foreach ( $resultArray as $key => $value )
        {
            // Убрать текущего пользователя и его новости
            if ($key == $currentUserID)
            {
                unset($resultArray[$key]);
            }
            // Убрать авторов с типом, отличным от типа текущего пользователя
            if ( $value["AUTHOR"]["TYPE"] != $currentUserType)
            {
                unset($resultArray[$key]);
            }
            // Убрать новости, в авторах у которых, есть текущий пользователь
            foreach ( $value["NEWS"] as $news_key => $news_value )
            {
                if ( in_array($currentUserID, $news_value["AUTHORS"]) )
                {
                    unset($resultArray[$key]["NEWS"][$news_key]);
                }

            }
        }
    }

    public function getResult()
    {
        $arFilter = Array('IBLOCK_ID'=>$this->arParams["NEWS_IBLOCK_ID"]);
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_".$this->arParams["AUTHOR_FIELD_CODE"]);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        // Проходим циклом по выбранным новостям и состовляем массив авторов
        while($newsObject = $res->Fetch())
        {
            // Если автор уже есть в массиве, добавляем текущую новость ему в массив
            if ( array_key_exists($newsObject["PROPERTY_AUTHOR_VALUE"], $arAuthors) )
            {
                array_push($arAuthors[$newsObject["PROPERTY_AUTHOR_VALUE"]], $newsObject["ID"]);
            }
            // Если еще нету, то создаем запись этого автора с текущей новостью
            else
            {
                $arAuthors[$newsObject["PROPERTY_AUTHOR_VALUE"]] = array($newsObject["ID"]);
            }
        }
        // Инициализируем массив, который будет использоваться в шаблоне
        /*
        Массив будет вида:
        Array(
        [id_автора] => Array(
        [AUTHOR] => Array(
            [LOGIN] => логин автора
            [TYPE] => тип автора
        )

        [NEWS] => Array(
        [id новости] => Array(
            [NAME] => заголовок
            [DATE] => дата
            [AUTHORS] => Array(
            [0] => id автора
            [1] => id автора
            )
        )
        )
        )
        )
        */
        $resultArray = array();
        $counter = 0;
        $ids = array();
        // Перебираем массив с ключами авторов и их новостями
        foreach ($arAuthors as $key => $value)
        {
            $arFilter = array("ID" => $key);
            $arParameters = array(
                "SELECT" => array($this->arParams["AUTHOR_FIELD_TYPE_CODE"]),
                "FIELDS" => array("ID", "LOGIN", "UF_AUTHOR_TYPE")
            );
            // Получаем автора текущей итерации цикла
            $rsUser = CUser::GetList(
                $by = 'ID', 
                $order = 'ASC', 
                $arFilter,
                $arParameters
            );
            // Если автор получен, то добавляем информацию о нем в массив
            if ($userObject = $rsUser->Fetch())
            {
                $author = array(
                    "LOGIN" => $userObject["LOGIN"],
                    "TYPE" => $userObject["UF_AUTHOR_TYPE"]
                );

                $resultArray[$key] = array(
                    "AUTHOR" => $author,
                    "NEWS" => array(),
                );
                // И выбиреаем все новости где есть этот автор
                $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_AUTHOR");
                $arFilter = Array('IBLOCK_ID'=>$this->arParams["NEWS_IBLOCK_ID"], "ID"=>$value);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                // Для каждой новости создаем ее массив
                while($ob = $res->Fetch())
                {
                    $news = array(
                        "NAME" => $ob["NAME"],
                        "DATE" => $ob["DATE_ACTIVE_FROM"],
                        "AUTHORS" => array(
                            $ob["PROPERTY_AUTHOR_VALUE"]
                        )
                    );
                    // Если этой новости еще нет в итоговом массиве, то добавляем ее целиком
                    if ( !array_key_exists($ob["ID"], $resultArray[$key]["NEWS"]) )
                    {
                        $resultArray[$key]["NEWS"][$ob["ID"]] = $news;
                    }
                    // Если она там есть, значит нужно добавить только автора
                    else
                    {
                        array_push(
                            $resultArray[$key]["NEWS"][$ob["ID"]]["AUTHORS"],
                            $ob["PROPERTY_AUTHOR_VALUE"]
                        );
                    }
                }
            }
        }
        $this->checkResultArray($resultArray);
        
        // Цикл для проверки количества вошедших новостей
        foreach ( $resultArray as $key => $value )
        {
            foreach ( $value["NEWS"] as $news_key => $news_value )
            {
                array_push($ids, $news_key);
            }
        }

        if ( $this->user->IsAuthorized() )
        {
            $this->arResult["NEWS"] = $resultArray;
        }
        else
        {
            $this->arResult["NON_AUTH"] = "Y";
        }
        $this->arResult["COUNTER"] = sizeof( array_unique($ids) );
        $this->SetResultCacheKeys(array("COUNTER"));
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
        $this->loadModules();

        if ( $this->startResultCache(false, $this->user->GetID()) ) 
        {
            $this->getResult();
            $this->includeComponentTemplate();
        }
    $this->application->SetTitle("Уникальных новостей - ".$this->arResult["COUNTER"]);
    }
}