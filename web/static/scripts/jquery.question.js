(function($){

  $.fn.questionView = function(options) {    


    let ENTER_KEYCODE = 13;

  	let container = $(this);
  	let questionName;
  	let selectedAnswer;
  	let answers = {};
  	let error = false;
  	let isAnswerInputing = false;


  	if(options.render){
  		renderQuestion();
  	}


    //Refactored
  	$(container).on("keydown", ".answer-name-input", (event)=>{
        if(event.keyCode === ENTER_KEYCODE){
            let answer = $(".answer-name-input").val();
            if(answer){
              if(!answers.hasOwnProperty(answer.trim())){
                  $(".answers").append("<li class='answer'>" +
                                       "<div class='answer-text' data-class='text'>" + answer +"</div>" +
                                       "<i class='fa fa-trash-o trash-btn' data-class='not-selecteable' aria-hidden='true'></i>" +
                                       "<i class='fa fa-pencil-square-o edit-btn' data-class='non-selecteable' aria-hidden='true'></i></li>");

                  answers[answer] = false;

                  hideAnswerNameInput();

                  $(".errors").html();

                  isAnswerInputing = false;

                  if(!answers.length)
                  {
                    $(".answer-info").html("");
                  }

              } else {
                error = true;
                $(".errors").html(Translator.trans("errors.answer_duplicate"));
              }
            } 
        }
 	  });


      //Refactore
  	$(container).on("click",".trash-btn", (event)=>{
  		console.log(answers);
  		let target = event.target;
  		let answer = $(target).siblings(".answer-text").html();
  		delete answers[answer];
  		$(target).closest(".answer").remove();
  		console.log(answers);
  	});



      //Refactore
  	$(container).on("click",".edit-btn", (event)=>{
  		let target = event.target;
  		let answer = $(target).siblings(".answer-text").html();
  		delete answers[answer];
      let answerBlock = $(target).closest(".answer");
      console.log(answerBlock);
      if(selectedAnswer == answerBlock){
        selectedAnswer = null;
      }
  		$(answerBlock).replaceWith("<input class='edit-answer-input form-control' value='" +  answer +"'>");
      console.log(answers);
  	});


      //Refactore
  	$(container).on("keydown", ".edit-answer-input", (event)=>{
  		if(event.keyCode === 13){
            let target = event.target;
            let answer = $(target).val();
            if(answer){
              if(!answers.hasOwnProperty(answer.trim())){
                   $(target).replaceWith("<li class='answer'>" +
                                       "<div class='answer-text' data-class='text'>" + answer +"</div>" +
                                       "<i class='fa fa-trash-o trash-btn' data-class='not-selecteable' aria-hidden='true'></i>" +
                                       "<i class='fa fa-pencil-square-o edit-btn' data-class='non-selecteable' aria-hidden='true'></i></li>");

                  answers[answer] = false;

                  hideAnswerNameInput();

                  $(".errors").html();

                  isAnswerInputing = false;

                  if(!answers.length)
                  {
                    $(".answer-info").html("");
                  }
              }
              else{
                error = true;
                $(".errors").html(Translator.trans("errors.answer_duplicate"));
              }
            } 
        }
  	})


    //Refactore
  	$(container).on('click', '.add-answer',(event)=>{
  		if(isAnswerInputing){
  			$(".answer-name-input").css("border","2px solid red");
  		} else {
  			isAnswerInputing = true;
  			$(".answer-name-input").css("border","1px solid black");
  			$(".answer-name-input").css("display","block");
  		}
  	});


    //Refactore
  	$(container).on('click', '.submit-question', (event)=>{
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
      	}).done((response)=>{
        	if(options.questions){
        		options.questions.push(24);
        	}
        	if(options.afterCreate){
        		options.afterCreate();
        	}
      	}).fail((response)=>{
        	$(".errors").html(response.responseText);
      	});
    	}
  	});



    //Refactored
  	$(container).on('click','.answer',(event)=>{
      let target = event.target;

      if($(target).attr("data-class") !== "non-selecteable"){
        let answer = $(target).closest(".answer");
        let answerText;

        if($(target).attr("data-class") === 'text'){
          answerText = $(target).html();
        } else {
          answerText = $(target).find(".answer-text").html();
        }

        $(answer).toggleClass("selected");
        $(selectedAnswer).toggleClass("selected");

        if(selectedAnswer){      
          answers[selectedAnswer.find(".answer-text").html()] = false;
        }

        answers[answerText] = true;

        selectedAnswer = answer;
      }
  	});


  	function renderQuestion(){
  		$(container).html(
  		"<div class='col content'>" + "<h1 class='question-header'>" + Translator.trans("admin.question.question_name") + "</h1>" +
         "<hr color='white'>" +
         "<input class='form-control question-name-input' placeholder='" + Translator.trans("admin.question.question_placeholder") + "'>" +
         "<div class='answer-wrapper'>" +
         "<h2>" + Translator.trans("admin.question.answer_label") + "</h2>" +
         "<p class='answer-info'>" + Translator.trans("admin.question.answer_info") + "</p>" +
         "<ul class='answers'></ul>" +
         "<input style='display: none' class='form-control answer-name-input' placeholder='" + Translator.trans("admin.question.answer_placeholder") + "'>" +
         "<div class='errors'></div>" +
         "<div class='col align-self-center answer-add-wrapper'>" +
         "<button class='btn btn-large add-answer'>" + Translator.trans("admin.question.answer_adding") + "</button></div></div>" +
         "<div class='col align-self-end question-submit-wrapper'>" +
         "<button class='btn btn-large submit-question'>" + Translator.trans("admin.question.question_create") + "</button></div></div>");
  	}

    window.printAnswers = ()=>{
      console.log(answers);
    }

    window.printSelectedAnswer = ()=>{
      console.log(selectedAnswer);
    }

    function hideAnswerNameInput(){
      $(".answer-name-input").css("display","none");
      $(".answer-name-input").val("");
    }

  //Refactore
 	  function checkQuestionForm(){
    	if(!$(".question-name-input").val())
    	{
      		$(".errors").html(Translator.trans("errors.question_name"));
      		return false;
    	} else if(!selectedAnswer) {
        	error = true;
        	$(".errors").html(Translator.trans("errors.select_answer"));
        	return false;
    	}
    	return true;
  	}

  
  };
})( jQuery );