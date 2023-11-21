<!-- css -->
<? $APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH . "/css/jquery.fancybox.min.css");?>
<? $APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH . "/css/slick-theme.css");?>
<? $APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH . "/css/slick.css");?>
<? $APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH . "/css/slick-animation.min.js");?>
<? $APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH . "/css/select2.min.css");?>

<!-- js -->
<?CJSCore::Init(array("jquery"));?>
<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/slick.js");?>
<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/jquery.fancybox.min.js");?>
<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/jquery.validate.min.js");?>
<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/jquery.inputmask.min.js");?>
<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/select2.min.js");?>

<!-- IK js helper -->
<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/IK_js_helper.js");?>