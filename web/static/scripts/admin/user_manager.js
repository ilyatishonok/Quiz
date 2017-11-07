$(document).ready(()=>{

	$(document).ajaxStart(()=>{
		$(".loader").show();
	});

	$(document).ajaxStop(()=>{
		$(".loader").hide();
	});

	$(".users-table").on('click', '.btn-block', (event)=>{
		let target = event.target;
		let id = $(target).closest(".user-row").attr("data-id");

		let data = {
			id: id,
			token: $(".users-table").attr("data-token"),
		}

		$.ajax({
			url: "/app_dev.php/admin/api/block-user",
			type: "PATCH",
			data: data,
			success: (response)=>{
				$(target).parent().siblings(".status").html("Blocked");
				$(target).replaceWith("<button type='button' class='btn btn-outline-success button-size btn-unblock'>Unblock</button>");
			},
			error: (response)=>{
				console.log(response);
			}
		})
	});

	$(".users-table").on('click', '.btn-unblock', (event)=>{
		let target = event.target;
		let id = $(target).closest(".user-row").attr("data-id");

		let data = {
			id: id,
			token : $(".users-table").attr("data-token"),
		}

		$.ajax({
			url: "/app_dev.php/admin/api/unblock-user",
			type: "PATCH",
			data: data,
			success: (response)=>{
				$(target).parent().siblings(".status").html("Active");
				$(target).replaceWith("<button type='button' class='btn btn-outline-danger button-size btn-block'>Block</button>");
			},

			error: (response)=>{
				console.log(response);
			}
		});
	});

	$(".search-btn").click((event)=>{
		let searchQuery = $(".search-input").val();
		window.href = "?search="+searchQuery;	
	});

});