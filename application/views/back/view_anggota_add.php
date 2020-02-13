
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
      <h5 class="card-title display-4" style="font-size: 25px">Tambah Data Anggota</h5>
      <hr>
      <form method="post" class="form-horizontal" id="form_anggota" enctype="multipart/form-data">
        <div class="col-md-6">

          <div class="row form-group">
            <div class="col col-md-4"><label for="text-input" class=" form-control-label">Nama Lengkap* : </label></div>
            <div class="col-12 col-md-8">
              <input type="text" id="input_nama" name="input_nama" placeholder="" class="form-control form-control-sm">
              <small class="form-text text-danger" id="input_nama_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-4"><label for="text-input" class=" form-control-label">Tempat Lahir* : </label></div>
            <div class="col-12 col-md-8">
              <input type="text" id="input_tempat_lahir" name="input_tempat_lahir" placeholder="" class="form-control form-control-sm">
              <small class="form-text text-danger" id="input_tempat_lahir_error"></small>
            </div>
          </div>

          <div class="form-group row">
            <label for="id_input_tanggal" class="control-label col-md-4">Tanggal Lahir* : </label>
            <div class="col-md-8" id="sandbox-container" >
              <div class="input-daterange input-group mb-3" >
                <input readonly type="text" class="form-control form-control-sm" name="input_tanggal_lahir" id="input_tanggal_lahir" required >
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
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
              <small class="form-text text-danger" id="input_jenis_kelamin_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-4"><label for="textarea-input" class=" form-control-label">Alamat* : </label></div>
            <div class="col-12 col-md-8">
              <textarea name="input_alamat" id="input_alamat" rows="3" placeholder="" class="form-control form-control-sm"></textarea>
              <small class="form-text text-danger" id="input_alamat_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-4"><label for="text-input" class=" form-control-label">Kota* : </label></div>
            <div class="col-12 col-md-8">
              <input type="text" id="input_kota" name="input_kota" placeholder="" class="form-control form-control-sm">
              <small class="form-text text-danger" id="input_kota_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-4"><label for="text-input" class=" form-control-label">No Telepon/HP : </label></div>
            <div class="col-12 col-md-8">
              <input type="text" id="input_hp" name="input_hp" placeholder="" class="form-control form-control-sm">
              <small class="form-text text-danger" id="input_kota_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-4"><label for="selectSm" class=" form-control-label">Agama :</label></div>
            <div class="col-12 col-md-8">
              <select name="input_agama" id="input_agama" class="form-control-sm form-control">
                <option value="">-- Pilih --</option>
                <option value="Islam">Islam</option>
                <option value="Katolik">Katolik</option>
                <option value="Protestan">Protestan</option>
                <option value="Hindu">Hindu</option>
                <option value="Budha">Budha</option>
                <option value="Lainnya">Lainnya</option>
              </select>
              <small class="form-text text-danger" id="input_agama_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-4"><label for="selectSm" class=" form-control-label">Status :</label></div>
            <div class="col-12 col-md-8">
              <select name="input_status" id="input_status" class="form-control-sm form-control">
                <option value="">-- Pilih --</option>
                <option value="Belum Kawin">Belum Kawin</option>
                <option value="Kawin">Kawin</option>
                <option value="Cerai Hidup">Cerai Hidup</option>
                <option value="Cerai Mati">Cerai Mati</option>
                <option value="Lainnya">Lainnya</option>
              </select>
              <small class="form-text text-danger" id="input_status_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-4"><label for="file-input" class=" form-control-label">Photo : </label></div>
            <div class="col-12 col-md-8">
              <input type="file" id="imgInp" name="imgInp" class="form-control-file">
            </div>
          </div>

        </div>
        <div class="col-md-6">

          <div class="row form-group">
            <div class="col col-md-5"><label for="text-input" class=" form-control-label">Username* : </label></div>
            <div class="col-12 col-md-7">
              <input type="text" id="input_username" name="input_username" placeholder="" class="form-control form-control-sm">
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
                <input readonly type="text" class="form-control form-control-sm" name="input_tanggal_registrasi" id="input_tanggal_registrasi" required >
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
              <?php echo cmb_dinamis_korporasi('input_korporasi_id','tbl_klien_korporasi','nama_klien','id'); ?>
              <!-- <small class="form-text text-danger" id="input_korporasi_id_error"></small> -->
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Departement :</label></div>
            <div class="col-12 col-md-7">
              <select name="input_departement" id="input_departement" class="form-control-sm form-control">
                <option value="">-- Pilih --</option>
                <option value="Produksi BOPP">Produksi BOPP</option>
                <option value="Produksi Slitting">Produksi Slitting</option>
                <option value="WH">WH</option>
                <option value="QA">QA</option>
                <option value="HRD">HRD</option>
                <option value="GA">GA</option>
                <option value="Purchasing">Purchasing</option>
                <option value="Accounting">Accounting</option>
                <option value="Engineering">Engineering</option>
              </select>
              <small class="form-text text-danger" id="input_departement_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Pekerjaan :</label></div>
            <div class="col-12 col-md-7">
              <?php echo cmb_dinamis('input_pekerjaan','pekerjaan','jenis_kerja','jenis_kerja'); ?>
              <small class="form-text text-danger" id="input_pekerjaan_error"></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Jabatan* :</label></div>
            <div class="col-12 col-md-7">
              <select name="input_jabatan" id="input_jabatan" class="form-control-sm form-control">
                <option value="x">-- Pilih --</option>
                <option value="2">Anggota</option>
                <option value="1">Pengurus</option>
              </select>
              <small class="form-text text-danger" id="input_jabatan_error" ></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-5"><label for="selectSm" class=" form-control-label">Aktif Keanggotaan* :</label></div>
            <div class="col-12 col-md-7">
              <select name="input_aktif" id="input_aktif" class="form-control-sm form-control">
                <option value="x">-- Pilih --</option>
                <option value="Y">Aktif</option>
                <option value="N">Non Aktif</option>
              </select>
              <small class="form-text text-danger" id="input_aktif_error" ></small>
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-5"><label for="textarea-input" class=" form-control-label">Simpanan Pokok : </label></div>
            <div class="col-12 col-md-7"><input type="text" id="input_simpanan_pokok" name="input_simpanan_pokok" placeholder="" class="form-control form-control-sm">
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-5"><label for="textarea-input" class=" form-control-label">Simpanan Wajib : </label></div>
            <div class="col-12 col-md-7"><input type="text" id="input_simpanan_wajib" name="input_simpanan_wajib" placeholder="" class="form-control form-control-sm">
            </div>
          </div>

          <div class="row form-group">
            <div class="col col-md-5"><label for="textarea-input" class=" form-control-label">Simpanan Sukarela : </label></div>
            <div class="col-12 col-md-7"><input type="text" id="input_simpanan_sukarela" name="input_simpanan_sukarela" placeholder="" class="form-control form-control-sm">
            </div>
          </div>

        </div>

      </div>
      <div class="card-footer">
        <button class="btn btn-sm btn-success" type="submit" id="btn_save">Simpan</button>
        <!-- <button class="btn btn-sm btn-success" type="button">Simpan dan Kembali</button> -->
        <button class="btn btn-sm btn-warning" type="button">Batal</button>
      </form>
    </div>
  </div>

  <script type="text/javascript">
  save_method = "add";
  </script>
