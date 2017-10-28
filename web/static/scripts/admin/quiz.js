$(document).ready(function () {

    let questionName;
    let selectedAnswer;
    let answers = {};

    let questionIds = [];

    $(".questions").sortable().bind('sortupdate',function(){
        let questions = $(".question").map(function(){
            return $(this).data("id");
        }).get();
        
        let newQuestionIds = [];
        let isCorrect = true;

        questions.forEach((element,index,array)=>{
            if(questionIds.indexOf(element) !== -1){
                newQuestionIds.push(element);
            } else {
                isCorrect = false;
            }
        });

        if(isCorrect === true){
            questionIds = newQuestionIds;
        } else {
            console.error("Incorrect data-id!");
        }
    });

    $(".question-search").autocomplete({
        source: function (request,response) {
            $.ajax({
                url: "/app_dev.php/api/questions?regular="+$(".question-search").val(),
                success: (data)=>{
                    response(data);
                }
            })
        },
        select: (event, ui)=>{
            console.log(ui.item.value);
            $.ajax({
                url: "/app_dev.php/admin/api/question?questionName=" + ui.item.value,
                success: (response)=>{
                    if(questionIds.indexOf(response.id) !== -1){
                        $(".errors").html("This is already exist!");
                        return;
                    }
                    questionIds.push(response.id);
                    
                    renderQuestion(response.name,response.id,response.answers);
                }
            })
        },
        minLength: 1
    });


    function renderQuestion(questionName, questionId ,answers){
        let html = "<li data-id=\'" + questionId + "\' class='question' draggable='true'><div class='question-header'>"
                        + questionName + "</div><ul class='question-answers'>";
                    
        for(let key in  answers){
            if(answers[key] === true){
                html += "<li class='question-answer selected'>" + key + "</li>";
            } else {
                html += "<li class='question-answer unselected'>" + key + "</li>";
            }
        }

        html += "</div></li>";

        $(".questions").append(html);
    }

    
});
