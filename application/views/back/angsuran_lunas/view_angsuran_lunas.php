<!-- buaat tanggal sekarang -->
<?php
$tagihan = $row_pinjam->ags_per_bulan * $row_pinjam->lama_angsuran;
$dibayar = $hitung_dibayar->total;
$jml_denda = $hitung_denda->total_denda;
$sisa_bayar = $tagihan - $dibayar;
$total_bayar = $sisa_bayar + $jml_denda;
?>

<div class="content">
<div class="alert alert-danger">
  <mark class="text-danger">Hapus Salah satu transaksi pembayaran untuk membatalkan status lunas</mark>
</div>
</div>

<div class="content">
<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">

  <a href="<?php echo base_url('angsuran/index/'.$this->uri->segment(3)); ?>" role="button" class="btn btn-sm btn-warning" ><b class="fa fa-tags"></b> Pembayaran Angsuran</a>
  <a href="<?php echo base_url('pinjaman/cetak_pinjaman_detail/'.$this->uri->segment(3)); ?>" role="button" class="btn btn-sm btn-success" ><b class="fa fa-print"></b> Cetak Detail</a>
</div>
</div>

<div class="content mt-3">
<div class="card" style="border-top: solid 3px blue">
  <div class="card-body card-block">
    <h5 class="card-title display-4" style="font-size: 20px">Detail Pinjaman</h5>
    <div class="btn-group btn-group-sm float-right" id="button_container">
    </div>
    <hr>

    <div class="col-md-2 ">
      <?php
					if($data_anggota['file_pic'] == '') {
            echo '<img src="'.base_url().'uploads/photo_default.jpg'.'" alt="..." class="img-thumbnail">';
					} else {
					  echo '<img src="'.base_url().'uploads/anggota/' . $data_anggota['file_pic']. '" alt="Foto" class="img-thumbnail">';
					}
				?>
    </div>

    <div class="col-md-4 " style="padding:0px; margin:0px; border: none">
      <div class="card" style="margin:0px;border: none">
        <div class="card-body" style=" padding: 0px">
          <h5 class="card-title text-success" style="font-size: 15px; font-weight: bold">Data Anggota</h5>
          <table style="font-size: 13px" width="100%">
            <tr>
              <td>ID Anggota</td><td>:</td>
              <td><?php echo $data_anggota['identitas']; ?></td>
            </tr>
            <tr>
              <td>Nama Anggota</td><td>:</td>
              <td><?php echo $data_anggota['nama']; ?></td>
            </tr>
            <tr>
              <td>Dept</td><td>:</td>
              <td><?php echo $data_anggota['departement']; ?></td>
            </tr>
            <tr>
              <td>Tempat, Tanggal Lahir </td><td>:</td>
              <td><?php echo $data_anggota['tmp_lahir'] .', '. formatTglIndo_3($data_anggota['tgl_lahir']); ?></td>
            </tr>
            <tr>
              <td>Kota Tinggal</td><td>:</td>
              <td><?php echo $data_anggota['kota']; ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-3 " >
      <div class="card" style="margin:0px;border: none">
        <div class="card-body" style=" padding: 0px">
          <h5 class="card-title text-success" style="font-size: 15px; font-weight: bold">Data Pinjaman</h5>
          <table style="font-size: 13px" width="100%">
            <tr>
              <td>Kode Pinjam</td><td>:</td>
              <td><?php echo 'TPJ' . sprintf('%05d', $row_pinjam->id) . '' ?> </td>
            </tr>
            <tr>
              <td>Tanggal Pinjam</td><td>:</td>
              <td>
                <?php
                $tanggal_arr = explode(' ', $row_pinjam->tgl_pinjam);
                echo formatTglIndo_3($tanggal_arr[0]);
                ?>
              </td>
            </tr>
            <tr>
              <td>Tanggal Tempo</td><td>:</td>
              <td>
                <?php
                $tanggal_arr = explode(' ', $row_pinjam->tempo);
                echo formatTglIndo_3($tanggal_arr[0]);
                ?>
              </td>
            </tr>
            <tr>
              <td>Lama Pinjaman</td><td>:</td>
              <td><?php echo $row_pinjam->lama_angsuran; ?> Bulan</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-3 " >
      <div class="card" style="margin:0px;border: none; padding: 0px">
        <div class="card-body" style=" padding: 0px">
          <h5 class="card-title text-success" style="font-size: 15px; font-weight: bold"></h5>
          <table style="font-size: 13px;" width="100%">
            <tr>
              <td>Pokok Pinjaman</td><td>:</td>
              <td class="text-right"><?php echo rupiah(nsi_round($row_pinjam->jumlah))?></td>
            </tr>
            <tr>
              <td>Angsuran Pokok</td><td>:</td>
              <td class="text-right"><?php echo rupiah($row_pinjam->pokok_angsuran); ?></td>
            </tr>
            <tr>
              <td>Biaya & Bunga</td><td>:</td>
              <td class="text-right"><?php echo rupiah($row_pinjam->biaya_adm + $row_pinjam->bunga_pinjaman); ?></td>
            </tr>
            <tr>
              <td>Jumlah Angsuran </td><td>:</td>
              <td class="text-right"><?php echo rupiah(nsi_round($row_pinjam->ags_per_bulan)); ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

  </div>
  <div class="card-footer bg-info text-light" style="padding: 0px; font-size: 12px">
    <table width="100%">
      <tr>
        <td><b>&nbsp Detail Pembayaran</b> <b class="fa fa-angle-double-right"></b></td>
        <td>Dibayar : Rp. <?php echo rupiah(nsi_round($dibayar)); ?></td>
        <td>Denda : Rp. <?php echo rupiah(nsi_round($jml_denda)); ?></td>
        <td>Sisa Tagihan : Rp. <?php echo rupiah(nsi_round($total_bayar)); ?> </td>
        <td>Status Pelunasan : <?php echo $row_pinjam->lunas; ?></td>
      </tr>
    </table>
  </div>
