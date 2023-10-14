<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

$arResult = array();
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "DETAIL_TEXT");

foreach ($arParams["PROPERTYS"] as $arkey => $arItem) {
    $arSelect[] = "PROPERTY_".$arItem;  
};

$arFilter = Array("IBLOCK_ID"=>$arParams["iblock"], "ID" => $arParams["ELEMENT_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();

    // $arResult = array(
    //     "ID" => $arFields["ID"],
    //     "NAME" => $arFields["NAME"],
    //     "PREVIEW_TEXT" => $arFields["PREVIEW_TEXT"],
    //     "DETAIL_TEXT" => $arFields["DETAIL_TEXT"],
    // );
    $arResult = $arFields;
};

$this->IncludeComponentTemplate();
?>