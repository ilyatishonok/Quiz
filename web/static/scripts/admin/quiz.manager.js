$(document).ready(()=>{

	$(document).ajaxStart(()=>{
		$(".loader").show();
	});

	$(document).ajaxStop(()=>{
		$(".loader").hide();
	});

	$("table").on('click', '.btn-block', (event)=>{
		let target = event.target;
		let id = $(target).closest(".quiz-row").attr("data-id");

		let data = {
			id: id,
			token: $("#csrf-token").val(),
		}
		$.ajax({
			url: Routing.generate("_block_quiz"),
			type: "PATCH",
			data: data,
			success: (response)=>{
				$(target).parent().siblings(".quiz-enabled").html("false");
				$(target).remove();
				$(".success").show();
				setTimeout(()=>{
					$(".success").fadeOut();
				},1000)
			},
			error: (response)=>{
				if(response.status === 400)
				{
					$(".error").show();
					$(".error-content").html(response.responseText);
					setTimeout(()=>{
						$(".error").fadeOut();
					}, 1000);
				}
			}
		})
	});
});