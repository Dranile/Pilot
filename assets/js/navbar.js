var $ = require('jquery');

const search = document.getElementById("search");
search.addEventListener('keydown', function(event) {
    if (event.key === "Enter") {
        window.location = "/serie/" + encodeURIComponent(search.value);
        event.preventDefault();
        // Do more work
    }
});

const search_btn = document.getElementById('search-btn');
search_btn.addEventListener('click', function (event) {
    window.location = "/serie/" + encodeURIComponent(search.value);
    event.preventDefault();
});

$(function(){
    $('#search').autocomplete({
        source : function(requete, response){
            $.ajax({
                type:"POST",
                url : "/search/" + $("#search").val() ,
                success : function(data){
                    var result = new Array();
                    var array = JSON.parse(data);
                    for (var i = 0; i < array.length; ++i) {
                        result.push(array[i].name);
                    }
                    response(result);
                },
                error: function(xhr, status, error) {

                }
            });
        }
    });
});

/*$(document).ajaxError(
    function (event, jqXHR, ajaxSettings, thrownError) {
        alert('[event:' + event + '], [jqXHR:' + jqXHR + '], [ajaxSettings:' + ajaxSettings + '], [thrownError:' + thrownError + '])');
    });*/