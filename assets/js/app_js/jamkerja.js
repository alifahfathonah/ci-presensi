var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {

  table = $('#table_jamkerja').DataTable({
    "lengthChange": true,
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		],
		"autoWidth": true,
		"processing": true,
		"serverSide": true,
		"order": [],
		"dom": "<'row'>" +
		"<'row'<'col-sm-12 col-md-6'><'col-md-6 text-right'>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"+
		"<'row'<'col-sm-12  row_show '>>",
		"buttons": ['colvis'],

		"ajax": {
			"url": base_url + "jamkerja/ajax_list/",
			"type": "POST"
		},

		"columnDefs": [{
			"targets": [-1],
			"orderable": false,
		}, ],
	});

  form_validation();
  $('#input_jam_masuk').timepicker();
  $('#input_jam_pulang').timepicker();
});

function reload_table() {
	table.ajax.reload(null, false);
}

function modal_edit(id){
  $.ajax({
      type: "GET",
      url : base_url+"jamkerja/detail/"+id,
      dataType: "JSON",
      success: function(data){
        $('[name="id"]').val(data.id );
        $('[name="input_jam_masuk"]').val(data.jam_masuk );
        $('[name="input_jam_pulang"]').val(data.jam_pulang );
        $('#modal-edit').modal('show');
        save_method = "edit";
      }
  });
}

function form_validation() {
	$('#form-jamkerja').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_jam_masuk',
			'input_jam_pulang'
		];

		var input_list_error = [
			'input_jam_masuk_error_detail',
			'input_jam_pulang_error_detail'
		];

		$.ajax({
			url: base_url + "jamkerja/validation/",
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSent: function () {
				$('#btn-save').attr('disabled', true);
			},
			success: function (data) {

				if (data.error) {
					for (let index = 0; index < input_list.length; index++) {
						const input_ = input_list[index];
						const input_error = input_list_error[index];
						if (data[input_error] !== "") {
							$('[id=' + input_error + ']').html(data[input_error]);
							$('[id=' + input_ + '_error_container]').addClass('has-danger');
              $('[id=' + input_ + '_error_icon]').text('clear');
						} else {
              $('[id=' + input_error + ']').html('');
              $('[id=' + input_ + '_error_container]').removeClass('has-danger');
							$('[id=' + input_ + '_error_container]').addClass('has-success');
              $('[id=' + input_ + '_error_icon]').text('done');
						}
					}
				}

				if (data.success) {
					for (let index = 0; index < input_list.length; index++) {
						const input_ = input_list[index];
						const input_error = input_list_error[index]

            $('[id=' + input_error + ']').html('');
            $('[id=' + input_ + '_error_container]').removeClass('has-danger');
            $('[id=' + input_ + '_error_container]').addClass('has-success');
            $('[id=' + input_ + '_error_icon]').text('done');
					}
					save();
				}

				$('#btn-save').attr('disabled', false);
			}
		});
	});
}


function save() {
	var url;
  url = base_url + "jamkerja/update/";
	var form = $('#form-jamkerja')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
      $('#modal-edit').modal('hide');
      reload_table();
		}
	});
}
