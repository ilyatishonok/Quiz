(function($){

  $.fn.questionView = function(options) {

    let answerAdding = false;

    var collectionHolder = $(".answers");
    collectionHolder.data('index', collectionHolder.find(':input').length);

    $(".add-answer-btn").on("click", function(e){
      e.preventDefault();
      addAnswerForm();
    });

    $(".answers").on("keydown", ".answer", (event)=>{
      if(event.keyCode === 13){
        event.preventDefault();
        let target = event.target;
        console.log($(target).val());
      }
    });

    $(".add-question-btn").click((event)=>{
        let data = $("#form_question").serialize();
        $.ajax({
          url: "/app_dev.php/admin/api/create/question",
          type: "POST",
          data: data,
          success: (response)=>{
            $(".errors").html(response.responseText);
          },

          error: (response)=>{
            $(".errors").html(response.responseText);  
          }
        })   
    });

    function addAnswerForm(){
      var prototype = collectionHolder.data('prototype');
      console.log(prototype);
      var index = collectionHolder.data('index');
      var newForm = prototype.replace(/__name__/g, index);
      collectionHolder.data('index', index + 1);
      $(".answers").append(newForm);  
    }


  };

})( jQuery );