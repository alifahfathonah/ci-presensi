var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {

	var rupiah = document.getElementById('input_gaji_pokok');
	if(rupiah !== null){
		rupiah.addEventListener('keyup', function(e){
				rupiah.value = formatRupiah(this.value, '');
		});
	}

	filter_date = false;

	form_validation();
	form_validation_keluarga();
	form_validation_pendidikan();
	form_validation_kerja();
	form_validation_jabatan();

	preview_image_upload();

	// upload_file();

	$('#input_tanggal_masuk').datepicker({
		showOtherMonths: true,
		format: 'dd-mm-yyyy'
	});

	$('#input_tmt_jabatan').datepicker({
		showOtherMonths: true,
		format: 'dd-mm-yyyy'
	});

	$('#input_tanggal_lahir').datepicker({
		showOtherMonths: true,
		format: 'dd-mm-yyyy'
	});

	$('#input_tanggal_lahir_keluarga').datepicker({
		showOtherMonths: true,
		format: 'dd-mm-yyyy'
	});

	fetch_data();
	$('.buttons-colvis').text("Tampilan Kolom");
});

function filter_data(){
	id_departemen = $('#input_departemen_filter').val();
	if(id_departemen == "x"){
		alert('Silahkan pilih departemen');
	} else {
		id_departemen = $('#input_departemen_filter').val();
		$("#table_karyawan").dataTable().fnDestroy();
		fetch_data( id_departemen);
	}
}

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

/* Fungsi formatRupiah */
function formatRupiah(angka, prefix=""){
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
	split   		= number_string.split(','),
	sisa     		= split[0].length % 3,
	rupiah     		= split[0].substr(0, sisa),
	ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if(ribuan){
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}

	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	// return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
	return rupiah;
}

function fetch_data( id_departemen  = "" ) {
	table = $('#table_karyawan').DataTable({
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
		"<'row'<'col-sm-12 col-md-6'l><'col-md-6 text-right'f>>" +
		"<'row'<'col-sm-12 filter_container'>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"+
		"<'row'<'col-sm-12 row_show '>>",
		"buttons": ['colvis'],
		"ajax": {
			"url": base_url + "karyawan/ajax_list/",
			"type": "POST",
			"data" : function (data) {
			  data.id_departemen    = id_departemen;
			}
		},

		"columnDefs": [{
			"targets": [-1],
			"orderable": false,
		}, ],
	});

	$('.filter_container').html(
		'<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">' +
		'<button onclick="reload_table()" title="Reload" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-refresh"></b></button>' +
		'<button onclick="open_modal_filter()" title="Filter" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-filter"></b></button>' +
		'</div>'
	);
}

function reload_table() {
	$("#table_karyawan").dataTable().fnDestroy();
	fetch_data();
}

function get_kota(){
	var id_prov = $('#input_provinsi').val();
	$.ajax({
		url: base_url + 'Indonesia/ajax_kota/' + id_prov,
		method: 'GET',
		success: function (html) {
			if(id_prov == "x"){
				$('#input_kota').html('<option value="x" selected>-- Silahkan Pilih Provinsi Dahulu --</option>');
			} else {
				$('#input_kota').html(html);
			}
		}
	});
}

