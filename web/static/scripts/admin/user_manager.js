$(document).ready(()=>{

	appendButtons();

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
			url: Routing.generate("_block_user"),
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
			url: Routing.generate("_unblock_user"),
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

	function appendButtons()
	{
		let dropDowns = $(".dropdown-menu");
		let dropDownsArray = dropDowns.toArray();

		dropDownsArray.forEach((item,index,array)=>{
			let enabled = $(item).parent().siblings(".user-enabled").html();
			if(enabled)
			{
				$(item).append("<a class='btn btn-outline-danger button-size btn-block'>Block</a>")
			}
			else
			{
				$(item).append("<a class='btn btn-outline-danger button-size btn-block'>Unblock</a>")
			}
		});
	}

});