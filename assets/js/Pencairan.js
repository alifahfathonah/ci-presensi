var table;
$(document).ready(function(){

  table = $('#table_pencairan').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"pencairan/ajax_list/",
          "type": "POST"
      },

      "columnDefs": [
      {
          "targets": [ -1 ],
          "orderable": false,
      },
      ],

  });

  // $('#table_pencairan').DataTable({
  //   responsive: true,
  //   autoWidth: false,
  //   processing: true,
  //   serverSide: true,
  //   columns: [
  //     // {'data': 'id'},
  //     {'data': 'tanggal_cair'},
  //     {'data': 'pengirim'},
  //     {'data': 'pekerjaan'},
  //     {'data': 'datel'},
  //     {'data': 'nominal'},
  //   ],
  //   ajax: {
  //     url: base_url+"Pencairan/datatable/", type: 'post',
  //   }
  // });

});


$('#sandbox-container .input-daterange').datepicker({
   format: 'yyyy-mm-dd',
   todayHighlight: true,
   language: "id"
});


function hapus_pencairan(id){
  console.log(id);
  if(confirm('Apakah anda yakin akan menghapus data ini?')){
    $.ajax({
        type: "GET",
        url : base_url+"pencairan/delete/"+id,
        success: function(data){
          window.location.href = base_url+"pencairan/";
        }
    });
  }
}

$( '#input_nominal' ).mask('000.000.000.000.000', {reverse: true});
