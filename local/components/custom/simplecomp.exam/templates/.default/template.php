<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="product-list-wraper">
<?if(!empty($arResult["CATALOG"])):?>
	<div class="product-list-block">
      <h3>Каталог</h3>
            <?foreach($arResult["CATALOG"] as $arItem):?>
            <div class="product-header">
                  <b>
                        <?echo $arItem["FIRM_NAME"]?>
                  </b> 
            </div> 

            <ul class="product-list">
                  <?foreach($arItem["PRODUCTS"] as $product):?>
                  <li class="product-list-item">
                        <?echo $product["NAME"]?> - 
                        <?echo $product["PROPERTY_PRICE_VALUE"]?> - 
                        <?echo $product["PROPERTY_MATERIAL_VALUE"]?> - 
                        <?echo $product["PROPERTY_ARTNUMBER_VALUE"]?>
                        <br>  
                        <?echo $product["DETAIL_PAGE_URL"]?> 
                  </li>
                  <?endforeach;?> 
            </ul>
            <?endforeach;?>
      </div>
<?endif;?>

<?$this->SetViewTarget("sidebar");?>
      <div style="color:red; margin: 34px 15px 35px 15px">
            Максимальная цена - <?echo "\"". $arResult["PRICES"]["MAX"]["NAME"]."\"". $arResult["PRICES"]["MAX"]["PRICE"];?>
            <br>
            Минимальная цена - <?echo "\"". $arResult["PRICES"]["MIN"]["NAME"]."\"". $arResult["PRICES"]["MIN"]["PRICE"];?>
      </div>
<?$this->EndViewTarget("sidebar");?>
</div>