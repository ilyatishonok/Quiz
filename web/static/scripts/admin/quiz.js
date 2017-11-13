$(document).ready(function () {

    let questionIds = [];

    $(".question-creator-wrapper").questionView({
        success: (response)=>{
            questionIds.push(response.id);
            console.log(questionIds);
            renderQuestion(response.questionName,response.id,response.answers);
            $(".add-question-button").show();
            $(".question-creator-wrapper").fadeOut();
            clearForm();
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
                        $(".question-errors").html("This question is already exist!");
                        return;
                    }
                    questionIds.push(response.id);
                    renderQuestion(response.name,response.id,response.answers);
                }
            })
        },
        minLength: 1
    });

    $(".add-question-button").click(()=>{
        $(".question-creator-wrapper").fadeIn();
        $(".add-question-button").hide();
    });

    $(".questions").on("click", ".delete-question", (event)=>{
        let target = event.target;
        console.log(questionIds);
        let questionId = $(target).parent().attr("data-id");
        let questionIndex = questionIds.indexOf(parseInt(questionId));

        if(questionIndex !== -1) {
            questionIds.splice(questionIndex,1);
            console.log(questionIds);
            $(target).parent().remove();
        } else {
            console.error("Questions doesn't have question with this id!");
        }
    });

    $(".create-quiz-btn").click((event)=>{
        if(checkForm()){
            let quizName = $(".quiz-name-input").val();
            let csrfToken = $("#csrf-input").val();
            let data = {
                quizName:quizName,
                questionsId: questionIds,
                csrfToken: csrfToken,
            }
            let jsonData = JSON.stringify(data);
            $.ajax({
                url: Routing.generate("_create_quiz"),
                dataType: "json",
                type: "POST",
                data: jsonData,
                success: (response)=>{
                    console.log(response);
                },
                error: (response)=>{
                    showError(Translator.trans(response.responseText));
                }
            })
        }

    });

    function checkForm(){
        $(".quiz-errors").html("");
        $(".questions-errors").html("");
        let correct = true;


        let quizName = $(".quiz-name-input").val();

        if(!quizName){
            $(".quiz-errors").html("You have to write quiz name!");
            correct = false;
        }

        if(!questionIds.length) {
            $(".questions-errors").html("You have to add some question!");
            correct = false;
        }

        return correct;
    }

    function clearForm(){
        $(".question-name").val("");
        $("#question_answers").html("");
    }

    function renderQuestion(questionName, questionId ,answers){
        let html = "<li data-id=\'" + questionId + "\' class='question' draggable='true'><div class='question-header'>"
                        + questionName + "</div><i class='fa fa-trash-o delete-question' aria-hidden='true'></i><ul class='question-answers'>";
                    
        for(let key in answers){
            if(answers[key] === true){
                html += "<li class='question-answer selected'>" + key + "</li>";
            } else {
                html += "<li class='question-answer unselected'>" + key + "</li>";
            }
        }

        html += "</div></li>";

        $(".questions").append(html);
    }

    function showError(error){
      $(".quiz-error-text").html(error);
      $(".alert-error").show();
      $('html, body').animate({
        scrollTop: 0
      }, 1000);
      setTimeout(()=>{
        $(".alert-error").fadeOut();
      },5000);
    }

    
});
