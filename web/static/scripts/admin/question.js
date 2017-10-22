$(document).ready(function() {

    let questionName;
    let selectedAnswer;
    let answers = {};

  $(".answer-name-input").keydown((event)=>{
        if(event.keyCode === 13){
            let answer = $(".answer-name-input").val();
            if(answer){
                  $(".answers").append("<div class='answer'>" + answer + "</div>");
                  answers[answer] = false;
            } 
        }
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
        if(response.success){
          console.log(true);
        }
        else{
          console.log(response);
          console.log(false);
        }
      }
    });
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

});