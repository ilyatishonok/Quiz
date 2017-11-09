$(document).ready(function() {

  $(document).ajaxStart(()=>{
    $(".loader").show();
  });

  $(document).ajaxStop(()=>{
    $(".loader").hide();
  });

  $(".question-handler").questionView({
    success:()=>{
      $(".question-handler").html(
        "<div class='h3'>The question was successfully created!</div>"+
        "<a href=''>Back</a>"
        );
    }
  });


  /*$(".question-handler").questionView({
    render: false,
    questions: questions,
    afterCreate: ()=>{
      $(".question-handler").html(
        "<div class=\'question-success\'>The question was successfly created!</div>" + 
        "<button class='btn btn-large btn-main-page'>Back to main page</button>"
      );
    }
  });*/
})