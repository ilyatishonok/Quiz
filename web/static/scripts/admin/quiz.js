$(document).ready(function () {
    $(".question-search").autocomplete({
        source: function (request,response) {
            $.ajax({
                url: "/app_dev.php/api/questions?regular="+$(".question-search").val(),
                success: (data)=>{
                    response(data);
                }
            })
        },
        minLength: 1
    });

});
