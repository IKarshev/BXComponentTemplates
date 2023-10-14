<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>


<pre><?print_r( $arResult )?></pre>

<form id="<?=$arResult["form_id"]?>" class="form_default" method="post" action="" enctype="multipart/form-data">
    <div class="text">
        <div class="title"><?=$arParams["FORM_TITLE"]?></div>
    </div>

    <?foreach ($arResult["PROPS"] as $arkey => $arItem):?>
        <?switch ($arItem["PROPERTY_TYPE"]) {
            case 'STRING'?>
                
                <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                    <label for="<?=$arItem["CODE"]?>" id="<?=$arItem["CODE"]?>"><?=$arItem["NAME"]?></label>
                    <input name="<?=$arItem["CODE"]?>" type="text" placeholder="Введите <?=$arItem["NAME"]?>">
                </div>     
                
                <?break;
            case 'LIST'?>

                <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                    <label for="<?=$arItem["CODE"]?>"><?=$arItem["NAME"]?></label>
                    <select name="<?=$arItem["CODE"]?>" id="<?=$arItem["CODE"]?>">
                        <?foreach ($arItem["LIST_ITEMS"] as $ListKey => $ListItem):?>
                            <option value="<?=$ListItem["XML_ID"]?>"><?=$ListItem["VALUE"]?></option>
                        <?endforeach;?>
                    </select>
                </div>
            
                <?break;
            case 'FILE'?>

                <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                    <span class="title"><?=$arItem["NAME"]?></span>
                    <label for="<?=$arItem["CODE"]?>" <?=($arItem["MULTIPLE"] == "Y")? 'multiple' : ''?> >выберите файл</label>
                    <input type="file" name="<?=$arItem["CODE"]?>" id="<?=$arItem["CODE"]?>">
                </div>     
            
                <?break;
            case 'CHECKBOX'?>

            <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                <span class="title"><?=$arItem["NAME"]?></span>

                <?foreach ($arItem["LIST_ITEMS"] as $checkboxKey => $checkboxItem):?>
                    <div class="checkbox_cont">
                        <input type="checkbox" name="<?=$arItem["CODE"]?>[]" id="<?=$checkboxItem["XML_ID"]?>">
                        <label for="<?=$checkboxItem["XML_ID"]?>"><?=$checkboxItem["VALUE"]?></label>
                    </div>
                <?endforeach;?>
                
            
            </div>     
        
            <?break;
        }?>
    <?endforeach;?>

    <div class="error_placement"></div>

    <button type="submit">Отправить</button>


</form>


<script>
    <?// импорт переменных в js?>
	var form_id = <?=CUtil::PhpToJSObject($arResult["form_id"])?>;
    var propertys = <?=CUtil::PhpToJSObject($arResult["PROPS"])?>;
    var ERROR_MESSAGES = <?=CUtil::PhpToJSObject($arParams["ERROR_MESSAGES"])?>;
</script>