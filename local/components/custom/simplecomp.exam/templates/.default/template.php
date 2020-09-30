<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="product-list-wraper">
<?if(!empty($arResult["NEWS_CATALOG"])):?>
	<div class="product-list-block">
      <h3>Каталог</h3>
            <?foreach($arResult["NEWS_CATALOG"] as $arItem):?>
            <div class="product-header">
            <b>
                  <?echo $arItem["NEWS_NAME"]?> - <?echo $arItem["NEWS_DATE"]?>
            </b> (
                  <?foreach($arItem["CATEGORIES"] as $key => $category):?>
                        <?if ($key == array_key_last($arItem["CATEGORIES"])):?>
                              <?echo "$category"?>
                        <?else:?> 
                              <?echo "$category, "?> 
                        <?endif;?>
                  <?endforeach;?>
                  )
            </div> 

            <ul class="product-list">
                  <?foreach($arItem["PRODUCTS"] as $product):?>
                  <li class="product-list-item">
                        <?echo $product["NAME"]?> - <?echo $product["PROPERTY_PRICE_VALUE"]?> - <?echo $product["PROPERTY_MATERIAL_VALUE"]?> - <?echo $product["PROPERTY_ARTNUMBER_VALUE"]?>  
                  </li>
                  <?endforeach;?> 
            </ul>
            <?endforeach;?>
      </div>
<?endif;?>

</div>
