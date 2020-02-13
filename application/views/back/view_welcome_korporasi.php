<div class="content mt-3">

  <div class="alert alert-success" role="alert">
  <!-- <h4 class="alert-heading">Well done!</h4> -->
  <h1 class="alert-heading display-1" style="font-size: 25px; ">Selamat Datang!</h1>
  <p>Hai, <?php echo ucfirst($this->session->userdata('username')); ?>, Anda sedang berada dalam halaman korporasi:</p>
  <h1 style="font-size: 50px" class="display-3"><?php echo $detail_korporasi['nama_klien'] ?></h1>
  <hr>
  <p class="mb-0">Silahkan pilih menu disamping untuk mengoperasikan aplikasi</p>
</div>
</div>
