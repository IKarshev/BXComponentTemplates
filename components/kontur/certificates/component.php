<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

$arResult = array();
$arSelect = Array("ID", "NAME", "PROPERTY_PHOTO",);
$arFilter = Array("IBLOCK_ID"=>$arParams["iblock"], "ID" => $arParams["ELEMENT_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();

    $arResult[] = CFile::GetPath( $arFields["PROPERTY_PHOTO_VALUE"] );
};

$this->IncludeComponentTemplate();
?>