function form_validation() {
	$('#form-karyawan').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_nip',
			'input_nama_lengkap',
			'input_tanggal_lahir',
			'input_alamat',
			'input_no_telp',
			'input_no_ktp',
			'input_alamat_ktp',
			'input_email',
			'input_gaji_pokok',
			'input_rekening',

			'input_jenis_kelamin',
			'input_tempat_lahir',
			'input_kota',
			'input_provinsi',
			'input_agama',
			'input_golongan_darah',
			'input_status_kawin',
			'input_status_karyawan',
			'input_departemen',
			'input_lock_area',

		];

		var input_list_error = [
			'input_nip_error_detail',
			'input_nama_lengkap_error_detail',
			'input_tanggal_lahir_error_detail',
			'input_alamat_error_detail',
			'input_no_telp_error_detail',
			'input_no_ktp_error_detail',
			'input_alamat_ktp_error_detail',
			'input_email_error_detail',
			'input_gaji_pokok_error_detail',
			'input_rekening_error_detail',

			'input_jenis_kelamin_error_icon',
			'input_tempat_lahir_error_icon',
			'input_kota_error_icon',
			'input_provinsi_error_icon',
			'input_agama_error_icon',
			'input_golongan_darah_error_icon',
			'input_status_kawin_error_icon',
			'input_status_karyawan_error_icon',
			'input_departemen_error_icon',
			'input_lock_area_error_icon',

		];

		$.ajax({
			url: base_url + "karyawan/validation/",
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

							if( input_error == "input_tanggal_lahir_error_detail"){
								$('.gj-icon').addClass('text-danger');
								$('.gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}

						} else {
							$('[id=' + input_error + ']').html('');
							$('[id=' + input_ + '_error_container]').removeClass('has-danger');
							$('[id=' + input_ + '_error_container]').addClass('has-success');
							$('[id=' + input_ + '_error_icon]').text('done');

							if( input_error == "input_tanggal_lahir_error_detail"){
								$('.gj-icon').addClass('text-success');
								$('.gj-icon').addClass('has-success');
								$('[id=' + input_ + '_error_icon]').text('');
							}
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
	if (save_method == "add") {
		url = base_url + "karyawan/insert/";
	} else {
		url = base_url + "karyawan/update/";
	}
	var form = $('#form-karyawan')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			$('#form-karyawan')[0].reset();
			if (save_method == "add") {
				window.open(base_url + "karyawan", "_self");
			} else {
				window.open(base_url + "karyawan/profil/"+$('#id').val(), "_self");
			}
		}
	});
}

function open_modal_filter(){
	$('#modal-filter').modal('show');
}

function open_modal_upload(is_attachment=""){
	$('#img-upload').removeAttr('src');
	if(is_attachment == 2){
		$('#judul-modal-upload').text('Upload File KTP');
	} else if(is_attachment == 3){
		$('#judul-modal-upload').text('Upload File Kartu Keluarga');
	} else if(is_attachment == 4){
		$('#judul-modal-upload').text('Upload File Ijazah');
	} else if(is_attachment == 5){
		$('#judul-modal-upload').text('Upload File Transkrip');
	} else if(is_attachment == 6){
		$('#judul-modal-upload').text('Upload File CV');
	} else if(is_attachment == 7){
		$('#judul-modal-upload').text('Upload File Pengamalan Kerja');
	} else {
		$('#judul-modal-upload').text('Upload Foto Profil');
	}

	$('#is_attachment').val(is_attachment);
	$('#modal-upload').modal('show');
}

function preview_image_upload(){
	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {

			var input = $(this).parents('.input-group').find(':text'),
			log = label;

			if( input.length ) {
				input.val(log);
			} else {
				if( log ) alert(log);
			}

		});
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$('#img-upload').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#imgInp").change(function(){
			readURL(this);
		});
	}

function open_modal_keluarga(){
	save_method="add";
	reset_validasi_keluarga();
	$('#modal-keluarga').modal('show');
}

