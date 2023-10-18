<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\SectionTable;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\PropertyTable;
Loader::includeModule('iblock');

// Тип инфоблока
$infoblock_type = \Bitrix\Iblock\TypeTable::getList( [
    'select' => [
        'ID',
        'NAME' => 'LANG_MESSAGE.NAME',
    ],
    'filter' => ['=LANG_MESSAGE.LANGUAGE_ID' => 'ru'],
] );
while ($row = $infoblock_type->fetch()) {
    $iblockTypes[$row['ID']] = $row['NAME'];
}

// Инфоблок
$infoblock = \Bitrix\Iblock\IblockTable::getList( [
    'select' => ['ID', 'NAME'],
    'filter' => ['IBLOCK_TYPE_ID' => $arCurrentValues['IBLOCKTYPE']],
] );
while ($row = $infoblock->fetch()) {
    $iblocks[$row['ID']] = $row['NAME'];
}

$props = array();
$rsProperty = \Bitrix\Iblock\PropertyTable::getList(array(
    'filter' => array('IBLOCK_ID'=>$arCurrentValues['IBLOCK'],'ACTIVE'=>'Y'),
));
while($arProperty=$rsProperty->fetch()){
    $props[ $arProperty['CODE'] ] = "[".$arProperty['ID']."] ".$arProperty["NAME"];
};


$arComponentParameters = array(
    "GROUPS" => array(
        "BASE" => array(
            "NAME" => "основные настройки",
        ),
        "PROPS" => array(
            "NAME" => "Свойства",
        ),
        "SAVE_SETTINGS" => array(
            "NAME" => "Настройки отправки",
        ),
    ),
    "PARAMETERS" => array(
        "IBLOCKTYPE" => array(
            "PARENT" => "BASE",
            "NAME" => "Тип инфоблока",
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $iblockTypes,
            "REFRESH" => "Y",
        ),
        "IBLOCK" =>  array(
            "PARENT" =>  "BASE",
            "NAME" =>  "id инфоблока",
            "TYPE" =>  "LIST",
            "VALUES" =>  $iblocks,
            "REFRESH" =>  "Y",
        ),
        "FORM_TITLE" => array(
            "PARENT" => "BASE",
            "NAME" => "Название формы",
            "TYPE" => "STRING",
        ),
        "PROPERTYS" => array(
            "NAME" => "Свойства",
            "TYPE" => "LIST",
            "PARENT" => "PROPS",
            "MULTIPLE" => "Y",
            "VALUES" => $props,
            "REFRESH" => "Y",
        ),
        "PHONE_MASK" => array(
            "NAME" => "Свойства с маской телефона",
            "TYPE" => "LIST",
            "PARENT" => "PROPS",
            "MULTIPLE" => "Y",
            "VALUES" => $props,
            "REFRESH" => "Y",
        ),
        "EMAIL_MASK" => array(
            "NAME" => "Свойства с маской почты",
            "TYPE" => "LIST",
            "PARENT" => "PROPS",
            "MULTIPLE" => "Y",
            "VALUES" => $props,
            "REFRESH" => "Y",
        ),
        "ADD_FORM" => array(
            "PARENT" => "SAVE_SETTINGS",
            "NAME" => "Добавлять результат в инфоблок?",
            "TYPE" => "CHECKBOX",
        ),
        "SEND_MAIL" => array(
            "PARENT" => "SAVE_SETTINGS",
            "NAME" => "Отправлять письмо?",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y",
        ),
        "POPUP" => array(
            "PARENT" => "BASE",
            "NAME" => "Выводить форму в pop-up`е ?",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y",
        ),
    ),
);

if ( $arCurrentValues["SEND_MAIL"] == "Y" ){
    $arComponentParameters['PARAMETERS']['MAIL_TEMPLATE'] = array(
        "NAME" => "ID почтового шаблона",
        "PARENT" => "SAVE_SETTINGS",
        "TYPE" => "STRING",
     );
};

if ( $arCurrentValues["POPUP"] == "Y" ){
    $arComponentParameters['PARAMETERS']['POPUP_BTN_TITLE'] = array(
        "NAME" => "Название кнопки для открытия pop-up",
        "PARENT" => "BASE",
        "TYPE" => "STRING",
    );
};
?>