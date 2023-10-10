<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

// Тип инфоблока
$iblockTypes = [];
$result = \Bitrix\Iblock\TypeTable::getList( [
    'select' => [
        'ID',
        'NAME' => 'LANG_MESSAGE.NAME',
    ],
    'filter' => ['=LANG_MESSAGE.LANGUAGE_ID' => 'ru'],
] );
while ($row = $result->fetch()) {
    $iblockTypes[$row['ID']] = $row['NAME'];
}

// инфоблок
$iblocks = [];
$result = \Bitrix\Iblock\IblockTable::getList( [
    'select' => ['ID', 'NAME'],
    'filter' => ['IBLOCK_TYPE_ID' => $arCurrentValues['iblockType']],
] );
while ($row = $result->fetch()) {
    $iblocks[$row['ID']] = $row['NAME'];
}

// Элемент инфоблока
$elements = array();
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID"=>$arCurrentValues["iblock"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
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
        "iblockType" => array(
            "PARENT" => "BASE",
            "NAME" => "Тип инфоблока",
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $iblockTypes,
            "REFRESH" => "Y",
        ),
        "iblock" =>  array(
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
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $elements,
            "REFRESH" => "Y",
        ),
    ),
);
?>