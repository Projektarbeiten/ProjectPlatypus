$(".login-button").click(function (){
    var formValuesText = {};
    var formValuesPasswords = {};
    var formValuesSelect = {};

    $('register-form input[type="text"]').each(function() {
        var inputName = $(this).attr('name');
        var inputValue = $(this).val();
        if(//Checking for Empty Required Fields)
        formValuesText[inputName] = inputValue;
      });

    $('register-form input[type="password"]').each(function() {
    var inputName = $(this).attr('name');
    var inputValue = $(this).val();
    formValuesPasswords[inputName] = inputValue;
    });

    $('register-form select').each(function() {
        var selectName = $(this).attr('name');
        var selectValue = $(this).val();
        formValuesSelect[selectName] = selectValue;
        });
    
      // Das Formular absenden
      $('register-form').submit(function(event) {
        event.preventDefault();
    
        // Hier kannst du deine AJAX-Anfrage durchführen oder das Formular normal absenden
    
        // Nach dem Absenden die gespeicherten Werte wieder einfügen
        $('register-form input[type="text"]').each(function() {
          var inputName = $(this).attr('name');
          if (formValuesText.hasOwnProperty(inputName)) {
            $(this).val(formValuesText[inputName]);
          }
        });
        $('register-form input[type="password"]').each(function() {
            var inputName = $(this).attr('name');
            if (formValuesPasswords.hasOwnProperty(inputName)) {
              $(this).val(formValuesPasswords[inputName]);
            }
          });
          $('register-form select').each(function() {
            var selectName = $(this).attr('name');
            if (formValuesSelect.hasOwnProperty(selectName)) {
              $(this).val(formValuesSelect[selectName]);
            }
          });
      });
});