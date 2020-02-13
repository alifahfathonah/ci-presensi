
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
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 25px">Ubah Data Anggota</h5>
      <hr>
      <form method="post" class="form-horizontal" id="form_anggota" enctype="multipart/form-data">
      <div class="col-md-6">

        <input type="hidden" name="id" value="<?php echo $record['id'];?>">
        <div class="row form-group">
          <div class="col col-md-4"><label for="text-input" class=" form-control-label">Nama Lengkap* : </label></div>
          <div class="col-12 col-md-8">
            <input type="text" id="input_nama" name="input_nama" placeholder="" class="form-control form-control-sm" value="<?php echo $record['nama'];?>">
            <small class="form-text text-danger" id="input_nama_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-4"><label for="text-input" class=" form-control-label">Tempat Lahir* : </label></div>
          <div class="col-12 col-md-8">
            <input type="text" id="input_tempat_lahir" name="input_tempat_lahir" placeholder="" class="form-control form-control-sm" value="<?php echo $record['tmp_lahir'];?>">
            <small class="form-text text-danger" id="input_tempat_lahir_error"></small>
          </div>
        </div>

        <div class="form-group row">
          <label for="id_input_tanggal" class="control-label col-md-4">Tanggal Lahir* : </label>
          <div class="col-md-8" id="sandbox-container" >
            <div class="input-daterange input-group mb-3" >
              <input readonly type="text" class="form-control form-control-sm" name="input_tanggal_lahir" id="input_tanggal_lahir" value="<?php echo $record['tgl_lahir'];?>" >
              <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
            <small class="form-text text-danger" id="input_tempat_lahir_error" ></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-4"><label for="selectSm" class=" form-control-label">Jenis Kelamin* : </label></div>
          <div class="col-12 col-md-8">
            <select name="input_jenis_kelamin" id="input_jenis_kelamin" class="form-control-sm form-control">
              <option value="x">-- Pilih --</option>
              <?php
                $jenis_kelamin = $record['jk'];
                $array_jk = array('L' => 'Laki-laki', 'P' => 'Perempuan' );
                foreach ($array_jk as $key => $value) {
                  $selected = ($key == $jenis_kelamin) ? 'selected' : '' ;
                  echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                }
              ?>
            </select>
            <small class="form-text text-danger" id="input_jenis_kelamin_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-4"><label for="textarea-input" class=" form-control-label">Alamat* : </label></div>
          <div class="col-12 col-md-8">
            <textarea name="input_alamat" id="input_alamat" rows="3" placeholder="" class="form-control form-control-sm"><?php echo $record['alamat'];?></textarea>
            <small class="form-text text-danger" id="input_alamat_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-4"><label for="text-input" class=" form-control-label">Kota* : </label></div>
          <div class="col-12 col-md-8">
            <input type="text" id="input_kota" name="input_kota" placeholder="" class="form-control form-control-sm" value="<?php echo $record['kota'];?>">
            <small class="form-text text-danger" id="input_kota_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-4"><label for="text-input" class=" form-control-label">No Telepon/HP : </label></div>
          <div class="col-12 col-md-8">
            <input type="text" id="input_hp" name="input_hp" placeholder="" class="form-control form-control-sm" value="<?php echo $record['notelp'];?>">
            <small class="form-text text-danger" id="input_kota_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-4"><label for="selectSm" class=" form-control-label">Agama :</label></div>
          <div class="col-12 col-md-8">
            <select name="input_agama" id="input_agama" class="form-control-sm form-control">
              <option value="x">-- Pilih --</option>
              <?php
                $agama = $record['agama'];
                $array_agama = array(
                  'Islam'     => 'Islam',
                  'Katolik'   => 'Katolik',
                  'Protestan' => 'Protestan',
                  'Hindu'     => 'Hindu',
                  'Budha'     => 'Budha',
                  'Lainnya'   => 'Lainnya'
                );

                foreach ($array_agama as $key => $value) {
                  $selected = ($key == $agama) ? 'selected' : '' ;
                  echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                }
              ?>
            </select>
            <small class="form-text text-danger" id="input_agama_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-4"><label for="selectSm" class=" form-control-label">Status :</label></div>
          <div class="col-12 col-md-8">
            <select name="input_status" id="input_status" class="form-control-sm form-control">
              <option value="">-- Pilih --</option>
              <?php
                $status = $record['status'];
                $array_stts = array(
                  'Belum Kawin' => 'Belum Kawin',
                  'Kawin' => 'Kawin',
                  'Cerai Hidup' => 'Cerai Hidup',
                  'Cerai Mati' => 'Cerai Mati',
                  'Lainnya' => 'Lainnya'
                );

                foreach ($array_stts as $key => $value) {
                  $selected = ($key == $status) ? 'selected' : '' ;
                  echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                }
              ?>
            </select>
            <small class="form-text text-danger" id="input_status_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-4"><label for="file-input" class=" form-control-label">Photo : </label></div>
          <div class="col-12 col-md-8 ">
            <img src="<?php echo base_url('uploads/anggota/'.$record['file_pic']); ?>" class="img-thumbnail" alt="" style="max-width:25%; height:auto;">
            <input type="hidden" name="old_image" value="<?php echo $record['file_pic'];?>">
            <input type="file" id="imgInp" name="imgInp" class="form-control-file">
          </div>
        </div>

      </div>
      <div class="col-md-6">

        <div class="row form-group">
          <div class="col col-md-5"><label for="text-input" class=" form-control-label">Username* : </label></div>
          <div class="col-12 col-md-7">
            <input type="text" id="input_username" name="input_username" placeholder="" class="form-control form-control-sm" value="<?php echo $record['identitas'];?>">
            <small class="form-text text-danger" id="input_username_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="password-input" class=" form-control-label">Password : </label></div>
          <div class="col-12 col-md-7">
            <input type="password" id="input_password" name="input_password" placeholder="" class="form-control form-control-sm">
            <small class="help-block form-text">Kosongkan password jika tidak ingin ubah/isi</small>
          </div>
        </div>

        <div class="form-group row">
          <label for="id_input_tanggal" class="control-label col-md-5">Tanggal Registrasi* : </label>
          <div class="col-md-7" id="sandbox-container">
            <div class="input-daterange input-group mb-3" id="datepicker" >
              <input readonly type="text" class="form-control form-control-sm" name="input_tanggal_registrasi" id="input_tanggal_registrasi" required value="<?php echo $record['tgl_daftar'];?>">
              <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
            <small class="form-text text-danger" id="input_tanggal_registrasi_error" ></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Korporasi :</label></div>
          <div class="col-12 col-md-7">
            <?php echo cmb_dinamis_korporasi('input_korporasi_id','tbl_klien_korporasi','nama_klien','id', $record['id_korporasi']); ?>
            <!-- <small class="form-text text-danger" id="input_korporasi_id_error"></small> -->
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Departement :</label></div>
          <div class="col-12 col-md-7">
            <select name="input_departement" id="input_departement" class="form-control-sm form-control">
              <option value="">-- Pilih --</option>
              <?php
                $departement = $record['departement'];
                $array_dept = array(
                  'Produksi BOPP' => 'Produksi BOPP',
                  'Produksi Slitting' => 'Produksi Slitting',
                  'WH' => 'WH',
                  'QA' => 'QA',
                  'HRD' => 'HRD',
                  'GA' => 'GA',
                  'Purchasing' => 'Purchasing',
                  'Accounting' => 'Accounting',
                  'Engineering' => 'Engineering'
                );

                foreach ($array_dept as $key => $value) {
                  $selected = ($key == $departement) ? 'selected' : '' ;
                  echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                }
              ?>
            </select>
            <small class="form-text text-danger" id="input_departement_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Pekerjaan :</label></div>
          <div class="col-12 col-md-7">
            <?php echo cmb_dinamis('input_pekerjaan','pekerjaan','jenis_kerja','jenis_kerja', $record['pekerjaan']); ?>
            <small class="form-text text-danger" id="input_pekerjaan_error"></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Jabatan* :</label></div>
          <div class="col-12 col-md-7">
            <select name="input_jabatan" id="input_jabatan" class="form-control-sm form-control">
              <option value="x">-- Pilih --</option>
              <?php
                $jabatan_id = $record['jabatan_id'];
                $array_jabatan_id = array(
                  '1'   => 'Pengurus',
                  '2'   => 'Anggota'
                );

                foreach ($array_jabatan_id as $key => $value) {
                  $selected = ($key == $jabatan_id) ? 'selected' : '' ;
                  echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                }
              ?>
            </select>
            <small class="form-text text-danger" id="input_jabatan_error" ></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Aktif Keanggotaan* :</label></div>
          <div class="col-12 col-md-7">
            <select name="input_aktif" id="input_aktif" class="form-control-sm form-control">
              <option value="x">-- Pilih --</option>
              <?php
                $aktif = $record['aktif'];
                $array_aktif = array(
                  'Y'   => 'Aktif',
                  'N'   => 'Non Aktif'
                );

                foreach ($array_aktif as $key => $value) {
                  $selected = ($key == $aktif) ? 'selected' : '' ;
                  echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                }
              ?>
              <!-- <option value="Y">Aktif</option>
              <option value="N">Non Aktif</option> -->
            </select>
            <small class="form-text text-danger" id="input_aktif_error" ></small>
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="textarea-input" class=" form-control-label">Simpanan Pokok : </label></div>
          <div class="col-12 col-md-7"><input type="text" id="input_simpanan_pokok" name="input_simpanan_pokok" placeholder="" class="form-control form-control-sm" value="<?php echo $record['simpanan_pokok'];?>">
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="textarea-input" class=" form-control-label">Simpanan Wajib : </label></div>
          <div class="col-12 col-md-7"><input type="text" id="input_simpanan_wajib" name="input_simpanan_wajib" placeholder="" class="form-control form-control-sm" value="<?php echo $record['simpanan_wajib'];?>">
          </div>
        </div>

        <div class="row form-group">
          <div class="col col-md-5"><label for="textarea-input" class=" form-control-label">Simpanan Sukarela : </label></div>
          <div class="col-12 col-md-7"><input type="text" id="input_simpanan_sukarela" name="input_simpanan_sukarela" placeholder="" class="form-control form-control-sm" value="<?php echo $record['simpanan_sukarela'];?>">
          </div>
        </div>

      </div>
    </div>
    <div class="card-footer">
      <button class="btn btn-sm btn-success" type="submit" id="btn_update" >Update</button>
      <!-- <button class="btn btn-sm btn-success" type="button" id="btn_update_back" >Update dan Kembali</button> -->
      <button class="btn btn-sm btn-warning" type="button" id="btn_update_batal" >Batal</button>
      </form>
    </div>
  </div>

  <script type="text/javascript">
    save_method = "update";
  </script>
