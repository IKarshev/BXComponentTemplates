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

class MainPageSlider extends CBitrixComponent implements Controllerable{

    public function configureActions(){
        // сбрасываем фильтры по-умолчанию
        return [

        ];
    }

    public function executeComponent(){// подключение модулей (метод подключается автоматически)
        try{
            // Проверка подключения модулей
            $this->checkModules();
            // Генерируем название формы
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
        return $arParams;
    }

    protected function getResult(){ // подготовка массива $arResult (метод подключается внутри класса try...catch)
        $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PREVIEW_TEXT", "DETAIL_TEXT", "PROPERTY_LINK", "PROPERTY_BUTTON_NAME");
        $arFilter = Array("IBLOCK_ID"=>$this->arParams["IBLOCK"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement()){
            $arFields = $ob->GetFields();
            $this->arResult["ITEMS"][] = $arFields;
        };


        return $this->arResult;
    }

}