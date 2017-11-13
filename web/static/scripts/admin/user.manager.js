$(document).ready(()=>{

	$(document).ajaxStart(()=>{
		$(".loader").show();
	});

	$(document).ajaxStop(()=>{
		$(".loader").hide();
	});

	$("table").on('click', '.btn-block', (event)=>{
		let target = event.target;
		let id = $(target).closest(".user-row").attr("data-id");

		let data = {
			id: id,
			token: $("#csrf-token").val(),
		}
		$.ajax({
			url: Routing.generate("_block_user"),
			type: "PATCH",
			data: data,
			success: (response)=>{
				$(target).parent().siblings(".user-enabled").html("false");
				$(target).replaceWith("<button type='button' class='btn btn-outline-success button-size btn-unblock'>"+Translator.trans("admin.user_manager.unblock")+"</button>");
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

	$("table").on('click', '.btn-unblock', (event)=>{
		let target = event.target;
		let id = $(target).closest(".user-row").attr("data-id");

		let data = {
			id: id,
			token : $("#csrf-token").val(),
		}

		$.ajax({
			url: Routing.generate("_unblock_user"),
			type: "PATCH",
			data: data,
			success: (response)=>{
				$(target).parent().siblings(".user-enabled").html("true");
				$(target).replaceWith("<button type='button' class='btn btn-outline-danger button-size btn-block'>"+Translator.trans("admin.user_manager.block")+"</button>");
				$(".success").show();
				setTimeout(()=>{
					$(".success").fadeOut();
				},1000)
			},

			error: (response)=>{
				$(".error").show();
					$(".error-content").html(response.responseText);
					setTimeout(()=>{
						$(".error").fadeOut();
					}, 1000);
			}
		});
	});

});