function form_validation_keluarga() {
	$('#form-keluarga').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_nama_lengkap_keluarga',
			'input_tanggal_lahir_keluarga',
			'input_pekerjaan_keluarga',
			'input_pendidikan_keluarga',
			'input_no_telp_keluarga',

			'input_hubungan_keluarga',
			'input_tempat_lahir_keluarga',
			'input_status_kawin_keluarga'
		];

		var input_list_error = [
			'input_nama_lengkap_keluarga_error_detail',
			'input_tanggal_lahir_keluarga_error_detail',
			'input_pekerjaan_keluarga_error_detail',
			'input_pendidikan_keluarga_error_detail',
			'input_no_telp_keluarga_error_detail',

			'input_hubungan_keluarga_error_icon',
			'input_tempat_lahir_keluarga_error_icon',
			'input_status_kawin_keluarga_error_icon',
		];

		$.ajax({
			url: base_url + "keluarga_karyawan/validation/",
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

							if( input_error == "input_tanggal_lahir_keluarga_error_detail"){
								$('.gj-icon').addClass('text-danger');
								$('.gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}

						} else {
							$('[id=' + input_error + ']').html('');
							$('[id=' + input_ + '_error_container]').removeClass('has-danger');
							$('[id=' + input_ + '_error_container]').addClass('has-success');
							$('[id=' + input_ + '_error_icon]').text('done');

							if( input_error == "input_tanggal_lahir_keluarga_error_detail"){
								$('.gj-icon').addClass('text-success');
								$('.gj-icon').addClass('has-success');
								$('[id=' + input_ + '_error_icon]').text('');
							}
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
					save_keluarga();
				}
				$('#btn-save').attr('disabled', false);
			}
		});
	});
}

function reset_validasi_keluarga(){
	var input_list = [
		'input_nama_lengkap_keluarga',
		'input_tanggal_lahir_keluarga',
		'input_pekerjaan_keluarga',
		'input_pendidikan_keluarga',
		'input_no_telp_keluarga',

		'input_hubungan_keluarga',
		'input_tempat_lahir_keluarga',
		'input_status_kawin_keluarga'
	];

	var input_list_error = [
		'input_nama_lengkap_keluarga_error_detail',
		'input_tanggal_lahir_keluarga_error_detail',
		'input_pekerjaan_keluarga_error_detail',
		'input_pendidikan_keluarga_error_detail',
		'input_no_telp_keluarga_error_detail',

		'input_hubungan_keluarga_error_icon',
		'input_tempat_lahir_keluarga_error_icon',
		'input_status_kawin_keluarga_error_icon',
	];

	for (let index = 0; index < input_list.length; index++) {
		const input_ = input_list[index];
		const input_error = input_list_error[index];

		$('[id=' + input_error + ']').html('');
		$('[id=' + input_ + '_error_container]').removeClass('has-danger');
		$('[id=' + input_ + '_error_container]').removeClass('has-success');
		$('[id=' + input_ + '_error_icon]').text('');

		if( input_error == "input_tanggal_lahir_keluarga_error_detail"){
			$('.gj-icon').removeClass('text-success');
			$('.gj-icon').removeClass('has-success');
			$('.gj-icon').removeClass('text-danger');
			$('[id=' + input_ + '_error_icon]').text('');
		}
	}
}

function save_keluarga() {
	var url;
	if (save_method == "add") {
		url = base_url + "keluarga_karyawan/insert/";
	} else {
		url = base_url + "keluarga_karyawan/update/";
	}
	var form = $('#form-keluarga')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			$('#form-keluarga')[0].reset();
			$('#modal-keluarga').modal('hide');
			if(data.status){
				load_data_keluarga();
			} else {
				alert('Oups! ada masalah!');
			}
		}
	});
}

function load_data_keluarga(){
	$.ajax({
		url: base_url + "keluarga_karyawan/load_data/"+$('#id_karyawan').val(),
		method: 'GET',
		success: function (html) {
			$('#table_data_keluarga').html(html);
		}
	});
}

function modal_edit_keluarga(id){
	reset_validasi_keluarga();
  $.ajax({
      type: "GET",
      url : base_url+"keluarga_karyawan/detail/"+id,
      dataType: "JSON",
      success: function(data){
        $('[name="id"]').val(data.id );

        $('[name="input_nama_lengkap_keluarga"]').val(data.nama_lengkap );
        $('[name="input_hubungan_keluarga"]').val(data.id_hubungan_keluarga );
				$('[name="input_tanggal_lahir_keluarga"]').val(data.tanggal_lahir );
				$('[name="input_pekerjaan_keluarga"]').val(data.pekerjaan );
				$('[name="input_no_telp_keluarga"]').val(data.no_telp );
				$('[name="input_tempat_lahir_keluarga"]').val(data.tempat_lahir );
				$('.card-title').text("Edit Data Keluarga");
        $('#modal-keluarga').modal('show');
        save_method = "edit";
      }
  });
}

