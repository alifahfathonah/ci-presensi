var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {

  $('#form-cuti').on('submit', function (event){

    var value = $('#input_hak_cuti').val();

    if(value == ""){
      $('#form-input-container').removeClass('has-success');
      $('#form-input-container').addClass('has-danger');
      $('#success-label').text('Harus diisi!');
    } else if(value == 0){
      $('#form-input-container').removeClass('has-success');
      $('#form-input-container').addClass('has-danger');
      $('#success-label').text('Tidak boleh 0!');
    } else {
      $('#form-input-container').removeClass('has-danger');
      $('#form-input-container').addClass('has-success');
      $('#success-label').text('...');
      var form = $('#form-cuti')[0];
    	var formData = new FormData(form);
    	$.ajax({
    		url: base_url + "cuti/insert",
    		method: 'POST',
    		data: formData,
    		dataType: 'json',
    		contentType: false,
    		processData: false,
    		success: function (data) {
          if(data.status){
              $('#success-label').text('Ok!');
          }
    		}
    	});

    }
  });

});

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
