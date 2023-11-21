$(function(){
    /**
     * Библиотека select2 позволяет добавить поиск по <options> в <select>
     * Библиотека может начать виснуть если много элементов в одном из select
     * Скрипт реализовывает подгрузку по мере скрола
     */
    function initOptimizedSelect2(selectId, dataSelect){
        var pageSize = 20;
        $.fn.select2.amd.require(["select2/data/array", "select2/utils"], function (ArrayData, Utils) {					
            function CustomData($element, options) {
                CustomData.__super__.constructor.call(this, $element, options);
            }
            
            Utils.Extend(CustomData, ArrayData);
            CustomData.prototype.query = function (params, callback) {
                if (!("page" in params)) {
                    params.page = 1;
                }
                var data = {};
                if (params.term != undefined && params.term != '') {
                    let subData = $.grep(dataSelect, function (n, i) {
                        if (n.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                            return n;
                        }
                    });
                    data.results = subData.slice((params.page - 1) * pageSize, params.page * pageSize);
                }
                else {
                    data.results = dataSelect.slice((params.page - 1) * pageSize, params.page * pageSize);
                }
                data.pagination = {};
                data.pagination.more = params.page * pageSize < dataSelect.length;
                callback(data);
            };
            
            $(`${selectId}`).select2({
                ajax: {},
                dataAdapter: CustomData,
                width: '100%'
            });
        });
    };

    /**
     * Проходимся по <select class="select2-optimization"></select>
     * И по их options. Отправляем данные в функцию, которая реализовывает подгрузку
     */
    $('.select2-optimization').each(function(){
        var items = [];
        var select_id = `#${$(this).attr("id")}`;
        $(this).find("option").each(function(){
            items.push({id: $(this).val(), text: $(this).html()});
        });
        initOptimizedSelect2(select_id, items);
    });
});