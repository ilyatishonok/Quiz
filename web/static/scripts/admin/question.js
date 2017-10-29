$(document).ready(function() {
  let questions = [1];
  $(".question-handler").questionView({
    render: false,
    questions: questions,
    afterCreate: ()=>{
      $(".question-handler").html(
        "<div class=\'question-success\'>The question was successfly created!</div>" + 
        "<button class='btn btn-large btn-main-page'>Back to main page</button>"
      );
      console.log(questions);
    }
  });
})