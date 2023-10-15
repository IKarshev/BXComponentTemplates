<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
session_start();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

use \Bitrix\Main\Application;
use \Bitrix\Iblock\SectionTable;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\PropertyTable;
session_start();

Loader::includeModule('iblock');

class FormComponent extends CBitrixComponent implements Controllerable{

    public function randomString($length = 8) { 
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $charactersLength = strlen($characters); 
        $randomString = ''; 
        for ($i = 0; $i < $length; $i++) { 
            $randomString .= $characters[rand(0, $charactersLength - 1)]; 
        } 
        return $randomString; 
    }

    public function generate_form_id($form_prefix) {
        $this->form_postfix = $this->randomString();
        $this->form_id = $form_prefix."_".$this->form_postfix;
        
        return $this->form_id;
    }

    public function validate_string($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function configureActions(){
        // сбрасываем фильтры по-умолчанию
        return [
            'Send_Form' => [
                'prefilters' => [],
                'postfilters' => []
            ]
        ];
    }

    public function executeComponent(){// подключение модулей (метод подключается автоматически)
        try{
            // Проверка подключения модулей
            $this->checkModules();
            // Генерируем название формы
            $this->form_id = $this->generate_form_id("Order_call");
            // формируем arResult
            $this->getResult($this->form_id);
            // подключение шаблона компонента
            $this->includeComponentTemplate();
        }
        catch (SystemException $e){
            ShowError($e->getMessage());
        }
    }

    protected function checkModules(){// если модуль не подключен выводим сообщение в catch (метод подключается внутри класса try...catch)
        if (!Loader::includeModule('iblock')){
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        }
    }


    public function onPrepareComponentParams($arParams){//обработка $arParams (метод подключается автоматически)
        $arParams["ERROR_MESSAGES"] = array(
            "FILE" => Loc::getMessage('ERROR_FILE'),
            "STRING" => Loc::getMessage('ERROR_STRING'),
            "CHECKBOX" => Loc::getMessage('ERROR_CHECKBOX'),
            "LIST" => Loc::getMessage('ERROR_LIST'),
            "EMAIL_VALIDATE" => Loc::getMessage('EMAIL_VALIDATE'),
        );
        
        return $arParams;
    }

    protected function getResult($form_id){ // подготовка массива $arResult (метод подключается внутри класса try...catch)
        // Формируем массив arResult
        $this->arResult["form_id"] = $this->form_id;
        // Передаем параметры в сессию, чтобы получить иметь доступ в ajax
        $_SESSION['arParams'] = $this->arParams;
  
        $PropTypeList = array(
            "S" => "STRING",
            "L" => "LIST",
            "F" => "FILE",
            "C" => "CHECKBOX",
        );

        // Формируем массив с масками
        $MASK = array();
        foreach ($this->arParams["PHONE_MASK"] as $arkey => $arItem) {
            $MASK[ $arItem ] = "PHONE";
        };
        foreach ($this->arParams["EMAIL_MASK"] as $arkey => $arItem) {
            $MASK[ $arItem ] = "EMAIL";
        };

        $this->arResult['MASK'] = $MASK;

        // получаем параметры
        $rsProperty = \Bitrix\Iblock\PropertyTable::getList(array(
            'filter' => array('IBLOCK_ID'=>$this->arParams['IBLOCK'],'ACTIVE'=>'Y'),
        ));
        while($arProperty=$rsProperty->fetch()){
            // $this->arResult["PROPS"][] = $arProperty;

            $PropertyArr = array(
                "ID" => $arProperty["ID"],
                "NAME" => $arProperty["NAME"],
                "CODE" => $arProperty["CODE"],
                "MULTIPLE" => $arProperty["MULTIPLE"],
                "PROPERTY_TYPE" => $PropTypeList[ $arProperty["PROPERTY_TYPE"] ],
                "IS_REQUIRED" => $arProperty["IS_REQUIRED"],
                "MASK" => (array_key_exists($arProperty["CODE"], $MASK)) ? $MASK[$arProperty["CODE"]] : "",
            );
            
            $listItem = array();
            if( $arProperty["PROPERTY_TYPE"] == 'L' ){
                $rsEnum = \Bitrix\Iblock\PropertyEnumerationTable::getList(array(
                    'filter' => array('PROPERTY_ID'=>$arProperty["ID"]),
                ));
                while($arEnum=$rsEnum->fetch()){
                    $listItem[] = $arEnum;
                }
                
                $PropertyArr["LIST_ITEMS"] = $listItem;
            };

            if( $PropertyArr["PROPERTY_TYPE"] == "LIST" && $arProperty["LIST_TYPE"] == "C" ){
                $PropertyArr["PROPERTY_TYPE"] = $PropTypeList[ $arProperty["LIST_TYPE"] ];
            };
        
            
            $this->arResult["PROPS"][] = $PropertyArr;
        };


        return $this->arResult;
    }

    public function Send_FormAction(){
        $request = Application::getInstance()->getContext()->getRequest();
        // получаем файлы, post
        $post = $request->getPostList();
        $files = $request->getFileList()->toArray();
        // Получаем параметры компонента из сессии
        $this->arParams = $_SESSION['arParams'];

        
        $PROP = array();
        // Валидация текстовых полей
        foreach ($post as $arkey => $arItem) {
            if( $arItem != "" ){// Проверяем пустые массивы
                if( is_array($arItem) ){
                    foreach ($arItem as $key => $value) {
                        $PROP[$arkey][] = $this->validate_string( $value );
                    };
                } else{// Валидация ключей массивов (списки)
                    $PROP[$arkey] = $this->validate_string( $arItem );
                };
            };
        };

        /**
         * Формируем массивы с id файлов
         */
        foreach ($files as $InputKey => $InputItem) {// inputs
            foreach ($InputItem['tmp_name'] as $arkey => $arItem) {
                $FileData = array(
                    "name" => $InputItem['name'][$arkey],
                    "size" => $InputItem['size'][$arkey],
                    "tmp_name" => $InputItem['tmp_name'][$arkey],
                    "type" => $InputItem['type'][$arkey],
                );

                $FileID = CFile::SaveFile( $FileData, "iblock" );
                $PROP[$InputKey][] = $FileID;
                $UploadFiles[] = $FileID;
            };
        };

        // Добавление в инфоблок
        if( $this->arParams['ADD_FORM'] == 'Y' ){
            $el = new CIBlockElement;
            $arLoadProductArray = Array(
                "MODIFIED_BY" => "1",
                "IBLOCK_SECTION_ID" => false,
                "IBLOCK_ID" => $this->arParams["IBLOCK"],
                "PROPERTY_VALUES"=> $PROP,
                "NAME" => "Данные от ".date('d.m.Y'),
                "ACTIVE" => "Y",
            );
            $PRODUCT_ID = $el->Add($arLoadProductArray);
        };

        // Почтовое событие
        if( $this->arParams['SEND_MAIL'] == 'Y' && (isset($this->arParams['MAIL_TEMPLATE']) && $this->arParams['MAIL_TEMPLATE'] != "") ){
            CEvent::Send( $this->arParams['MAIL_TEMPLATE'] , SITE_ID, $PROP, "N", $UploadFiles );
        };
        
    } 

}