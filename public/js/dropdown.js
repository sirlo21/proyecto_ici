$("#departamento").change(event => {
	$.get(`provincias/${event.target.value}`, function(res, sta){
		$("#provincia").empty();
		res.forEach(element => {
			$("#provincia").append(`<option value=${element.id}> ${element.name} </option>`);
		});
	});
});