$(document).ready(function(e) {
  upload_photo();
  update_profil();
});

$(document).on("click", ".browse", function() {
  var file = $(this)
  .parent()
  .parent()
  .parent()
  .find(".file");
  file.trigger("click");
});
$('input[type="file"]').change(function(e) {
  var fileName = e.target.files[0].name;
  $("#file").val(fileName);

  var reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById("preview").src = e.target.result;
  };
  reader.readAsDataURL(this.files[0]);
});

function upload_photo(){
  $("#image-form").on("submit", function() {
    $("#msg").html('<div class="alert alert-light text-info" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">'+
    '<i class="fa fa-spin fa-spinner"></i> Please wait...!'+
    '</div><br>');
    $.ajax({
      type: "POST",
      url: base_url + "logo/upload_photo",
      data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      contentType: false, // The content type used when sending data to the server.
      cache: false, // To unable request pages to be cached
      processData: false, // To send DOMDocument or non processed data file it is set to false
      success: function(data) {
        console.log(data);
        if (data == 1 || parseInt(data) == 1) {
          $("#msg").html(
            '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">'+
            '<b><i class="fa fa-thumbs-up"></i></b>  Upload logo sukses.'+
            '</div><br>'
          );
        } else {
          $("#msg").html(
            '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">'+
            '<strong><i class="fa fa-exclamation-triangle"></i></strong> Jenis file yang diizinkan : <strong> JPG, PNG, JPEG</strong>.'+
            '</div><br>'
          );
        }
      },
      error: function(data) {
        $("#msg").html(
          '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">'+
          '<strong><i class="fa fa-exclamation-triangle"></i></strong> Oups. Ada masalah!'+
          '</div><br>'
        );
      }
    });
  });
}

function update_profil(){
  $("#profil-form").on("submit", function() {
    $("#msg-profil").html(
      '<div class="alert alert-light text-info" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">'+
      '<i class="fa fa-spin fa-spinner"></i> Please wait...!'+
      '</div><br>'
  );
    $.ajax({
      type: "POST",
      url: base_url + "profil/update_profil",
      data: $(this).serialize(), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      dataType: 'json',
      success: function(data) {
        console.log(data);
        if(data.status){
          $("#msg-profil").html(
            '<div class="alert alert-light text-success" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">'+
            '<b><i class="fa fa-thumbs-up"></i></b> Update Profil Sukses.'+
            '</div><br>'
          );
        } else {
          $("#msg-profil").html(
            '<div class="alert alert-light text-danger" style="margin-bottom: 1px; height: 30px; line-height:30px; padding:0px 15px;">'+
            '<strong><i class="fa fa-exclamation-triangle"></i></strong> Oups. Update Profi Gagal!'+
            '</div><br>'
          );
        }
      }
    });
  });
}
