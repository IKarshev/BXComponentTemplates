<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>

<section class="company">
    <div class="container">
        <div class="title_h2">
            <h2><?=$arResult["NAME"]?></h2>
            <a href="<?=$arResult["LINK"]?>" class="link">Подробнее</a>
        </div>
        <div class="company__box">
            <div class="company__left">
                <h5><?=$arResult["PREVIEW_TEXT"]?></h5>
                <p><?=$arResult["DETAIL_TEXT"]?></p>
            </div>
            <div class="company__img">
                <img src="<?=$arResult["PHOTO_1"]?>" alt="">
            </div>
            <div class="company__right">
                <img src="<?=$arResult["PHOTO_2"]?>" alt="">
                <?if( isset($arResult["VENDOR_CODE"]) && $arResult["VENDOR_CODE"] != "" ):?>
                    <span>art <?=$arResult["VENDOR_CODE"]?></span>
                <?endif;?>
            </div>
        </div>
    </div>
</section>