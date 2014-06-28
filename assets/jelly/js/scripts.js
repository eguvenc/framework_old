$(document).on('change', '#select_data', function(event)
{
	event.preventDefault();
	var val = $(this).val();

	if (val!=0)
		{
			$.ajax({
				url: 'table.html',
				type: 'POST',
				dataType: 'html',
				data: {value:val},
			})
			.done(function(data)
			{	
				$("#data-table").remove();
				$(".row").append(data);
			})
			.fail(function()
			{
				$("#data-error").remove();
				$(".row").append("<div class='alert alert-danger' id='data-error'>Error While Getting Data For Selected Type</div>");
				console.log("error");
			})
		}
});
