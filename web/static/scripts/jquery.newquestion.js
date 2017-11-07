(function($){

  $.fn.questionView = function(options) {

    let answerAdding = false;
    let selectedAnswer;

    var collectionHolder = $("#question_answers");
    collectionHolder.data('index', collectionHolder.find(':input').length);

    $(".add-answer-btn").on("click", function(e){
      e.preventDefault();
      addAnswerForm();
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
      if(event.keyCode === 13){
        event.preventDefault();
        let target = event.target;
        $(target).attr("readonly","readonly");
        $(target).toggleClass("wrapped");
      }
    });


    $(".add-question-btn").click((event)=>{
        event.preventDefault();
        let data = $("#form_question").serialize()+"&selected="+$(selectedAnswer).find(".answer").val();
        $.ajax({
          url: "/app_dev.php/admin/api/create/question",
          type: "POST",
          data: data,
          success: (response)=>{
            options.success(response);
          },

          error: (response)=>{    
            $(".answers").html(response.responseText);  
          }
        }) 
        console.log("lox");  
    });

    function addAnswerForm(){
      var prototype = collectionHolder.data('prototype');
      var index = collectionHolder.data('index');
      var newForm = prototype.replace(/__name__/g, index);
      collectionHolder.data('index', index + 1);
      $("#question_answers").append(newForm);  
    }


  };

})( jQuery );