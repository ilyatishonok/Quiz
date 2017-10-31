$(document).ready(()=>{

	$(".btn-block").click((event)=>{
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
				console.log(response);
			},
			error: (response)=>{
				console.log(response);
			}
		})
	});

});