$(document).ready(function() {

    let questionName;
    let selectedAnswer;
    let answers = {};
    let error = false;

  $(".answer-name-input").keydown((event)=>{
        if(event.keyCode === 13){
            let answer = $(".answer-name-input").val();
            if(answer){
              if(!answers.hasOwnProperty(answer.trim())){
                  $(".answers").append("<li class='answer'>" + answer + "<i class='fa fa-trash-o icon' aria-hidden='true'></i></li>");
                  answers[answer] = false;
                  $(".answer-name-input").css("display","none");
                  $(".answer-name-input").val("");
                  if(!answers.length)
                  {
                    $(".answer-info").html("");
                  }
                  if(error === true){
                    $(".errors").html();
                  }
              }
              else{
                error = true;
                $(".errors").html(Translator.trans("errors.answer_duplicate"));
              }
            } 
        }
  });

  $(".add-answer").click(()=>{
    $(".answer-name-input").css("display","block");
  });

  $(".submit-question").click(()=>{
    if(checkQuestionForm()){
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

        },
        error: (response)=>{
          $(".errors").html(response.responseText);
        } 
      });
    }
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

  function checkQuestionForm(){
    if(!$(".question-name-input").val()){
      $(".errors").html(Translator.trans("errors.question_name"));
      return false;
    }
    else if(!selectedAnswer){
        error = true;
        $(".errors").html(Translator.trans("errors.select_answer"));
        return false;
    }
    return true;
  }

})