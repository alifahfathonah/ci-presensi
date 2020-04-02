function cari_data(){
    let bulan = $('#input_bulan').val();
    let tahun = $('#input_tahun').val();
    let parameter = tahun+"-"+bulan;
    $.ajax({
        url: base_url + "lapsaldo/getdata/" + parameter ,
    	method: 'GET',
    	success: function (html) {
            $('#content_lap_saldo').html(html);
    	}
    });
}

function cetak_laporan(){
    let bulan = $('#input_bulan').val();
    let tahun = $('#input_tahun').val();
    let parameter = tahun + "-" + bulan;
    window.open(base_url + "lapsaldo/cetak/" + parameter, "_blank");
}