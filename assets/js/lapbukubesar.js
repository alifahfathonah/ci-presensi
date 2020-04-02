$(document).ready(function () {
    getdata();
});

function getdata(){
    let bulan = $('#input_bulan').val();
    let tahun = $('#input_tahun').val();
    let parameter = tahun + "-" + bulan;
    $.ajax({
    	url: base_url + "lapbukubesar/getdata/" + parameter,
    	method: 'GET',
    	success: function (html) {
    		$('#content_bukubesar').html(html);
    	}
    });
}

function cetak_laporan() {
	let bulan = $('#input_bulan').val();
	let tahun = $('#input_tahun').val();
	let parameter = tahun + "-" + bulan;
	window.open(base_url + "lapbukubesar/cetak/" + parameter, "_blank");
}