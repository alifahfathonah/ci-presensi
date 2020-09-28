<?php $this->session->userdata['page_title'] = "Data Karyawan"; ?>
<div class="col-md-12">
  <div class="card ">
    <div class="card-header card-header-success card-header-text">
      <div class="card-icon">
        <i class="material-icons">content_paste</i>
      </div>
      <h4 class="card-title">Form Tambah Data Karyawan <small class="text-muted">(Semua Kolom Wajib Diisi)</small> </h4>
    </div>

    <div class="card-body ">
      <form method="post" class="form-horizontal" onSubmit="return false;" id="form-karyawan">
        <div class="row">
          <div class="col-md-6">

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_nip">Nomor Induk </label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_nip_error_container">
                  <label class="control-label" id="input_nip_error_detail"></label>
                  <input type="text" class="form-control" id="input_nip" name="input_nip">
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_nip_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_nama_lengkap">Nama Lengkap</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_nama_lengkap_error_container">
                  <label class="control-label" id="input_nama_lengkap_error_detail"></label>
                  <input type="text"  class="form-control" id="input_nama_lengkap" name="input_nama_lengkap" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_nama_lengkap_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_jenis_kelamin">Jenis Kelamin</label>
              <div class="col-sm-8" >
                <div class="form-group label-floating " id="input_jenis_kelamin_error_container" >
                  <!-- <label class="control-label" id="input_jenis_kelamin_error_detail"></label> -->
                  <select class="form-control" id="input_jenis_kelamin" name="input_jenis_kelamin" >
                    <option value="x" selected>-- Silahkan Pilih --</option>
                    <option value="Laki - laki">Laki - laki</option>
                    <option value="Perempuan" >Perempuan</option>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_jenis_kelamin_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_tempat_lahir">Tempat Lahir</label>
              <div class="col-sm-8" >
                <div class="form-group label-floating " id="input_tempat_lahir_error_container" >
                  <!-- <label class="control-label" id="input_tempat_lahir_error_detail"></label> -->
                  <select class="form-control" id="input_tempat_lahir" name="input_tempat_lahir" >
                    <option value="x" selected>-- Silahkan Pilih --</option>

                    <?php
                    foreach ($kabupaten->result() as $r) {
                      echo '<option value="'.$r->id.'">'.$r->name.'</option>';
                    }
                    ?>

                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_tempat_lahir_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_tanggal_lahir">Tanggal Lahir</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_tanggal_lahir_error_container">
                  <label class="control-label" id="input_tanggal_lahir_error_detail"></label>
                  <!-- <input type="text"  class="form-control" id="input_tanggal_lahir" name="input_tanggal_lahir" /> -->
                  <input type="text" class="form-control datetimepicker" id="input_tanggal_lahir" name="input_tanggal_lahir"/>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_tanggal_lahir_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_alamat">Alamat</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_alamat_error_container">
                  <label class="control-label" id="input_alamat_error_detail"></label>
                  <input type="text"  class="form-control" id="input_alamat" name="input_alamat" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_alamat_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_provinsi">Provinsi</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_provinsi_error_container">
                  <!-- <label class="control-label" id="input_provinsi_error_detail"></label> -->
                  <select class="form-control" id="input_provinsi" name="input_provinsi" onchange="get_kota()">
                    <option value="x" selected>-- Silahkan Pilih --</option>

                    <?php
                    foreach ($provinsi->result() as $r) {
                      echo '<option value="'.$r->id.'">'.$r->name.'</option>';
                    }
                    ?>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_provinsi_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_kota">Kota / Kabupaten</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_kota_error_container">
                  <!-- <label class="control-label" id="input_kota_error_detail"></label> -->
                  <select class="form-control" id="input_kota" name="input_kota" >
                    <option value="x" selected>-- Silahkan Pilih Provinsi Dahulu --</option>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_kota_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_no_telp">No Telp</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_no_telp_error_container">
                  <label class="control-label" id="input_no_telp_error_detail"></label>
                  <input type="text"  class="form-control" id="input_no_telp" name="input_no_telp" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_no_telp_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_agama">Agama</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_agama_error_container">
                  <!-- <label class="control-label" id="input_agama_error_detail"></label> -->
                  <select class="form-control" id="input_agama" name="input_agama" >
                    <option value="x" selected>-- Silahkan Pilih --</option>

                    <?php
                    foreach ($agama->result() as $r) {
                      echo '<option value="'.$r->id.'">'.$r->nama_agama.'</option>';
                    }
                    ?>
                  </select>

                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_agama_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

          </div>

          <div class="col-md-6">

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_no_ktp">No KTP</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_no_ktp_error_container">
                  <label class="control-label" id="input_no_ktp_error_detail"></label>
                  <input type="text"  class="form-control" id="input_no_ktp" name="input_no_ktp" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_no_ktp_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_alamat_ktp">Alamat KTP</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_alamat_ktp_error_container">
                  <label class="control-label" id="input_alamat_ktp_error_detail"></label>
                  <input type="text"  class="form-control" id="input_alamat_ktp" name="input_alamat_ktp" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_alamat_ktp_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_golongan_darah">Golongan Darah</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_golongan_darah_error_container">
                  <!-- <label class="control-label" id="input_golongan_darah_error_detail"></label> -->
                  <select class="form-control" id="input_golongan_darah" name="input_golongan_darah" >
                    <option value="x" selected>-- Silahkan Pilih --</option>
                    <option value="A" >A</option>
                    <option value="B" >B</option>
                    <option value="AB" >AB</option>
                    <option value="O" >O</option>
                    <option value="Tidak Diketahui" >Tidak Diketahui</option>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_golongan_darah_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_status_kawin">Status Kawin</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_status_kawin_error_container">
                  <!-- <label class="control-label" id="input_status_kawin_error_detail"></label> -->
                  <select class="form-control" id="input_status_kawin" name="input_status_kawin" >
                    <option value="x" selected>-- Silahkan Pilih --</option>
                    <option value="Belum Kawin" >Belum Kawin</option>
                    <option value="Kawin" >Kawin</option>
                    <option value="Cerai" >Cerai</option>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_status_kawin_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_email">E-mail</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_email_error_container">
                  <label class="control-label" id="input_email_error_detail"></label>
                  <input type="text"  class="form-control" id="input_email" name="input_email" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_email_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_status_karyawan">Status Karyawan</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_status_karyawan_error_container">
                  <!-- <label class="control-label" id="input_status_karyawan_error_detail"></label> -->
                  <select class="form-control" id="input_status_karyawan" name="input_status_karyawan" >
                    <option value="x" selected>-- Silahkan Pilih --</option>
                    <option value="1" >Tetap</option>
                    <option value="2" >Kontrak</option>
                    <option value="3" >Training</option>
                    <option value="4" >Magang</option>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_status_karyawan_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_aktif">Aktif</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_aktif_error_container">
                  <!-- <label class="control-label" id="input_status_karyawan_error_detail"></label> -->
                  <select class="form-control" id="input_aktif" name="input_aktif" >
                    <option value="1" selected >Ya</option>
                    <option value="0" >Tidak</option>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_aktif_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_tanggal_masuk">Tanggal Masuk</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_tanggal_masuk_error_container">
                  <label class="control-label" id="input_tanggal_masuk_error_detail"></label>
                  <!-- <input type="text"  class="form-control" id="input_tanggal_lahir" name="input_tanggal_lahir" /> -->
                  <input type="text" class="form-control datetimepicker" id="input_tanggal_masuk" name="input_tanggal_masuk"/>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_tanggal_lahir_masuk_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_departemen">Departemen</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_departemen_error_container">
                  <!-- <input type="text"  class="form-control" id="input_departemen" name="input_departemen" /> -->
                  <select class="form-control" id="input_departemen" name="input_departemen" >
                    <option value="x" selected>-- Silahkan Pilih --</option>

                    <?php
                    foreach ($departemen->result() as $r) {
                      echo '<option value="'.$r->id.'">'.$r->nama_departemen . ' (Kode: '.$r->kode_departemen.')' .'</option>';
                    }
                    ?>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_departemen_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_lock_area">Lock Area</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_lock_area_error_container">
                  <!-- <label class="control-label" id="input_lock_area_error_detail"></label> -->
                  <select class="form-control" id="input_lock_area" name="input_lock_area" >
                    <option value="x" selected>-- Silahkan Pilih --</option>
                    <option value="1" >Ya</option>
                    <option value="0" >Tidak</option>
                  </select>
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_lock_area_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_gaji_pokok">Gaji Pokok</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_gaji_pokok_error_container">
                  <label class="control-label" id="input_gaji_pokok_error_detail"></label>
                  <input type="text"  class="form-control" id="input_gaji_pokok" name="input_gaji_pokok" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_gaji_pokok_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <label class="col-sm-4 col-form-label" for="input_rekening">No Rekening</label>
              <div class="col-sm-8">
                <div class="form-group label-floating " id="input_rekening_error_container">
                  <label class="control-label" id="input_rekening_error_detail"></label>
                  <input type="text"  class="form-control" id="input_rekening" name="input_rekening" />
                  <span class="form-control-feedback">
                    <i class="material-icons" id="input_rekening_error_icon"></i>
                  </span>
                </div>
              </div>
            </div>

          </div>
        </div>

    </div>
    <div class="card-footer">
      <button type="reset" class="btn btn-outline-secondary btn-sm" id="btn-reset">
          <i class="fa fa-refresh"></i> Reset
      </button>
      <button type="submit" class="btn btn-success btn-sm" id="btn-save">
          <i class="fa fa-save"></i> Save
      </button>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
    save_method = "add";
</script>
