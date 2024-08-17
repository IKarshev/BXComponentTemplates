# Шаблон постраничной навигации для ajax подгрузки

## Установка
1. В `result_modifier.php` прописать:
```
if(array_key_exists("IS_AJAX", $_REQUEST) && $_REQUEST["IS_AJAX"] == "Y") $APPLICATION->RestartBuffer();
```
2. В `component_epilog.php` прописать:
```
if(array_key_exists("IS_AJAX", $_REQUEST) && $_REQUEST["IS_AJAX"] == "Y") die();
```
3. Выбрать в компоненте шаблон постраничной навигации