</div>

<input type="hidden" id="hidden_id" name="hidden_id" value="<?php echo $this->uri->segment(3) ?>">

<div class="card" style="border-top: solid 3px green">
  <div class="card-body card-block">
    <h5 class="card-title display-4" style="font-size: 20px">Data Pembayaran Angsuran</h5>
		<!-- <button type="button" class="btn btn-sm btn-success" onclick="open_modal_bayar_angsuran()"><b class="fa fa-plus"></b> Bayar</button> -->
    <hr>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-sm small" style="width:100%" id="table_pelunasan">
				<thead class="bg-info">
					<tr class="text-center">
						<th style="width:5%; vertical-align: middle " > No. </th>
						<th style="width:12%; vertical-align: middle"> Kode Bayar</th>
						<th style="width:20%; vertical-align: middle"> Tanggal Bayar</th>
						<th style="width:20%; vertical-align: middle"> Jumlah Bayar</th>
						<th style="width:20%; vertical-align: middle"> Keterangan  </th>
						<th style="width:10%; vertical-align: middle"> User  </th>
						<th style="width:10%; vertical-align: middle"> Cetak Nota  </th>
					</tr>
				</thead>
      </table>
		</div>
  </div>
</div>

</div>

<script type="text/javascript">
  document.title = "Bayar Pelunasan - KSU Sakrawarih"
</script>


<!-- Modal -->
<div class="modal fade" id="modal_pelunasan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h6 class="modal-title" id="exampleModalLabel">Pelunasan</h6>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
			<form id="form_pelunasan">

				<div class="form-group row">
					<label for="id_input_tanggal" class="control-label col-md-4" >Tanggal Transaksi</label>
					<div class="col-md-8" >
						<div class="input-group input-group-sm date">
							<input type="text" class="form-control form-control-sm" name="input_tanggal_trans"  id='input_tanggal_trans' readonly style="background: white">
							<div class="input-group-append">
								<span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
						<small class="form-text text-danger" id="input_tanggal_trans_error" ></small>
					</div>
				</div>

				<div class="form-group row">
			    <label for="inputEmail3" class="col-sm-4 col-form-label">Nomor Pinjam</label>
			    <div class="col-sm-8">
			      <input type="text" class="form-control" id="input_nomor_pinjaman" name="input_nomor_pinjaman" readonly style="background: white"
						value="<?php echo 'PJ' . sprintf('%05d', $this->uri->segment(3)) . '' ?>">
						<input type="hidden" name="input_nomor_pinjaman_id" value="<?php echo $this->uri->segment(3); ?>">
			    </div>
			  </div>

				<div class="form-group row">
			    <label for="inputEmail3" class="col-sm-4 col-form-label">Sisa Tagihan</label>
			    <div class="col-sm-8">
			      <input type="text" class="form-control" id="input_sisa_tagihan" name="input_sisa_tagihan"  readonly style="background: white">
			    </div>
			  </div>

				<div class="form-group row">
			    <label for="inputEmail3" class="col-sm-4 col-form-label">Jumlah Bayar</label>
			    <div class="col-sm-8">
			      <input type="text" class="form-control" id="input_jumlah_bayar" name="input_jumlah_bayar" onkeypress="return isNumberKey(event)" min="0">
						<small class="form-text text-danger" id="input_jumlah_bayar_error" ></small>
			    </div>
			  </div>

				<div class="form-group row">
					<label for="inputPassword" class="col-sm-4 col-form-label">Simpan Ke Kas</label>
					<div class="col-sm-8">
						<?php echo cmb_dinamis('simpan_ke_kas','nama_kas_tbl','nama','id'); ?>
						<small id="simpan_ke_kas_error" class="text-danger"></small>
					</div>
				</div>

				<div class="form-group row">
			    <label for="inputEmail3" class="col-sm-4 col-form-label">Keterangan</label>
			    <div class="col-sm-8">
			      <input type="text" class="form-control" id="input_keterangan" name="input_keterangan" >
			    </div>
			  </div>

				<span id="angsuran_ke" class="inputform" ></span>
				<span id="sisa_ags" class="inputform"></span>
				<span id="denda" class="inputform" ></span>
				<input type="hidden" id="denda_val" name="denda_val" value="" />

    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-sm btn-primary" id="btn-save">Simpan</button>
			<button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Batal</button>
			</form>
    </div>
  </div>
</div>
</div>
