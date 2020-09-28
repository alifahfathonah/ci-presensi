var table;
var save_method;
$(document).ready(function() {
  $('#alert_username').hide();
  $('#alert_password').hide();

  table = $('#user_table').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"user/ajax_list/",
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

      var form = $("#form-user")

      event.preventDefault();
      if (form[0].checkValidity() === false) {
        event.stopPropagation();
        $('#btn-save').text('Save');
        $('#btn-save').attr('disabled', false);
      }else {
        var url;

        if(save_method == 'add') {
            url = base_url+"user/ajax_add";
        } else {
            url = base_url+"user/ajax_update";
        }

        $.ajax({
            url : url,
            type: "POST",
            data: $('#form-user').serialize(),
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
      $('#form-user').addClass('was-validated');
  });

   $('#id_input_username').keyup(function(){
     var usercheck = $(this).val();
     var url = base_url+"user/ajax_check_username/";

     $.post(url, {user_name: usercheck}, function(data){
       if (data.status == true){
         $('#alert_username').show();
         $('#btn-save').attr('disabled',true);
       } else {
         $('#alert_username').hide();
         $('#btn-save').attr('disabled',false);
       }
     },'json');

   });

   $('#id_input_password_ulang').keyup(function(){
     var Password   = $('#id_input_password').val();
     var rePassword = $(this).val();
     var result     = rePassword.localeCompare(Password);
     if(result != 0){
       $('#alert_password').show();
       $('#btn-save').attr('disabled',true);
     }else {
       $('#alert_password').hide();
       $('#btn-save').attr('disabled',false);
     }

   });

});

function reload_table(){
    table.ajax.reload(null,false);
}

function add_menu(){
    document.getElementById("id_input_password").required = true;
    document.getElementById("id_input_password_ulang").required = true;
    $('#form-user').removeClass('was-validated');
    save_method = 'add';
    $('#form-user')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_add').modal('show');
    $('.modal-title').text('Add New User');
}

function edit_menu(id){
    save_method = 'update';
    $('#form-user')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#form-user').removeClass('was-validated');
    $.ajax({
        url : base_url+"user/ajax_edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="input_name"]').val(data.name);
            $('[name="input_username"]').val(data.username);
            $('[name="input_user_role"]').val(data.user_role);
            document.getElementById("id_input_password").required = false;
            document.getElementById("id_input_password_ulang").required = false;
            $('#modal_add').modal('show');
            $('.modal-title').text('Edit User');
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
            url : base_url+"user/ajax_delete/" + id,
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