function hapus_data_keluarga(id) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "keluarga_karyawan/delete/" + id,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					load_data_keluarga();
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}

function enable_edit_pendidikan(){
	$("#btn-batal-pendidikan").css({ 'display' : '' });
	$("#btn-save-pendidikan").css({ 'display' : '' });

	$('#input_tingkat_pendidikan').removeAttr("disabled");
	$('#input_sekolah').removeAttr("disabled");
	$('#input_kota_pendidikan').removeAttr("disabled");
	$('#input_gelar_pendidikan').removeAttr("disabled");
	$('#input_jurusan').removeAttr("disabled");
	$('#input_tahun_lulus').removeAttr("disabled");
	$('#input_tahun_masuk').removeAttr("disabled");
	$('#input_nilai').removeAttr("disabled");
	$('#input_fakultas').removeAttr("disabled");

}

function reset_edit_pendidikan(){
	$('#form-pendidikan')[0].reset();
	reset_validasi_pendidikan();
	$("#btn-batal-pendidikan").css({ 'display' : 'none' });
	$("#btn-save-pendidikan").css({ 'display' : 'none' });

	$('#input_tingkat_pendidikan').prop( "disabled", true );
	$('#input_sekolah').prop( "disabled", true );
	$('#input_kota_pendidikan').prop( "disabled", true );
	$('#input_gelar_pendidikan').prop( "disabled", true );
	$('#input_jurusan').prop( "disabled", true );
	$('#input_tahun_lulus').prop( "disabled", true );
	$('#input_tahun_masuk').prop( "disabled", true );
	$('#input_nilai').prop( "disabled", true );
	$('#input_fakultas').prop( "disabled", true );
}

function load_data_pendidikan(){
	$.ajax({
      type: "GET",
      url : base_url+"riwayat_pendidikan/detail/"+ $('#id_karyawan').val(),
      dataType: "JSON",
      success: function(data){
        $('[name="input_tingkat_pendidikan"]').val(data.id_level_pendidikan );
        $('[name="input_sekolah"]').val(data.asal_sekolah_univ );
				$('[name="input_gelar_pendidikan"]').val(data.gelar );
				$('[name="input_fakultas"]').val(data.fakultas );
				$('[name="input_jurusan"]').val(data.jurusan );
				$('[name="input_tahun_masuk"]').val(data.tahun_masuk );
				$('[name="input_tahun_lulus"]').val(data.tahun_lulus );
				$('[name="input_kota_pendidikan"]').val(data.kota );
				$('[name="input_nilai"]').val(data.ipk_nilai );
      }
  });
}

function form_validation_pendidikan() {
	$('#form-pendidikan').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_sekolah',
			'input_kota_pendidikan',
			'input_gelar_pendidikan',
			'input_fakultas',
			'input_jurusan',
			'input_tahun_masuk',
			'input_tahun_lulus',
			'input_nilai',
			'input_tingkat_pendidikan'
		];

		var input_list_error = [
			'input_sekolah_error_detail',
			'input_kota_pendidikan_error_detail',
			'input_gelar_pendidikan_error_detail',
			'input_fakultas_error_detail',
			'input_jurusan_error_detail',
			'input_tahun_masuk_error_detail',
			'input_tahun_lulus_error_detail',
			'input_nilai_error_detail',
			'input_tingkat_pendidikan_error_icon'
		];

		$.ajax({
			url: base_url + "riwayat_pendidikan/validation/",
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
					save_pendidikan();
				}
				$('#btn-save-pendidkan').attr('disabled', false);
			}
		});
	});
}

