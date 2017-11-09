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
          $(".answers-errors").html("You already adding answer!");
          return;
      }
      addAnswerForm();
      answerAdding = true;
    });


    $(".answers").on("click", ".answer-wrapper", (event)=>{
      event.preventDefault();
      let target = event.target;
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
            $(target).attr("readonly","readonly");
            $(target).toggleClass("wrapped");
            answerAdding = false;
            $(".answers-errors").html("");
            $(target).parent(".answer-wrapper").next(".answer-error").html("");
        } else {
          $(target).parent(".answer-wrapper").next(".answer-error").html("This value can't be empty!");
        }
      }
    });

    $(".create-question-btn").click((event)=>{
        event.preventDefault();
        if(!checkForm())
        {
            let data = $("#form_question").serialize()+"&selected="+$(selectedAnswer).find(".answer").val();
            $.ajax({
              url: "/app_dev.php/admin/api/create/question",
              type: "POST",
              data: data,
              success: (response)=>{
                console.log(response);
                options.success(response);
              },
              error: (response)=>{    
                let data = JSON.parse(response.responseText);
                $(".question-error").html(data.message.name[data.message.name.length-1]);
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

    function checkForm(){
      let errors = false;
      if(!$(".question-name").val()){
        $(".question-error").html("You haven't input question name!");
        errors = true;
      }
      if(!selectedAnswer)
      {
        errors = true;
        $(".answers-errors").html("You haven't select any answer!");
      }
      let answerInputs = $(".answer");
      let answersArray = answerInputs.toArray();
      answersArray.forEach((item,index,array)=>{
        let input = $(item).val();
        if(!input){
          $(item).parent(".answer-wrapper").next(".answer-error").html("This shouldn't be empty!");
          errors = true;
        }
        else{
          $(item).parent(".answer-wrapper").next(".answer-error").html("");
        }
      });
      return errors;
    }

  };

})( jQuery );