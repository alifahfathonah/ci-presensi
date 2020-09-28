<?php $this->session->userdata['page_title'] = "Data Karyawan"; ?>
<div class="row">
  <div class="col-md-3">
    <div class="card card-profile ml-auto mr-auto" style="max-width: 360px">
      <div class="card-header card-header-image" onclick="open_modal_upload()">
        <a href="javascript:;">
          <?php
          if($record['image_path'] == null){
            $img_path = base_url('uploads/no_image.png');
          } else {
            $img_path = base_url('uploads/photo_profil/'.$record['image_path']);
          }
          ?>
          <img id="profil_img" class="img"  src="<?php echo $img_path; ?>">
        </a>
      </div>
      <?php
            $display = "none";
            if($record['image_path'] != null){
              $display = "";
            }
       ?>
      <button id="btn-hapus-foto-profil" style="display: <?php echo $display; ?>" type="button" name="button" class="btn btn-sm btn-link text-danger" onclick="hapus_foto_profil('<?php echo $record['id']; ?>')">Hapus Foto</button>
      <div class="card-body ">
        <?php
        if($record['is_active'] == 1){
          $status_karyawan = "text-success";
          $status_karyawan_title = "Karyawan Aktif";
        } else {
          $status_karyawan = "";
          $status_karyawan_title = "Karyawan Non Aktif";
        }
        ?>

        <h4 class="card-title">
          <i class="material-icons <?php echo $status_karyawan; ?> " title="<?php echo $status_karyawan_title; ?>" >verified_user</i>
          <?php echo $record['nama_lengkap']; ?>
        </h4>
        <!-- <h6 class="card-category text-gray"><?php echo $record['jabatan']; ?></h6> -->
      </div>
      <div class="card-footer justify-content-center">
        <?php
        $awal  = date_create($record['tanggal_masuk']);
        $akhir = date_create(); // waktu sekarang
        $diff  = date_diff( $awal, $akhir );
        ?>
        <p for="">Masa kerja <?php echo $diff->y . " Tahun " . $diff->m . " Bulan " . $diff->d . " Hari"; ?></p>
      </div>
    </div>
    <a style="width: 100%" role="button" name="button" class="btn btn-info" href="<?php echo base_url('kehadiran/search/'.$record['id']); ?>"><b class="fa fa-calendar"></b> Data Kehadiran</a>
  </div>
  <div class="col-lg-9">
    <div class="card">
      <div class="card-header card-header-tabs card-header-rose">
        <div class="nav-tabs-navigation">
          <div class="nav-tabs-wrapper">
            <ul class="nav nav-tabs" data-tabs="tabs">
              <li class="nav-item">
                <a class="nav-link active" href="#profile" data-toggle="tab">
                  <i class="material-icons">person</i> Profil
                  <div class="ripple-container"></div>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#jabatan" data-toggle="tab" onclick="load_data_jabatan()">
                  <i class="material-icons">work</i> Jabatan
                  <div class="ripple-container"></div>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#username" data-toggle="tab">
                  <i class="material-icons">login</i> Username
                  <div class="ripple-container"></div>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#keluarga" data-toggle="tab" onclick="load_data_keluarga()">
                  <i class="material-icons">family_restroom</i> Data Keluarga
                  <div class="ripple-container"></div>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#pendidikan" data-toggle="tab">
                  <i class="material-icons">book</i> Riwayat Pendidikan
                  <div class="ripple-container"></div>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#kerja" data-toggle="tab" onclick="load_data_kerja()">
                  <i class="material-icons">work</i> Pengalaman Kerja
                  <div class="ripple-container"></div>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#file" data-toggle="tab">
                  <i class="material-icons">attach_file</i> File
                  <div class="ripple-container"></div>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div class="tab-pane active" id="profile">
            <?php echo $this->session->flashdata('message'); ?>
            <table class="table table-striped" style="max-width:100%" >
              <tbody>
                <?php $font_size = 15; ?>
                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Nomor Induk Karyawan</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['nip']; ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Nama Lengkap</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['nama_lengkap']; ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Jenis Kelamin</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['jenis_kelamin']; ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Tempat, Tanggal Lahir</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo ucwords($record['tempat_lahir']) . ", " . formatTglIndo($record['tanggal_lahir']) ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Alamat</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['alamat'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Kota / Kabupaten, Provinsi</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['kota'] . ', ' .$record['provinsi'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Agama</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['agama'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Nomor Telepon / HP</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['no_telp'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Email</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['email'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Nomor KTP</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['no_ktp'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Alamat KTP</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['alamat_ktp'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Golongan Darah</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['golongan_darah'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Status Perkawinan</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['status_kawin'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Status Karyawan</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left">
                  <?php
                  $object = array('1' => 'Tetap', '2' => 'Kontrak', '3' => 'Training', '4' => 'Magang');
                  // echo ($record['status_karyawan'] == 1) ? "Aktif" : "Tidak Aktif"
                  echo ($record['status_karyawan'] !== "0") ? $object[$record['status_karyawan']] : '' ;
                  ?>
                </td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Tanggal Masuk</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo formatTglIndo($record['tanggal_masuk']) ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Departemen, Jabatan</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['departemen']. ', '.$record['jabatan'] ?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Gaji Pokok</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo rupiah($record['gaji_pokok'] )?></td>
                </tr>

                <tr style="line-height: 5px; min-height: 5px; height: 5px;">
                  <td widtd="40%" style="font-size: <?php echo $font_size; ?>px;">Nomor Rekening</td>
                  <td widtd="2%">:</td>
                  <td widtd="58%" class="text-left"><?php echo $record['rekening'] ?></td>
                </tr>

              </tbody>
            </table>
            <button class="btn btn-danger btn-sm btn-link" onclick="hapus_data_karyawan('<?php echo $record['id']; ?>')" ><b class="fa fa-trash"></b> Hapus Data</button>
            <a role="button" name="btn-edit" class="btn btn-sm btn-link btn-warning pull-right" href="<?php echo base_url('karyawan/edit/'.$record['id']); ?>">
              <b class="fa fa-edit"></b> Edit Profil
            </a>
          </div>

          <div class="tab-pane " id="jabatan">
            <button type="button" name="btn-add-family" class="btn btn-sm btn-secondary text-dark" onclick="open_modal_jabatan()"><b class="fa fa-plus"></b> Tambah</button>
            <table class="table table-striped table-small small">
              <thead>
                <?php $font_size = 13; ?>
                <tr >
                  <th style="font-size: <?php echo $font_size; ?>px;">No.</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Nama Jabatan</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Detail Jabatan</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">TMT</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Status</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">#</th>
                </tr>
              </thead>
              <tbody id="table_data_jabatan">

              </tbody>
            </table>
          </div>

          <div class="tab-pane " id="keluarga">
            <button type="button" name="btn-add-family" class="btn btn-sm btn-secondary text-dark" onclick="open_modal_keluarga()"><b class="fa fa-plus"></b> Tambah</button>
            <table class="table table-striped table-small small">
              <thead>
                <?php $font_size = 13; ?>
                <tr >
                  <th style="font-size: <?php echo $font_size; ?>px;">No.</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Nama</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Hub.</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Tempat, Tanggal Lahir</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Pekerjaan</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">No. Telp</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">#</th>
                </tr>
              </thead>
              <tbody id="table_data_keluarga">

              </tbody>
            </table>
          </div>

          <div class="tab-pane " id="pendidikan">
            <div class="row">
              <div class="col-9" style="border: 1px solid none">
                <h4>Pendidikan Terakhir</h4>
              </div>
              <div class="col-3 text-right"  style="border: 1px solid none">
                <button type="submit" class="btn btn-warning btn-sm" id="btn-enable-edit-keluarga" onclick="enable_edit_pendidikan()">Enable Edit</button>
              </div>
            </div>
            <form method="post" id="form-pendidikan" onsubmit="return: false;">

              <input type="hidden" name="id_karyawan_pendidikan" id="id_karyawan_pendidikan" value="<?php echo ($riwayat_pendidikan['id_karyawan'] != "") ? $riwayat_pendidikan['id_karyawan'] : "" ; ?>">

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_tingkat_pendidikan">Tingkat Pendidikan </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_tingkat_pendidikan_error_container">
                    <select class="form-control" id="input_tingkat_pendidikan" name="input_tingkat_pendidikan" disabled>
                      <option value="x" selected>-- Silahkan Pilih --</option>
                      <?php
                      foreach ($pendidikan->result() as $r) {
                        $selected = ($riwayat_pendidikan['id_level_pendidikan'] == $r->id) ? "selected" : "" ;
                        echo '<option '.$selected.' value="'.$r->id.'">'.$r->nama_pendidikan.'</option>';
                      }
                      ?>
                    </select>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tingkat_pendidikan_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_sekolah">Sekolah / Universitas </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_sekolah_error_container">
                    <label class="control-label" id="input_sekolah_error_detail"></label>
                    <input type="text" class="form-control" id="input_sekolah" name="input_sekolah" disabled
                    value="<?php echo ($riwayat_pendidikan['asal_sekolah_univ'] != "") ? $riwayat_pendidikan['asal_sekolah_univ'] : "" ; ?>">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_sekolah_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_kota_pendidikan">Kota </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_kota_pendidikan_error_container">
                    <label class="control-label" id="input_kota_pendidikan_error_detail"></label>
                    <input type="text" class="form-control" id="input_kota_pendidikan" name="input_kota_pendidikan" disabled
                    value="<?php echo ($riwayat_pendidikan['kota'] != "") ? $riwayat_pendidikan['kota'] : "" ; ?>">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_kota_pendidikan_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_gelar_pendidikan">Gelar Pendidikan </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_gelar_pendidikan_error_container">
                    <label class="control-label" id="input_gelar_pendidikan_error_detail"></label>
                    <input type="text" class="form-control" id="input_gelar_pendidikan" name="input_gelar_pendidikan" disabled
                    value="<?php echo ($riwayat_pendidikan['gelar'] != "") ? $riwayat_pendidikan['gelar'] : "" ; ?>">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_gelar_pendidikan_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_fakultas">Fakultas </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_fakultas_error_container">
                    <label class="control-label" id="input_fakultas_error_detail"></label>
                    <input type="text" class="form-control" id="input_fakultas" name="input_fakultas" disabled
                    value="<?php echo ($riwayat_pendidikan['fakultas'] != "") ? $riwayat_pendidikan['fakultas'] : "" ; ?>">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_fakultas_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_jurusan">Jurusan </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_jurusan_error_container">
                    <label class="control-label" id="input_jurusan_error_detail"></label>
                    <input type="text" class="form-control" id="input_jurusan" name="input_jurusan" disabled
                    value="<?php echo ($riwayat_pendidikan['jurusan'] != "") ? $riwayat_pendidikan['jurusan'] : "" ; ?>">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_jurusan_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_tahun_masuk">Tahun Masuk </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_tahun_masuk_error_container">
                    <label class="control-label" id="input_tahun_masuk_error_detail"></label>
                    <input type="text" class="form-control" id="input_tahun_masuk" name="input_tahun_masuk" disabled
                    value="<?php echo ($riwayat_pendidikan['tahun_masuk'] != "") ? $riwayat_pendidikan['tahun_masuk'] : "" ; ?>">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tahun_masuk_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_tahun_lulus">Tahun Lulus </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_tahun_lulus_error_container">
                    <label class="control-label" id="input_tahun_lulus_error_detail"></label>
                    <input type="text" class="form-control" id="input_tahun_lulus" name="input_tahun_lulus" disabled
                    value="<?php echo ($riwayat_pendidikan['tahun_lulus'] != "") ? $riwayat_pendidikan['tahun_lulus'] : "" ; ?>">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tahun_lulus_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_nilai">Nilai / IPK </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_nilai_error_container">
                    <label class="control-label" id="input_nilai_error_detail"></label>
                    <input type="text" class="form-control" id="input_nilai" name="input_nilai" disabled
                    value="<?php echo ($riwayat_pendidikan['ipk_nilai'] != "") ? $riwayat_pendidikan['ipk_nilai'] : "" ; ?>">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_nilai_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-12 text-right">
                <button type="button" class="btn btn-secondary btn-sm" id="btn-batal-pendidikan" onclick="reset_edit_pendidikan()" data-dismiss="modal" style="display: none"><b class="fa fa-close" ></b> Batal</button>
                <button type="submit" class="btn btn-success btn-sm" id="btn-save-pendidikan"  style="display: none">Update</button>
              </div>
            </form>
          </div>

          <div class="tab-pane " id="kerja">
            <button type="button" name="btn-add-family" class="btn btn-sm btn-secondary text-dark" onclick="open_modal_kerja()"><b class="fa fa-plus"></b> Tambah</button>
            <table class="table table-striped table-small small">
              <thead>
                <?php $font_size = 13; ?>
                <tr >
                  <th style="font-size: <?php echo $font_size; ?>px;">No.</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Nama Perusahaan</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Bidang</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Jabatan</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Kota</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">Masa Kerja</th>
                  <th style="font-size: <?php echo $font_size; ?>px;">#</th>
                </tr>
              </thead>
              <tbody id="table_data_kerja">

              </tbody>
            </table>
          </div>

          <div class="tab-pane " id="username">
            <div id="alert-password-message">

            </div>
            <form class="form-horizontal" id="form-password">
              <input type="hidden" name="id_username" value="<?php echo $username['id']; ?>">
              <input type="hidden" name="input_id_karyawan" value="<?php echo $record['id']; ?>">
              <input type="hidden" value="<?php echo $username['username']; ?>" id="input_username_old" name="input_username_old">
              <div class="row">
                <label class="col-md-3 col-form-label" for="input_username">Username</label>
                <div class="col-md-9">
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" class="form-control" value="<?php echo $username['username']; ?>" id="input_username" name="input_username">
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-md-3 col-form-label" for="input_password">Password</label>
                <div class="col-md-9">
                  <div class="form-group bmd-form-group">
                    <input type="password" class="form-control" value="" placeholder="Isi jika ingin mengganti password" id="input_password" name="input_password">
                    <small id="label_info" class="form-text text-muted">Password default: tahun-bulan-tanggal, contoh: 2020-02-12</small>
                  </div>
                </div>
              </div>
              <div class="row">
                <label class="col-sm-3 col-form-label" for="input_hak_akses">Hak Akses</label>
                <div class="col-sm-9" >
                  <div class="form-group label-floating " >
                    <select class="form-control" id="input_hak_akses" name="input_hak_akses" >

                      <?php
                      $data = array("1" => "Administrator", "2" => "Kepala Departemen", "3" => "Staff" );
                      foreach ($data as $key => $value) {
                        $selected = ($username['id_hak_akses'] == $key) ? "Selected" : "" ;
                        echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                      }
                      ?>

                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <button type="button" name="btn-reset-password" class="btn btn-sm btn-link" onclick="reset_password()">Reset Password</button>
                </div>
                <div class="col-md-9 text-right">
                  <button type="button" name="btn-update-password" class="btn btn-sm btn-warning" onclick="update_password()">Update</button>
                </div>
              </div>

            </form>
          </div>

          <div class="tab-pane " id="file">
            <div class="row">
              <div class="col-md-6 ml-auto mr-auto">
                <?php
                if($attachment_ktp == null){
                  $img_path_ktp = base_url('uploads/no_file.png');
                } else {
                  $img_path_ktp = base_url('uploads/attachment/'.$attachment_ktp);
                }
                ?>
                <div class="card" >
                  <img class="card-img-top" src="<?php echo $img_path_ktp; ?>" alt="Card image cap" id="img_ktp" >
                  <div class="card-body text-center">
                    <h4 class="card-title" style="font-size: 15px">Kartu Tanda Penduduk</h4>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-success btn-link" title="Upload file" onclick="open_modal_upload(2)"><i class="material-icons">cloud_upload</i></button>
                      <?php
                      $img_container = "img_ktp";
                      echo '<div id="btn-hps-foto-container_'.$img_container.'">';
                      if($attachment_ktp != null){
                        echo '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $attachment_ktp.'\' , \'' . $img_container . '\')"><i class="material-icons">clear</i></button>';
                      }
                      echo '</div>';
                      ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 ml-auto mr-auto">
                <?php
                if($attachment_kk == null){
                  $img_path_kk = base_url('uploads/no_file.png');
                } else {
                  $img_path_kk = base_url('uploads/attachment/'.$attachment_kk);
                }
                ?>
                <div class="card" >
                  <img class="card-img-top" src="<?php echo $img_path_kk; ?>" alt="Card image cap" id="img_kk" >
                  <div class="card-body text-center">
                    <h4 class="card-title" style="font-size: 15px">Kartu Keluarga</h4>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-success btn-link" onclick="open_modal_upload(3)"><i class="material-icons">cloud_upload</i></button>
                      <?php
                      $img_container = "img_kk";
                      echo '<div id="btn-hps-foto-container_'.$img_container.'">';
                      if($attachment_kk != null){
                        echo '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $attachment_kk.'\' , \'' . $img_container . '\')"><i class="material-icons">clear</i></button>';
                      }
                      echo '</div>';
                      ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 ml-auto mr-auto">
                <?php
                if($attachment_ijazah == null){
                  $img_path_ijazah = base_url('uploads/no_file.png');
                } else {
                  $img_path_ijazah = base_url('uploads/attachment/'.$attachment_ijazah);
                }
                ?>
                <div class="card" >
                  <img class="card-img-top" src="<?php echo $img_path_ijazah; ?>" alt="Card image cap" id="img_ijazah" >
                  <div class="card-body text-center">
                    <h4 class="card-title" style="font-size: 15px">Ijazah</h4>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-success btn-link" onclick="open_modal_upload(4)"><i class="material-icons">cloud_upload</i></button>
                      <?php
                      $img_container = "img_ijazah";
                      echo '<div id="btn-hps-foto-container_'.$img_container.'">';
                      if($attachment_ijazah != null){
                        echo '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $attachment_ijazah.'\' , \'' . $img_container . '\')"><i class="material-icons">clear</i></button>';
                      }
                      echo '</div>';
                      ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 ml-auto mr-auto">
                <?php
                if($attachment_transkrip == null){
                  $img_path_transkrip= base_url('uploads/no_file.png');
                } else {
                  $img_path_transkrip = base_url('uploads/attachment/'.$attachment_transkrip);
                }
                ?>
                <div class="card" >
                  <img class="card-img-top" src="<?php echo $img_path_transkrip; ?>" alt="Card image cap" id="img_transkrip" >
                  <div class="card-body text-center">
                    <h4 class="card-title" style="font-size: 15px">Transkrip Nilai</h4>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-success btn-link" onclick="open_modal_upload(5)"><i class="material-icons">cloud_upload</i></button>
                      <?php
                      $img_container = "img_transkrip";
                      echo '<div id="btn-hps-foto-container_'.$img_container.'">';
                      if($attachment_transkrip != null){
                        echo '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $attachment_transkrip.'\' , \'' . $img_container . '\')"><i class="material-icons">clear</i></button>';
                      }
                      echo '</div>';
                      ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 ml-auto mr-auto">
                <?php
                if($attachment_cv == null){
                  $img_path_cv = base_url('uploads/no_file.png');
                } else {
                  $img_path_cv = base_url('uploads/attachment/'.$attachment_cv);
                }
                ?>
                <div class="card" >
                  <img class="card-img-top" src="<?php echo $img_path_cv; ?>" alt="Card image cap" id="img_cv" >
                  <div class="card-body text-center">
                    <h4 class="card-title" style="font-size: 15px">Curiculum Vitae</h4>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-sm btn-success btn-link" onclick="open_modal_upload(6)"><i class="material-icons">cloud_upload</i></button>
                      <?php
                      $img_container = "img_cv";
                      echo '<div id="btn-hps-foto-container_'.$img_container.'">';
                      if($attachment_cv != null){
                        echo '<button type="button" class="btn btn-sm btn-danger btn-link" title="Hapus file" onclick="hapus_file_attachment(\''. $attachment_cv.'\' , \'' . $img_container . '\')"><i class="material-icons">clear</i></button>';
                      }
                      echo '</div>';
                      ?>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-upload" aria-hidden="true" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">cloud_upload</i>
            </div>
            <h4 class="card-title" id="judul-modal-upload">Upload Foto Profil</h4>
          </div>

          <div class="card-body ">
            <form method="POST" id="form-upload" onsubmit="return: false;">
              <input type="hidden" name="id_karyawan_file" id="id_karyawan_file" value="<?php echo $record['id']; ?>">
              <input type="hidden" name="is_attachment" id="is_attachment" value="0">
              <input type="hidden" name="id" id="id" >
              <div class="text-center">
                <div class="input-group" >
                  <span class="input-group-btn" style="border: 1px solid none; margin: 0; padding: 0">
                    <span class="btn btn-info btn-file" style="margin: 0; height: 36px;">
                      Cari... <input class="form-control" type="file" id="imgInp" name="imgInp" >
                    </span>
                  </span>
                  <input type="text" class="form-control" readonly style="border: 1px solid none;" >
                </div>
                <p class="text-muted">File : JPG/JPEG/PNG, Max 1mb</p>
                <hr>
                <img id='img-upload' style="max-width: 50%"/>
              </div>
            </div>
            <div class="card-footer ">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="button" class="btn btn-success btn-sm" id="btn-save-upload" onclick="upload_file()">Upload</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-keluarga" aria-hidden="true" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">content_paste</i>
            </div>
            <h4 class="card-title">Tambah Data Keluarga</h4>
          </div>

          <div class="card-body ">
            <form method="post" id="form-keluarga" onsubmit="return: false;">
              <input type="hidden" name="id_karyawan" id="id_karyawan" value="<?php echo $record['id']; ?>">
              <input type="hidden" name="id" id="id">
              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_nama_lengkap_keluarga">Nama Lengkap </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_nama_lengkap_keluarga_error_container">
                    <label class="control-label" id="input_nama_lengkap_keluarga_error_detail"></label>
                    <input type="text" class="form-control" id="input_nama_lengkap_keluarga" name="input_nama_lengkap_keluarga">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_nama_lengkap_keluarga_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_hubungan_keluarga">Hubungan Keluarga</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_hubungan_keluarga_error_container">
                    <select class="form-control" id="input_hubungan_keluarga" name="input_hubungan_keluarga" >
                      <option value="x" selected>-- Silahkan Pilih --</option>
                      <?php
                      $object = array(
                        '1' => 'Bapak',
                        '2' => 'Ibu',
                        '3' => 'Suami',
                        '4' => 'Istri',
                        '5' => 'Anak',
                        '6' => 'Saudara/i'
                      );
                      foreach ($object as $r => $value) {
                        $selected = "";
                        echo '<option '.$selected.' value="'.$r.'">'.$value.'</option>';
                      }
                      ?>
                    </select>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_hubungan_keluarga_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_tempat_lahir_keluarga">Tempat Lahir</label>
                <div class="col-sm-8" >
                  <div class="form-group label-floating " id="input_tempat_lahir_keluarga_error_container" >
                    <!-- <label class="control-label" id="input_tempat_lahir_error_detail"></label> -->
                    <select class="form-control" id="input_tempat_lahir_keluarga" name="input_tempat_lahir_keluarga" >
                      <option value="x" selected>-- Silahkan Pilih --</option>

                      <?php
                      foreach ($kabupaten->result() as $r) {
                        echo '<option value="'.$r->id.'">'.$r->name.'</option>';
                      }
                      ?>

                    </select>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tempat_lahir_keluarga_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_tanggal_lahir_keluarga">Tanggal Lahir</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_tanggal_lahir_keluarga_error_container">
                    <label class="control-label" id="input_tanggal_lahir_keluarga_error_detail"></label>
                    <input type="text" class="form-control datetimepicker" id="input_tanggal_lahir_keluarga" name="input_tanggal_lahir_keluarga"/>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tanggal_lahir_keluarga_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_pekerjaan_keluarga">Pekerjaan </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_pekerjaan_keluarga_error_container">
                    <label class="control-label" id="input_pekerjaan_keluarga_error_detail"></label>
                    <input type="text" class="form-control" id="input_pekerjaan_keluarga" name="input_pekerjaan_keluarga">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_pekerjaan_keluarga_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_no_telp_keluarga">No. Telp </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_no_telp_keluarga_error_container">
                    <label class="control-label" id="input_no_telp_keluarga_error_detail"></label>
                    <input type="text" class="form-control" id="input_no_telp_keluarga" name="input_no_telp_keluarga">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_no_telp_keluarga_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

            </div>
            <div class="card-footer ">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="submit" class="btn btn-success btn-sm" id="btn-save-keluarga">Tambah</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-kerja" aria-hidden="true" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">content_paste</i>
            </div>
            <h4 class="card-title">Tambah Data Pengalaman Kerja</h4>
          </div>

          <div class="card-body ">
            <form method="post" id="form-kerja" onsubmit="return: false;">
              <input type="hidden" name="id_karyawan_kerja" id="id_karyawan_kerja" value="<?php echo $record['id']; ?>">
              <input type="hidden" name="id_kerja" id="id_kerja">
              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_nama_perusahaan">Nama Perusahaan </label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_nama_perusahaan_error_container">
                    <label class="control-label" id="input_nama_perusahaan_error_detail"></label>
                    <input type="text" class="form-control" id="input_nama_perusahaan" name="input_nama_perusahaan">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_nama_perusahaan_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_bidang">Bidang</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_bidang_error_container">
                    <label class="control-label" id="input_bidang_error_detail"></label>
                    <input type="text" class="form-control" id="input_bidang" name="input_bidang">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_bidang_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_jabatan">Jabatan</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_jabatan_error_container">
                    <label class="control-label" id="input_jabatan_error_detail"></label>
                    <input type="text" class="form-control" id="input_jabatan" name="input_jabatan">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_jabatan_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_kota_kerja">Kota</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_kota_kerja_error_container">
                    <label class="control-label" id="input_kota_kerja_error_detail"></label>
                    <input type="text" class="form-control" id="input_kota_kerja" name="input_kota_kerja">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_kota_kerja_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_masa_kerja">Masa Kerja</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_masa_kerja_error_container">
                    <label class="control-label" id="input_masa_kerja_error_detail"></label>
                    <input type="text" class="form-control" id="input_masa_kerja" name="input_masa_kerja">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_masa_kerja_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

            </div>
            <div class="card-footer ">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="submit" class="btn btn-success btn-sm" id="btn-save-kerja">Tambah</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-jabatan" aria-hidden="true" >
  <div class="modal-dialog" role="document"  style="background: none;">
    <div class="modal-content"  style="background: none; box-shadow: none; border: none">
      <div class="modal-body">
        <div class="card " style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);">

          <div class="card-header card-header-info card-header-icon d-flex flex-row">
            <div class="card-icon">
              <i class="material-icons">content_paste</i>
            </div>
            <h4 class="card-title">Tambah Data Jabatan</h4>
          </div>

          <div class="card-body ">
            <form method="post" id="form-jabatan" onsubmit="return: false;">
              <input type="hidden" name="id_karyawan_jabatan" id="id_karyawan_jabatan" value="<?php echo $record['id']; ?>">
              <input type="hidden" name="id">
              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_jabatan">Jabatan</label>
                <div class="col-sm-8" >
                  <div class="form-group label-floating " id="input_jabatan_error_container" >
                    <!-- <label class="control-label" id="input_tempat_lahir_error_detail"></label> -->
                    <select class="form-control" id="input_jabatan" name="input_jabatan" >
                      <option value="x" selected>-- Silahkan Pilih --</option>

                      <?php
                      foreach ($list_jabatan as $r) {
                        echo '<option value="'.$r->id.'">'.$r->nama_jabatan.'</option>';
                      }
                      ?>

                    </select>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_jabatan_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_detail_jabatan">Detail</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_detail_jabatan_error_container">
                    <label class="control-label" id="input_detail_jabatan_error_detail"></label>
                    <!-- <input type="text" class="form-control" id="input_detail_jabatan" name="input_detail_jabatan"> -->
                    <textarea class="form-control" id="input_detail_jabatan" name="input_detail_jabatan" rows="2" cols="80"></textarea>
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_detail_jabatan_error_icon"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-4 col-form-label" for="input_tmt_jabatan">TMT Jabatan</label>
                <div class="col-sm-8">
                  <div class="form-group label-floating " id="input_tmt_jabatan_error_container">
                    <label class="control-label" id="input_tmt_jabatan_error_detail"></label>
                    <input type="text" class="form-control" id="input_tmt_jabatan" name="input_tmt_jabatan">
                    <span class="form-control-feedback">
                      <i class="material-icons" id="input_tmt_jabatan_error_icon"></i>
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

            </div>
            <div class="card-footer ">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><b class="fa fa-close"></b></button>
              <button type="submit" class="btn btn-success btn-sm" id="btn-save-jabatan">Tambah</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
