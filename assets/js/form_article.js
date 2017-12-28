$( "#form_serie option:selected" ).each(function () {
    if($( this ).val() != ""){
        $("input[type=number]").parent().show();
    }
    else {
        $("input[type=number]").parent().hide();
    }
});


$( "#form_serie" ).change(function() {
    $( "#form_serie option:selected" ).each(function () {
        if($( this ).val() != ""){
            $("input[type=number]").parent().show();
        }
        else {
            $("input[type=number]").parent().hide();
        }
    })
});