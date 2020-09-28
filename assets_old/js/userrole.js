var table;
var save_method;
$(document).ready(function() {

  table = $('#userrole_table').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"userrole/ajax_list/",
          "type": "POST"
      },

      "columnDefs": [
      {
          "targets": [ -1 ],
          "orderable": false,
      },
      ],

  });

  $("#btn-save").click(function(event) {
      $('#btn-save').text('saving...'); //change button text
      $('#btn-save').attr('disabled', true); //set button disable

      var form = $("#form-userrole")

      event.preventDefault();
      if (form[0].checkValidity() === false) {
        event.stopPropagation();
        $('#btn-save').text('Save');
        $('#btn-save').attr('disabled', false);
      }else {
        var url;

        if(save_method == 'add') {
            url = base_url+"userrole/ajax_add";
        } else {
            url = base_url+"userrole/ajax_update";
        }

        $.ajax({
            url : url,
            type: "POST",
            data: $('#form-userrole').serialize(),
            dataType: "JSON",
            success: function(data){
                console.log(data.redirect);
                if(data.status){
                    $('#modal_add').modal('hide');
                    reload_table();
                }
                $('#btn-save').text('save');
                $('#btn-save').attr('disabled',false);
                if(data.redirect == 1){
                  window.open(base_url+"login/logout", "_self");
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error adding / update data');
                $('#btn-save').text('Save');
                $('#btn-save').attr('disabled',false);
            }
        });
      }
      $('#form-userrole').addClass('was-validated');
  });

});

function reload_table(){
    table.ajax.reload(null,false);
}

function add_menu(){
    $('#form-userrole').removeClass('was-validated');
    save_method = 'add';
    $('#form-userrole')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_add').modal('show');
    $('.modal-title').text('Add New Role');
}

function edit_menu(id){
    save_method = 'update';
    $('#form-userrole')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#form-userrole').removeClass('was-validated');
    $.ajax({
        url : base_url+"userrole/ajax_edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="input_role_name"]').val(data.user_role_name);
            $('#modal_add').modal('show');
            $('.modal-title').text('Edit Role');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function delete_menu(id){
    if(confirm('Are you sure delete this data?')){
        $.ajax({
            url : base_url+"userrole/ajax_delete/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data){
                $('#modal_add').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error deleting data');
            }
        });

    }
}
