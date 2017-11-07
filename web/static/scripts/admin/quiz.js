$(document).ready(function () {

    let questionName;
    let selectedAnswer;
    let answers = {};
    let error = false;
    let questionIds = [];

    $(".question-creator-wrapper").questionView({
        success: (response)=>{
            console.log(response);
            questionIds.push(response.id);
            console.log(questionIds);
            renderQuestion(response.name,response.id,response.answers);
        }
    });

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
            $.ajax({
                url: "/app_dev.php/admin/api/question?questionName=" + ui.item.value,
                success: (response)=>{
                    if(questionIds.indexOf(response.id) !== -1){
                        $(".errors").html("This question is already exist!");
                        return;
                    }
                    questionIds.push(response.id);
                    console.log(response.answers);
                    renderQuestion(response.name,response.id,response.answers);
                }
            })
        },
        minLength: 1
    });

    $(".add-question-button").click(()=>{
        
    });


    $(".create-quiz-btn").click((event)=>{
        let quizName = $(".quiz-name-input").val();
        let csrfToken = $("#csrf-input").val();
        let data = {
            quizName:quizName,
            questionsId: questionIds,
            csrfToken: csrfToken,
        }
        let jsonData = JSON.stringify(data);
        $.ajax({
            url: "/app_dev.php/admin/api/create/quiz",
            dataType: "json",
            type: "POST",
            data: jsonData,
            success: (response)=>{
                console.log(response);
            },
            error: (response)=>{
                console.log(response);
            }
        })

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
