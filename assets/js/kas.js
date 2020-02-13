function getDataKasBantuPeriode(){
  var bulan = $('#input_bulan').val();
  var tahun = $('#input_tahun').val();
  $('#kas_bantu_kontainer').html("Processing...");
  $.ajax({
      type: "GET",
      url : base_url+"kas/renderPageKasPeriode/"+bulan+"/"+tahun,
      success: function(html){
        $('#kas_bantu_kontainer').html(html);
      }
  });
}

$('#sandbox-container .input-daterange').datepicker({
   format: 'yyyy-mm-dd',
   todayHighlight: true,
   language: "id"
});

function open_edit_form(id){
  window.location.href = base_url+"kas/edit/"+id;
}

function hapus_kas(id){
  if(confirm('Apakah anda yakin akan menghapus data ini?')){
    $.ajax({
        type: "GET",
        url : base_url+"kas/delete/"+id,
        success: function(data){
          window.location.href = base_url+"kas/";
        }
    });
  }
}

$( '#input_nominal_debet' ).mask('000.000.000.000.000', {reverse: true});
$( '#input_nominal_kredit' ).mask('000.000.000.000.000', {reverse: true});
