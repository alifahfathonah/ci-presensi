
<!-- <div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1>Profil Koperasi</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title" id="txt">
            </div>
        </div>
    </div>
</div> -->

<div class="content flashdata_container">
    <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
  <div class="card" style="border-top: solid 3px #7ea4b3">
    <?php
    $url = $record['url'];
    $form_attributes = array('class' => '', 'id' => 'form_profil');
    echo form_open($url, $form_attributes);
    ?>
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 25px">Detail Profil</h5>
      <hr>
      <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
      <div class="form-group"><label for="company" class=" form-control-label">Nama Koperasi</label>
        <input name="input_nama_koperasi" id="input_nama_koperasi" type="text" placeholder="Masukan nama koperasi" class="form-control" value="<?php echo $record['nama_lembaga']; ?>">
      </div>
      <div class="form-group"><label for="vat" class=" form-control-label">Nama Pimpinan</label>
        <input name="input_nama_pimpinan" id="input_nama_pimpinan" type="text" placeholder="Masukan nama pimpinan" class="form-control" value="<?php echo $record['nama_ketua']; ?>">
      </div>
      <div class="form-group"><label for="street" class=" form-control-label">No HP</label>
        <input type="text" name="input_hp" id="input_hp" placeholder="Masukan no hp" class="form-control" value="<?php echo $record['hp_ketua']; ?>">
      </div>
      <div class="form-group"><label for="street" class=" form-control-label">Alamat</label>
        <input type="text" name="input_alamat" id="input_alamat" placeholder="Masukan alamat" class="form-control" value="<?php echo $record['alamat']; ?>">
      </div>
      <div class="form-group"><label for="street" class=" form-control-label">Telepon</label>
        <input type="text" name="input_telepon" id="input_telepon" placeholder="Masukan telepon" class="form-control" value="<?php echo $record['telepon']; ?>">
      </div>
      <div class="form-group"><label for="street" class=" form-control-label">Kota/Kabupaten</label>
        <input type="text" name="input_kotakabupaten" id="input_kotakabupaten" placeholder="Masukan kota" class="form-control" value="<?php echo $record['kota']; ?>">
      </div>
      <div class="form-group"><label for="street" class=" form-control-label">E-mail</label>
        <input type="text" id="input_email" name="input_email" placeholder="Masukan e-mail" class="form-control" value="<?php echo $record['email']; ?>">
      </div>
      <div class="form-group"><label for="street" class=" form-control-label">Website</label>
        <input type="text" id="input_website" name="input_website" placeholder="Masukan website" class="form-control" value="<?php echo $record['web']; ?>">
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" name="btn_save" id="btn_save" class="btn btn-sm btn-primary">Save</button>
    </div>
    <?php
    echo form_close();
    ?>
  </div>
</div>
