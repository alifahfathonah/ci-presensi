var nama_anggota;
$(document).ready(function () {
    var sample_data = new Bloodhound({
    	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    	queryTokenizer: Bloodhound.tokenizers.whitespace,
    	prefetch: base_url + "Kasanggota/fetch_autocomplete",
    	remote: {
    		url: base_url + "Kasanggota/fetch_autocomplete/%QUERY",
    		wildcard: '%QUERY'
    	}
    });

    var img_url = base_url + "uploads/anggota/";

    $('#input_nama_anggota').typeahead(null, {
    name: 'sample_data',
    display: 'name',
    source: sample_data,
    limit: 10,
    templates: {
    	suggestion: Handlebars.compile(
    		'<div class="row">' +
                '<div class="col-md-2" style="padding-right:5px; padding-left:0px;">' +
                    '<img id="img_cilik_{{base_id}}" src="' + img_url + '{{image}}" class="img-thumbnail" width="48" />' +
                '</div>' +
                '<div class="col-md-10" style="padding-right:5px; padding-left:0px;">{{name}}<br>ID: {{id}}</div>' +
    		'</div>')
    }
    });
})

function lihat_laporan(){
    var input_nama = $('#input_nama_anggota').val();
    nama_anggota = input_nama.split(" - ");
    if (input_nama == ""){
        alert('Silahkan pilih anggota');
    } else{
        $('#input_id_anggota').val(nama_anggota[0]);
        window.open(base_url + "kasanggota/cari/" + nama_anggota[0], '_self');
    }
}

function cetak_laporan(){
    // var input_nama = $this->uri->segment(3);
    window.open(base_url + "kasanggota/cetak/", '_self');
}