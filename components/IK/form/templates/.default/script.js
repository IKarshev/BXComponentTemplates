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
                // $(`#${form_id} .error_placement`).append(error);
                $(element).closest(".input_cont").find(".error_placement").append(error);
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
            var code = (item.PROPERTY_TYPE != "CHECKBOX") ? item.CODE : `${item.CODE}[]`;

            ValidateSettings["rules"][`${code}`] = {
                "required": (item.IS_REQUIRED == "Y") ? true : false,
                "email" : (item.MASK == "EMAIL") ? true : false,
            };
            ValidateSettings["messages"][`${code}`] = {
                "required": `${ERROR_MESSAGES[item.PROPERTY_TYPE]} ${item.NAME}`,
                "email": `${ERROR_MESSAGES["EMAIL_VALIDATE"]} ${item.NAME}`,
            };
        });
        
        // Установка масок
        $(`#${form_id} input[data-mask=PHONE]`).inputmask("mask", {"mask": "+7 (999) 999-99 99"});
    


        $(`#${form_id}`).validate(ValidateSettings);

    };
});