function reset_validasi_pendidikan(){
	var input_list = [
		'input_sekolah',
		'input_kota_pendidikan',
		'input_gelar_pendidikan',
		'input_fakultas',
		'input_jurusan',
		'input_tahun_masuk',
		'input_tahun_lulus',
		'input_nilai',
		'input_tingkat_pendidikan'
	];

	var input_list_error = [
		'input_sekolah_error_detail',
		'input_kota_pendidikan_error_detail',
		'input_gelar_pendidikan_error_detail',
		'input_fakultas_error_detail',
		'input_jurusan_error_detail',
		'input_tahun_masuk_error_detail',
		'input_tahun_lulus_error_detail',
		'input_nilai_error_detail',
		'input_tingkat_pendidikan_error_icon'
	];

	for (let index = 0; index < input_list.length; index++) {
		const input_ = input_list[index];
		const input_error = input_list_error[index];

		$('[id=' + input_error + ']').html('');
		$('[id=' + input_ + '_error_container]').removeClass('has-danger');
		$('[id=' + input_ + '_error_container]').removeClass('has-success');
		$('[id=' + input_ + '_error_icon]').text('');

	}
}

function save_pendidikan(){
	var id_karyawan = $('#id_karyawan_pendidikan').val();
	var url;
	if (id_karyawan == "") {
		url = base_url + "riwayat_pendidikan/insert/";
	} else {
		url = base_url + "riwayat_pendidikan/update/";
	}
	var form = $('#form-pendidikan')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			$('#form-pendidikan')[0].reset();
			if(data.status){
				alert('Update riwayat pendidikan berhasil');
				reset_edit_pendidikan();
				load_data_pendidikan();
			} else {
				alert('Oups! ada masalah!');
			}
		}
	});
}

function load_data_kerja(){
	$.ajax({
		url: base_url + "Pengalamankerja/load_data/"+$('#id_karyawan').val(),
		method: 'GET',
		success: function (html) {
			$('#table_data_kerja').html(html);
		}
	});
}

function open_modal_kerja(){
	save_method="add";
	reset_validasi_kerja();
	$('#modal-kerja').modal('show');
}

function reset_validasi_kerja(){
	var input_list = [
		'input_nama_perusahaan',
		'input_bidang',
		'input_jabatan',
		'input_kota_kerja',
		'input_masa_kerja'
	];

	var input_list_error = [
		'input_nama_perusahaan_error_detail',
		'input_bidang_error_detail',
		'input_jabatan_error_detail',
		'input_kota_kerja_error_detail',
		'input_masa_kerja_error_detail'
	];

	for (let index = 0; index < input_list.length; index++) {
		const input_ = input_list[index];
		const input_error = input_list_error[index];

		$('[id=' + input_error + ']').html('');
		$('[id=' + input_ + '_error_container]').removeClass('has-danger');
		$('[id=' + input_ + '_error_container]').removeClass('has-success');
		$('[id=' + input_ + '_error_icon]').text('');
	}
}

function form_validation_kerja() {
	$('#form-kerja').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_nama_perusahaan',
			'input_bidang',
			'input_jabatan',
			'input_kota_kerja',
			'input_masa_kerja'
		];

		var input_list_error = [
			'input_nama_perusahaan_error_detail',
			'input_bidang_error_detail',
			'input_jabatan_error_detail',
			'input_kota_kerja_error_detail',
			'input_masa_kerja_error_detail'
		];

		$.ajax({
			url: base_url + "pengalamankerja/validation/",
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSent: function () {
				$('#btn-save-kerja').attr('disabled', true);
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
					save_kerja();
				}
				$('#btn-save-kerja').attr('disabled', false);
			}
		});
	});
}

function detail_kerja(id){
	$.ajax({
      type: "GET",
      url : base_url+"pengalamankerja/detail/"+ id,
      dataType: "JSON",
      success: function(data){
				$('[name="id_kerja"]').val(data.id );
        $('[name="input_nama_perusahaan"]').val(data.nama_perusahaan );
        $('[name="input_bidang"]').val(data.bidang );
				$('[name="input_jabatan"]').val(data.jabatan );
				$('[name="input_kota_kerja"]').val(data.kota );
				$('[name="input_masa_kerja"]').val(data.masa_kerja );
				save_method="edit";
				$('.card-title').text("Edit Data Pengalaman Kerja");
				reset_validasi_kerja();
				$('#modal-kerja').modal('show');
      }
  });
}

