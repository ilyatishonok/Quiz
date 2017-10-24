$(document).ready(function () {

    let questionName;
    let selectedAnswer;
    let answers = {};

    let questions = [];

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

    $(".content").on("click", ".answer", (event)=>{
      let target = event.target;

      $(target).toggleClass("selected");
      $(selectedAnswer).toggleClass("selected");

      if(selectedAnswer){      
        answers[$(selectedAnswer).html()] = false;
      }

      answers[$(target).html()] = true;

      selectedAnswer = target;

     });

    $(".submit-question").click(()=>{
        let questionName = $(".question-name-input").val();
        let data = {
            questionName: questionName,
            answers: answers
        }


        let jsonData = JSON.stringify(data);

        $.ajax({
            url: "/app_dev.php/admin/api/create/question",
            data: jsonData,
            dataType: "json",
            type: "POST",
            success: (response)=>{
                console.log(response.id);
                questions.push(response.id);
            } 
        });
    });

    $(".button").click(()=>{
        let quizName = $(".quiz-name-input").val();
        let data = {
            quizName: quizName,
            questions: questions
        }

        let jsonData = JSON.stringify(data);

        $.ajax({
            url: "/app_dev.php/admin/api/create/quiz",
            data: jsonData,
            dataType: "json",
            type: "POST",
            success: (response)=>{
                console.log(response);
            }

        });
    });

    $(".answer-name-input").keydown((event)=>{
        if(event.keyCode === 13){
            let answer = $(".answer-name-input").val();
            if(answer){
                  $(".answers").append("<div class='answer'>" + answer + "</div>");
                  answers[answer] = false;
            } 
        }
  });

    $(".add-question").click(()=>{
        
    })

});
