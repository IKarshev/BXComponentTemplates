<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

$arResult = array();
$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_TEXT", "DETAIL_TEXT", "PROPERTY_PHOTO_1", "PROPERTY_PHOTO_2", "PROPERTY_LINK", "PROPERTY_VENDOR_CODE");
$arFilter = Array("IBLOCK_ID"=>$arParams["iblock"], "ID" => $arParams["ELEMENT_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();

    $arResult = array(
        "ID" => $arFields["ID"],
        "NAME" => $arFields["NAME"],
        "PREVIEW_TEXT" => $arFields["PREVIEW_TEXT"],
        "DETAIL_TEXT" => $arFields["DETAIL_TEXT"],
        "PHOTO_1" => CFile::GetPath( $arFields["PROPERTY_PHOTO_1_VALUE"] ),
        "PHOTO_2" => CFile::GetPath( $arFields["PROPERTY_PHOTO_2_VALUE"] ),
        "LINK" => $arFields["PROPERTY_LINK_VALUE"],
        "VENDOR_CODE" => $arFields["PROPERTY_VENDOR_CODE_VALUE"],
    );
};

$this->IncludeComponentTemplate();
?>