function save_kerja(){
	var url;
	if (save_method == "add") {
		url = base_url + "pengalamankerja/insert/";
	} else {
		url = base_url + "pengalamankerja/update/";
	}
	var form = $('#form-kerja')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			$('#form-kerja')[0].reset();
			if(data.status){
				alert('Update pengalaman kerja berhasil');
				reset_validasi_kerja();
				load_data_kerja();
				$('#modal-kerja').modal('hide');
			} else {
				alert('Oups! ada masalah!');
			}
		}
	});
}

function hapus_data_kerja(id) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "pengalamankerja/delete/" + id,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					load_data_kerja();
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}

function upload_file() {
	var url;
	var attachment = $('#is_attachment').val();

	if (attachment == 2) {
		url = base_url + "fileattachment/upload_ktp/";
	} else if (attachment == 3) {
		url = base_url + "fileattachment/upload_kk/";
	} else if (attachment == 4) {
		url = base_url + "fileattachment/upload_ijazah/";
	} else if (attachment == 5) {
		url = base_url + "fileattachment/upload_transkrip/";
	} else if (attachment == 6) {
		url = base_url + "fileattachment/upload_cv/";
	} else if(attachment == ""){
		url = base_url + "karyawan/upload_foto/";
	}

	var form = $('#form-upload')[0];
	var formData = new FormData(form);
	// $('#form-upload').on('submit', function (event) {
		console.log('submit');
		$.ajax({
			url: url,
			method: 'POST',
			data: formData,
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function (data) {
				if(!data.status){
					alert(data.message);
				} else {
					$('#form-upload')[0].reset();
					if(attachment != ""){
						$('[id=' + data.id_container + ']').attr("src", base_url + "uploads/attachment/" + data.file);
						$('#btn-hps-foto-container_'+data.id_container).html(data.btn_hapus);
						$('#modal-upload').modal('hide');
					} else {
						$('[id=' + 'profil_img' + ']').attr("src", base_url + "uploads/photo_profil/" + data.file);
						$("#btn-hapus-foto-profil").css({ 'display' : ''});
						$('#modal-upload').modal('hide');
					}
				}
			}
		});
	// });
}

function hapus_file_attachment(id, img_container) {
	if (confirm('Anda yakin akan menghapus file ini?')) {
		$.ajax({
			url: base_url + "fileattachment/delete/" + id + "/" + img_container,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					$('[id=' + data.id_container + ']').attr("src", base_url + "uploads/no_file.png");
					$('#btn-hps-foto-container_'+data.id_container).html("");
					$('#modal-upload').modal('hide');
				} else {
					alert(data.message);
				}
			},
			// error: function (jqXHR, textStatus, errorThrown) {
			// 	console.log(errorThrown);
			// 	alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			// }
		});
	}
}

function hapus_data_karyawan(id) {
	if (confirm('Anda yakin akan menghapus profil ini?')) {
		$.ajax({
			url: base_url + "karyawan/delete/" + id,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					window.open(base_url + "karyawan");
				}
			},
			// error: function (jqXHR, textStatus, errorThrown) {
			// 	console.log(errorThrown);
			// 	alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			// }
		});
	}
}

function open_modal_import(){
	$('#alert-import-message').html();
	$('#modal-import').modal('show');
}

function do_import() {
	var form = $('#form-import')[0];
	var formData = new FormData(form);
	$('#alert-import-message').html('<div class="alert alert-danger">Import masih berjalan. Dilarang menutup / reload halaman / browser Anda.</di>');
	$.ajax({
		url: base_url + "karyawan/do_import/",
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			console.log(data.status);
			if(data.status){
				alert('Import Data Berhasil');
				$('#modal-import').modal('hide');
				reload_table();
			} else {
				$('#alert-import-message').html('<div class="alert alert-danger">' + data.message + '</di>');
			}
		}
	});
}

