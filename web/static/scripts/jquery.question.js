(function($){

  $.fn.questionView = function(options) {    

  	let container = $(this);
  	let questionName;
  	let selectedAnswer;
  	let answers = {};
  	let error = false;
  	let isAnswerInputing = false;


  	if(options.render){
  		renderQuestion();
  	}

  	$(".answer-name-input").keydown((event)=>{
        if(event.keyCode === 13){
            let answer = $(".answer-name-input").val();
            if(answer){
              if(!answers.hasOwnProperty(answer.trim())){
                  $(".answers").append("<li class='answer'><div class='answer-text'>" + answer + "</div><i class='fa fa-trash-o trash-btn' aria-hidden='true'></i><i class='fa fa-pencil-square-o edit-btn' aria-hidden='true'></i></li>");
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
                  isAnswerInputing = false;
              }
              else{
                error = true;
                $(".errors").html(Translator.trans("errors.answer_duplicate"));
              }
            } 
        }
 	});

  	$(".content").on("click",".trash-btn", (event)=>{
  		console.log(answers);
  		let target = event.target;
  		let answer = $(target).siblings(".answer-text").html();
  		delete answers[answer];
  		$(target).closest(".answer").remove();
  		console.log(answers);
  	});


  	$(".content").on("click",".edit-btn", (event)=>{
  		console.log(answers);
  		let target = event.target;
  		let answer = $(target).siblings(".answer-text").html();
  		delete answers[answer];
  		$(target).closest(".answer").replaceWith("<input class='edit-answer-input' value='" +  answer +"'>");
  	});

  	$(".content").on("keydown", ".edit-answer-input", (event)=>{
  		if(event.keyCode === 13){
            let target = event.target;
            let answer = $(target).val();
            if(answer){
              if(!answers.hasOwnProperty(answer.trim())){
                  $(".edit-answer-input").replaceWith("<li class='answer'><div class='answer-text'>" + answer + "</div><i class='fa fa-trash-o trash-btn' aria-hidden='true'></i><i class='fa fa-pencil-square-o edit-btn' aria-hidden='true'></i></li>");
                  answers[answer] = false;
                  console.log(answers);
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
  	})

  	$(".add-answer").click((event)=>{
  		if(isAnswerInputing){
  			$(".answer-name-input").css("border","2px solid red");
  		} else {
  			isAnswerInputing = true;
  			$(".answer-name-input").css("border","1px solid black");
  			$(".answer-name-input").css("display","block");
  		}
  	});

  	$(".submit-question").click((event)=>{
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


  	$(".content").on('click','.answer',(event)=>{
      let target = event.target;

      $(target).toggleClass("selected");
      $(selectedAnswer).toggleClass("selected");

      if(selectedAnswer){      
        answers[$(selectedAnswer).html()] = false;
      }

      answers[$(target).html()] = true;

      selectedAnswer = target;

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