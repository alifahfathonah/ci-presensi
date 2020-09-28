$(document).ready(function () {

});

function update_password(){
	var form = $('#form-password')[0];
	var formData = new FormData(form);
	$.ajax({
		url: base_url + "administrator/update_password/",
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			if(data.status){
				$('#input_username').val(data.username);
				$('#input_password').val();
				$('#alert-password-message').html('<div class="alert alert-success">' + data.message + '</di>');
			} else {
				$('#alert-password-message').html('<div class="alert alert-danger">' + data.message + '</di>');
			}
		}
	});
}
