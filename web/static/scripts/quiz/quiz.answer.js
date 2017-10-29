$(document).ready(function(){
	let selectedAnswer;
	let selectedAnswerId;


	$(".answer").click((event)=>{
		$(selectedAnswer).toggleClass("selected");
		let target = event.target;
		selectedAnswer = target;
		$(selectedAnswer).toggleClass("selected");
		selectedAnswerId = $(selectedAnswer).attr("data-id");
	});


});