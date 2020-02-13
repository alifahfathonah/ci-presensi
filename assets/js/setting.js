$(document).ready(function(){

  function load_data()
  {
    $.ajax({
      url: base_url+"setting/load_data",
      dataType:"JSON",
      success:function(data){
        var html = '<tr>';
        // html += '<td id="id" placeholder="Enter First Name"></td>';
        html += '<td id="tanggal" contenteditable ></td>';
        html += '<td id="nominal" contenteditable placeholder="Masukan Nominal Saldo"></td>';
        html += '<td id="jenis_saldo" contenteditable ></td>';
        html += '<td id="keterangan" contenteditable ></td>';
        html += '<td><button type="button" name="btn_add" id="btn_add" class="btn btn-sm btn-success"><span class="fa fa-plus"></span></button></td></tr>';
        for(var count = 0; count < data.length; count++)
        {
          html += '<tr>';
          // html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="id" >'+data[count].id+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="tanggal" contenteditable>'+data[count].tanggal+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="nominal" contenteditable>'+data[count].nominal+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="jenis_saldo" contenteditable>'+data[count].jenis_saldo+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="keterangan" contenteditable>'+data[count].keterangan+'</td>';
          html += '<td><button type="button" name="delete_btn" id="'+data[count].id+'" class="btn btn-sm btn-danger btn_delete"><span class="fa fa-trash"></span></button></td></tr>';
        }
        $('tbody').html(html);
      }
    });
  }

  load_data();

  $(document).on('click', '#btn_add', function(){
    // var first_name = $('#first_name').text();
    var tanggal     = $('#tanggal').text();
    var nominal     = $('#nominal').text();
    var jenis_saldo = $('#jenis_saldo').text();
    var keterangan  = $('#keterangan').text();

    // if(first_name == '')
    // {
    //   alert('Enter First Name');
    //   return false;
    // }
    if(tanggal == '')
    {
      alert('Enter Date');
      return false;
    }
    if(nominal == '')
    {
      alert('Enter Amount');
      return false;
    }
    if(jenis_saldo == '')
    {
      alert('Enter Jenis Saldo');
      return false;
    }
    if(keterangan == '')
    {
      alert('Enter Description');
      return false;
    }
    $.ajax({
      url:base_url+"setting/insert",
      method:"POST",
      data:{tanggal:tanggal, nominal:nominal, jenis_saldo:jenis_saldo, keterangan:keterangan},
      success:function(data){
        load_data();
      }
    })
  });

  $(document).on('blur', '.table_data', function(){
    var id = $(this).data('row_id');
    var table_column = $(this).data('column_name');
    var value = $(this).text();
    $.ajax({
      url:base_url+"setting/update",
      method:"POST",
      data:{id:id, table_column:table_column, value:value},
      success:function(data)
      {
        load_data();
      }
    })
  });

  $(document).on('click', '.btn_delete', function(){
    var id = $(this).attr('id');
    if(confirm("Are you sure you want to delete this?"))
    {
      $.ajax({
        url:base_url+"setting/delete",
        method:"POST",
        data:{id:id},
        success:function(data){
          load_data();
        }
      })
    }
  });

});
