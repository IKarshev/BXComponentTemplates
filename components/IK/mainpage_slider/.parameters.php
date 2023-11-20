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
    ),
);
?>