function update_password(){
	var form = $('#form-password')[0];
	var formData = new FormData(form);
	$.ajax({
		url: base_url + "username/update_password/",
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

function reset_password(){
	var form = $('#form-password')[0];
	var formData = new FormData(form);
	$.ajax({
		url: base_url + "username/reset_password/",
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			if(data.status){
				// $('#input_username').val(data.username);
				$('#input_password').val();
				$('#alert-password-message').html('<div class="alert alert-success">' + data.message + '</di>');
			} else {
				$('#alert-password-message').html('<div class="alert alert-danger">' + data.message + '</di>');
			}
		}
	});
}

function hapus_foto_profil(id){
	$.ajax({
		url: base_url + "karyawan/hapus_foto_profil/" + id,
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			$('#profil_img').attr("src", base_url + "uploads/no_image.png");
			$("#btn-hapus-foto-profil").css({ 'display' : 'none'});
		}
	});
}

function open_modal_jabatan(){
	save_method="add";
	reset_validasi_jabatan();
	$('#modal-jabatan').modal('show');
}

function reset_validasi_jabatan(){

}

function form_validation_jabatan() {
	$('#form-jabatan').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_jabatan',
			'input_tmt_jabatan'
		];

		var input_list_error = [
			'input_jabatan_error_icon',
			'input_tmt_jabatan_error_detail'

		];

		$.ajax({
			url: base_url + "jabatankaryawan/validation/",
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSent: function () {
				$('#btn-save-jabatan').attr('disabled', true);
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

							if( input_error == "input_tmt_jabatan_error_detail"){
								$('.gj-icon').addClass('text-danger');
								$('.gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}

						} else {
							$('[id=' + input_error + ']').html('');
							$('[id=' + input_ + '_error_container]').removeClass('has-danger');
							$('[id=' + input_ + '_error_container]').addClass('has-success');
							$('[id=' + input_ + '_error_icon]').text('done');

							if( input_error == "input_tmt_jabatan_error_detail"){
								$('.gj-icon').addClass('text-success');
								$('.gj-icon').addClass('has-success');
								$('[id=' + input_ + '_error_icon]').text('');
							}
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

						if( input_error == "input_tmt_jabatan_error_detail"){
							$('.gj-icon').addClass('text-success');
							$('.gj-icon').addClass('has-success');
							$('[id=' + input_ + '_error_icon]').text('');
						}

					}
					save_jabatan();
				}
				$('#btn-save-jabatan').attr('disabled', false);
			}
		});
	});
}

function save_jabatan() {
	var url;
	if (save_method == "add") {
		url = base_url + "jabatankaryawan/insert/";
	} else {
		url = base_url + "jabatankaryawan/update/";
	}
	var form = $('#form-jabatan')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			$('#form-jabatan')[0].reset();
			$('#modal-jabatan').modal('hide');
			if(data.status){
				load_data_jabatan();
			} else {
				alert('Oups! ada masalah!');
			}
		}
	});
}

function load_data_jabatan(){
	$.ajax({
		url: base_url + "jabatankaryawan/load_data/"+$('#id_karyawan_jabatan').val(),
		method: 'GET',
		success: function (html) {
			$('#table_data_jabatan').html(html);
		}
	});
}

function modal_edit_jabatan(id){
	reset_validasi_jabatan();
  $.ajax({
      type: "GET",
      url : base_url+"jabatankaryawan/detail/"+id,
      dataType: "JSON",
      success: function(data){
        $('[name="id"]').val(data.id );

        $('[name="input_jabatan"]').val(data.id_jabatan );
        $('[name="input_tmt_jabatan"]').val(data.tmt );
				$('[name="input_aktif"]').val(data.is_active );
				$('[name="input_detail_jabatan"]').val(data.detail_jabatan );
				$('.card-title').text("Edit Data Jabatan");
				$('#btn-save-jabatan').text('Update');
        $('#modal-jabatan').modal('show');
        save_method = "edit";
      }
  });
}

function hapus_data_jabatan(id) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "jabatankaryawan/delete/" + id,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					load_data_jabatan();
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}
