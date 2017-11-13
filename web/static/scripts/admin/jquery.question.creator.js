(function($){

  $.fn.questionView = function(options) {

    let answerAdding = false;
    let selectedAnswer;

    var collectionHolder = $("#question_answers");
    collectionHolder.data('index', collectionHolder.find(':input').length);

    $(".add-answer-btn").on("click", function(e){
      e.preventDefault();
      if(answerAdding)
      {
          showError(Translator.trans("errors.adding_answer"));
          return;
      }
      addAnswerForm();
      answerAdding = true;
    });

    $(".answers").on('click', '.delete', (event)=>{
        let target = event.target;

        let answer = $(target).closest(".answer-tools").siblings(".answer")[0];
        if($(selectedAnswer).children(".answer")[0] == answer){
          selectedAnswer = null;
          console.log("blath");
        }

        $(target).closest(".answer-wrapper").remove();
    });

    $(".answers").on("click", ".edit", (event)=>{
        let target = event.target;

        let answer =  $(target).closest(".answer-tools").siblings(".answer")[0];

        $(answer).attr("readonly", false);

        $(answer).toggleClass("wrapped");

    });


    $(".answers").on("click", ".answer-wrapper", (event)=>{
      event.preventDefault();
      let target = event.target;
      if ($(target).attr("data-class") === "non-wrapped") {
        return;
      }
      let answerWrapper = $(target).closest(".answer-wrapper");
      if($(answerWrapper).find(".answer").hasClass("wrapped")){
        $(answerWrapper).toggleClass("selected");
        if(selectedAnswer)
            $(selectedAnswer).toggleClass("selected");
        selectedAnswer = answerWrapper;
      }
    });

    $("#question_answers").on("keydown", ".answer", (event)=>{
      let target = event.target;
      if(event.keyCode === 13){
        event.preventDefault();
        if($(target).val()){
            $(target).attr("readonly",true);
            $(target).css("border", "1px solid white");
            $(target).toggleClass("wrapped");
            answerAdding = false;
        } else {
          $(target).css("border", "2px solid red");
        }
      }
    });

    $(".create-question-btn").click((event)=>{
        event.preventDefault();
        if(checkForm())
        {
            let data = $("#form_question").serialize()+"&selected="+$(selectedAnswer).find(".answer").val();
            $.ajax({
              url: Routing.generate("_create_question"),
              type: "POST",
              data: data,
              success: (response)=>{
                selectedAnswer = null;
                options.success(response);
              },
              error: (response)=>{    
                let data = JSON.parse(response.responseText);
                showError(data.message.name[data.message.name.length-1]);
              }
            }) 
        } 
    });

    function addAnswerForm(){
      var prototype = collectionHolder.data('prototype');
      var index = collectionHolder.data('index');
      var newForm = prototype.replace(/__name__/g, index);
      collectionHolder.data('index', index + 1);
      $("#question_answers").append(newForm);  
    }

    function clearForm(){
      $(".question-name").css("border","1px solid white");
      let answersElements= $(".answer");
      let answerArray = answersElements.toArray();
      answerArray.forEach((item,index,array)=>{
        $(item).css("border", "1px solid white");
      });

    }

    function checkForm(){
      clearForm();

      let correct = true;
      let questionName = $(".question-name").val();
      let answersElements= $(".answer");
      let answerArray = answersElements.toArray();

      if (!questionName) {
          correct = false;
          $(".question-name").css("border", "2px solid red");
      }

      answerArray.forEach((item,index,array)=>{
          if($(item).val() === "")
          {
            $(item).css("border", "2px solid red");
            correct = false;
          }
      });

      if(!correct){
        showError(Translator.trans("errors.empty_values"));
        return correct;
      }

      if(!selectedAnswer){
        showError(Translator.trans("errors.none_selected"));
        return false;
      }

      return correct;
    }

    function showError(error){
      $(".error-text").html(error);
      $(".error").show();
      $('html, body').animate({
        scrollTop: $(".question-creator").offset().top
      }, 1000);
      setTimeout(()=>{
        $(".error").fadeOut();
      },5000);
    }

  };

})( jQuery );