<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>

<div class="companyPage__slider">
    <?foreach ($arResult as $arkey => $arItem):?>
        <div class="wrap">
            <div class="block">
                
                <a href="<?=$arItem?>" data-fancybox="gallery_1" class="gallery_element">
                    <img src="<?=$arItem?>" alt="">
                </a>

            </div>
        </div>
    <?endforeach;?>

</div>
