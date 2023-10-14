BX.ready(function(){
    if(typeof form_id !== "undefined" && typeof propertys !== "undefined" ){
        var ValidateSettings = {
            rules: {},
            messages : {},
            errorElement : "div",
            errorPlacement : function(error, element) {
                $(`#${form_id} .error_placement`).append(error);
            },
            submitHandler : function(form, event){
                event.preventDefault();    
            
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
                "required": (item.PROPERTY_TYPE == "FILE") ? `Необходимо загрузить файл в поле ${item.NAME}` :`Необходимо заполнить поле ${item.NAME}`,
            };
        });
        
        console.log( ValidateSettings );

        $(`#${form_id}`).validate(ValidateSettings);
        

        // $(`${form_id} .phone-mask`).inputmask("mask", {"mask": "+7 (999) 999-99 99"});
    
    };
});