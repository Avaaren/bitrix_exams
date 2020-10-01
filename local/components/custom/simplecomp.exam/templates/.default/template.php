<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="news-list-wrapper">
<?if ( $arResult["NON_AUTH"] == "Y" ):?>
    <div class="error-message">
        <p>Авторизируйтесь, чтобы просматривать контент</p>
    </div>
<?else:?>
    <?foreach( $arResult["NEWS"] as $key => $value):?>
        <div class="news-list-header">
            <h4>
                [<?echo $key;?>] - <?echo $value["AUTHOR"]["LOGIN"];?>
            </h4>
            <ul class="news-list">
                <?foreach( $value["NEWS"] as $news_key => $news_value):?>
                    <li class="news-list-item">
                        <? echo $news_value["NAME"]; ?> - <?echo $news_value["DATE"]; ?>
                    </li>
                <?endforeach;?>
            </ul>
        </div>
    <?endforeach;?>
<?endif;?>
</div>
