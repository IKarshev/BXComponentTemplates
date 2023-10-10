<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\SectionTable;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\PropertyTable;
use \Bitrix\Iblock\IblockTable;
Loader::includeModule('iblock');


// Тип инфоблока
$iblockTypes = [];
$result = \Bitrix\Iblock\TypeTable::getList([
    'select' => [
        'ID',
        'NAME' => 'LANG_MESSAGE.NAME',
    ],
    'filter' => ['=LANG_MESSAGE.LANGUAGE_ID' => 'ru'],
]);
while ($row = $result->fetch()) {
    $iblockTypes[$row['ID']] = $row['NAME'];
};

// инфоблок
$iblocks = array();
$result = IblockTable::getList([
    'select' => ['ID', 'NAME'],
    'filter' => ['IBLOCK_TYPE_ID' => $arCurrentValues['IBLOCKTYPE']],
]);
while ($row = $result->fetch()) {
    $iblocks[$row['ID']] = $row['NAME'];
};

// Элемент инфоблока
$elements = [];
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    $elements[ $arFields["ID"] ] = $arFields["NAME"];
}

$arComponentParameters = array(
    "GROUPS" => array(
        "BASE" => array(
            "NAME" => "основные настройки",
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
        "ELEMENT_ID" => array(
            "NAME" => "ID галереи",
            "TYPE" => "LIST",
            "PARENT" => "BASE",
            "VALUES" => $elements,
            "REFRESH" => "Y",
        ),
    ),
);
?>