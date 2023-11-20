<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>

<section class="mainpage_slider">
  <div class="slider stick-dots">

    <?foreach ($arResult["ITEMS"] as $arkey => $arItem):?>
        <div class="slide">
            <div class="slide__img">
                <img src="" alt="" data-lazy="<?=CFile::GetPath($arItem["DETAIL_PICTURE"]);?>" class="full-image animated" data-animation-in="zoomInImage"/>
            </div>
            <div class="slide__content">
                <div class="slide__content--headings text-center">
                    <h2 class="animated title" data-animation-in="fadeInUp"><?=$arItem["NAME"]?></h2>

                    <?if( $arItem["PREVIEW_TEXT"] != "" ):?>
                        <p class="animated top-title" data-animation-in="fadeInUp" data-delay-in="0.3"><?=$arItem["PREVIEW_TEXT"]?></p>
                    <?endif;?>

                    <?if( $arItem["PROPERTY_BUTTON_NAME_VALUE"] != "" ):?>
                        <a href="<?=$arItem["PROPERTY_LINK_VALUE"]?>" class="btn-light btn button-custom animated" data-animation-in="fadeInUp"><?=$arItem["PROPERTY_BUTTON_NAME_VALUE"]?></a>
                    <?endif;?>
                </div>
            </div>
        </div>
    <?endforeach;?>

    </div>
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="44px" height="44px" id="circle" fill="none" stroke="currentColor">
            <circle r="20" cy="22" cx="22" id="test">
        </symbol>
    </svg>

</section>