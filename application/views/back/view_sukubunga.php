
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
    $form_attributes = array('class' => '', 'id' => 'form_sukubunga');
    echo form_open($url, $form_attributes);
    ?>
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 25px">Biaya & Administrasi</h5>
      <hr>
      <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group"><label for="company" class=" form-control-label">Tipe Pinjaman Bunga</label>
            <!-- <input name="input_nama_koperasi" id="input_nama_koperasi" type="text" placeholder="-" class="form-control" value="<?php echo $record['pinjaman_bunga_tipe']; ?>"> -->
            <?php $list = array('A' => 'A: Persen bunga dikali angsuran bulan', 'B' => 'B: Persen bunga dikali total pinjaman' ); ?>
            <select class="form-control" name="input_pinjaman_bunga_tipe" id="input_pinjaman_bunga_tipe">
              <?php foreach ($list as $key => $value): ?>
                <?php if ($key == $record['pinjaman_bunga_tipe']){
                        $selected = 'selected';
                      } else {
                        $selected = '';
                      }
                ?>
                <option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Suku Bunga Pinjaman (%)</label>
            <input name="input_bg_pinjam" id="input_bg_pinjam" type="text" placeholder="-" class="form-control" value="<?php echo $record['bg_pinjam']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Biaya Administrasi (%)</label>
            <input name="input_biaya_adm" id="input_biaya_adm" type="text" placeholder="-" class="form-control" value="<?php echo $record['biaya_adm']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Biaya Denda (Rp)</label>
            <input name="input_denda" id="input_denda" type="text" placeholder="-" class="form-control" value="<?php echo $record['denda']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Tempo Tanggal Pembayaran</label>
            <input name="input_denda_hari" id="input_denda_hari" type="text" placeholder="-" class="form-control" value="<?php echo $record['denda_hari']; ?>">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group"><label for="company" class=" form-control-label">Dana Cadangan (%)</label>
            <input name="input_dana_cadangan" id="input_dana_cadangan" type="text" placeholder="-" class="form-control" value="<?php echo $record['dana_cadangan']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Jasa Anggota (%)</label>
            <input name="input_jasa_anggota" id="input_jasa_anggota" type="text" placeholder="-" class="form-control" value="<?php echo $record['jasa_anggota']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Dana Pengurus (%)</label>
            <input name="input_dana_pengurus" id="input_dana_pengurus" type="text" placeholder="-" class="form-control" value="<?php echo $record['dana_pengurus']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Dana Karyawan (%)</label>
            <input name="input_dana_karyawan" id="input_dana_karyawan" type="text" placeholder="-" class="form-control" value="<?php echo $record['dana_karyawan']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Dana Pendidikan (%)</label>
            <input name="input_dana_pend" id="input_dana_pend" type="text" placeholder="-" class="form-control" value="<?php echo $record['dana_pend']; ?>">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group"><label for="company" class=" form-control-label">Dana Sosial (%)</label>
            <input name="input_dana_sosial" id="input_dana_sosial" type="text" placeholder="-" class="form-control" value="<?php echo $record['dana_sosial']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Jasa Usaha (%)</label>
            <input name="input_jasa_usaha" id="input_jasa_usaha" type="text" placeholder="-" class="form-control" value="<?php echo $record['jasa_usaha']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Jasa Modal Anggota (%)</label>
            <input name="input_jasa_modal" id="input_jasa_modal" type="text" placeholder="-" class="form-control" value="<?php echo $record['jasa_modal']; ?>">
          </div>
          <div class="form-group"><label for="company" class=" form-control-label">Pajak PPh (%)</label>
            <input name="input_pjk_pph" id="input_pjk_pph" type="text" placeholder="-" class="form-control" value="<?php echo $record['pjk_pph']; ?>">
          </div>
        </div>
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
