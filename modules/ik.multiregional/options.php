<?
use Ik\MultiRegional\Settings;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);
$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
Loader::includeModule($module_id);


$Settings = new Ik\MultiRegional\Settings();
if ( $request->isPost() ){//save settings
    $Settings->save_option( $_POST );
};
$current_options = $Settings->get_option();

$testList =  array('Значение 1', 'Значение 2', 'Значение 3', 'Значение 4');

$aTabs = array(
    array(
        "DIV" => "edit",
        "TAB"=> Loc::getMessage("FALBAR_TOTOP_OPTIONS_TAB_NAME"),
        "TITLE" => Loc::getMessage("FALBAR_TOTOP_OPTIONS_TAB_NAME"),
        "OPTIONS" => array(
            Loc::getMessage("API_SETTINGS"), // Заголовок настроек
            array( // Настройка
                "test_api",
                Loc::getMessage("IS_TEST_MODE"),
                "",
                array("checkbox")
            ),
            array( // Настройка
                "extEntityId",
                Loc::getMessage("EXTENTITYID"),
                "",
                array("text")
            ),
            array( // Настройка
                "infoblock",
                "Выберите инфоблок",
                "",
                array("selectbox", array(
                    "test1" => "test11",
                    "test2" => "test22",
                )),
            ),
            Loc::getMessage("FALBAR_TOTOP_OPTIONS_TAB_APPEARANCE"),
        )
    ),
);


// формируем табы
$aTabs = $Settings->fill_params( $aTabs );
$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);  
$tabControl->Begin();
?>

<form id="IK_BasicModule" action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">

<pre><?print_r( $aTabs );?></pre>

<?
foreach($aTabs as $aTab){

    if($aTab["OPTIONS"]){

        $tabControl->BeginNextTab();

        __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
    }
}

$tabControl->Buttons();
?>

<input type="submit" name="apply_" value="<? echo(Loc::GetMessage("FALBAR_TOTOP_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save" />
<input type="submit" name="default" value="<? echo(Loc::GetMessage("FALBAR_TOTOP_OPTIONS_INPUT_DEFAULT")); ?>" />

<?
echo(bitrix_sessid_post());
?>
</form>
<?$tabControl->End();?>