BX.ready(function(){
    if(typeof form_id !== "undefined" && typeof propertys !== "undefined" ){
        /**
         * Валидация формы
         */
        var ValidateSettings = {
            rules: {},
            messages : {},
            errorElement : "div",
            errorPlacement : function(error, element) {
                $(`#${form_id} .error_placement`).append(error);
            },
            submitHandler : function(form, event){
                event.preventDefault();    
            
                /**
                 * Отправка ajax запроса в компонент
                 */
                var request = BX.ajax.runComponentAction('IK:form', 'Send_Form', {
                    mode: 'class',
                    data: new FormData( document.getElementById(`${form_id}`) ),
                }).then(function(response){
    
                    console.log("Отправлено");
                    
                });
            },

        };
        propertys.forEach(function(item, i, arr) {
            ValidateSettings["rules"][`${item.CODE}`] = {
                "required": (item.IS_REQUIRED == "Y") ? true : false,
            };
            ValidateSettings["messages"][`${item.CODE}`] = {
                "required": `${ERROR_MESSAGES[item.PROPERTY_TYPE]} ${item.NAME}`,
            };
        });
        
        // Установка масок
        // $(`${form_id} .phone-mask`).inputmask("mask", {"mask": "+7 (999) 999-99 99"});
    


        $(`#${form_id}`).validate(ValidateSettings);

    };
});