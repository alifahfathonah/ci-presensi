<?php
$tagihan = $row_pinjam->ags_per_bulan * $row_pinjam->lama_angsuran;
$dibayar = $hitung_dibayar->total;
$jml_denda = $hitung_denda->total_denda;
$sisa_bayar = $tagihan - $dibayar;
$total_bayar = $sisa_bayar + $jml_denda;

$sim_pokok    = $data_anggota['simpanan_pokok'];
$sim_sukarela = $data_anggota['simpanan_sukarela'];
$sim_wajib    = $data_anggota['simpanan_wajib'];
?>

<input type="hidden" id="hidden_id" value="<?php echo $this->uri->segment(3); ?>">

<div class="content">
	<div class="alert alert-danger">
		Klik <strong>Validasi Lunas</strong> untuk melakukan Pelunasan dan Pembayaran Tagihan Denda
	</div>

	<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
		<a href="<?php echo base_url('angsuran_lunas/index/'.$this->uri->segment(3)); ?>" role="button"
			class="btn btn-sm btn-success" ><b class="fa fa-check-square-o"></b> Validasi Lunas</a>
			<!-- <a href="#" role="button" class="btn btn-sm btn-success" ><b class="fa fa-check-square-o"></b> Validasi Lunas</a> -->
			<a href="#" role="button" class="btn btn-sm btn-info" ><b class="fa fa-file-o"></b> Detail</a>
		</div>

		<div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
			<button type="button" class="btn btn-sm btn-light" onclick="modal_petunjuk_pembayaran()" ><b class="fa fa-question"></b></button>
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
									<td class="text-right"><?php echo rupiah($row_pinjam->bunga_pinjaman + $row_pinjam->biaya_adm); ?></td>
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
			<div class="card-footer bg-info text-light" style="padding: 0px; font-size: 16px">
				<table width="100%">
					<tr>
						<td><b>&nbsp Rangkuman</b> <b class="fa fa-angle-double-right"></b></td>
						<td>Sisa Angsuran : <?php echo $row_pinjam->lama_angsuran - $sisa_ags; ?> Bulan</td>
						<td>Dibayar : Rp. <?php echo rupiah(nsi_round($dibayar)); ?></td>
						<td>Denda : Rp. <?php echo rupiah(nsi_round($jml_denda)); ?></td>
						<td>Sisa Tagihan : Rp. <?php echo rupiah(nsi_round($total_bayar)); ?> </td>
						<td>Status Pelunasan : <?php echo $row_pinjam->lunas; ?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="card" style="border-top: solid 3px none">
			<div class="card-body card-block">
				<h5 class="card-title display-4" style="font-size: 20px">Data Pembayaran Angsuran</h5>
				<!-- <button type="button" class="btn btn-sm btn-success" onclick="open_modal_bayar_angsuran()"><b class="fa fa-plus"></b> Bayar</button> -->
				<div class="btn-group btn-group-sm float-right" id="button_container">
				</div>
				<hr>
				<div class="table-responsive">
					<table id="table_angsuran" class="table table-hover table-striped table-sm small" style="width:100%" >
						<thead class='thead-dark'>
							<tr>
								<th >No </th>
								<th >Kode </th>
								<th >Tanggal Bayar </th>
								<th >Tanggal Tempo </th>
								<th >Angsuran Ke </th>
								<th >Jumlah Bayar </th>
								<th >Denda </th>
								<th >Terlambat </th>
								<th >Username </th>
								<th >Pilihan </th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="modal_bayar_angsuran" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title">Form Tambah Pembayaran Angsuran</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-7">
							<div class="card">
								<div class="card-body">
									<h5 class="card-title">Pembayaran Angsuran</h5>
									<form id="form_pembayaran_angsuran" >

										<div class="form-group row">
											<label for="id_input_tanggal" class="control-label col-md-6" >Tanggal Transaksi</label>
											<div class="col-md-6" >
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
											<label for="inputPassword" class="col-sm-6 col-form-label">Nomor Pinjaman</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" name="input_nomor_pinjaman" id="input_nomor_pinjaman" readonly style="background: white; border: none"
												value="<?php echo 'TPJ' . sprintf('%05d', $this->uri->segment(3)) . '' ?>">
												<input type="hidden" name="input_nomor_pinjaman_id" value="<?php echo $this->uri->segment(3); ?>">
											</div>
										</div>

										<div class="form-group row">
											<label for="inputPassword" class="col-sm-6 col-form-label">Angsuran Ke </label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" name="input_angsuran_ke" id="input_angsuran_ke" readonly style="background: white; border: none">
											</div>
										</div>

										<div class="form-group row">
											<label for="staticEmail" class="col-sm-6 col-form-label">Sisa Angsuran</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" name="input_sisa_angsuran" id="input_sisa_angsuran" readonly style="background: white; border: none">
											</div>
										</div>

										<div class="form-group row">
											<label for="inputPassword" class="col-sm-6 col-form-label">Jumlah Angsuran</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" name="input_jumlah_angsuran" id="input_jumlah_angsuran" readonly style="background: white; border: none"
												value="<?php echo rupiah(nsi_round($row_pinjam->ags_per_bulan)); ?>">
											</div>
										</div>

										<div class="form-group row">
											<label for="inputPassword" class="col-sm-6 col-form-label">Sisa Tagihan</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" name="input_sisa_tagihan" id="input_sisa_tagihan" readonly style="background: white; border: none" >
											</div>
										</div>

										<div class="form-group row">
											<label for="staticEmail" class="col-sm-6 col-form-label">Denda</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" name="input_denda" id="input_denda" readonly style="background: white; border: none" >
											</div>
										</div>

										<div class="form-group row">
											<label for="inputPassword" class="col-sm-6 col-form-label">Simpan Ke Kas</label>
											<div class="col-sm-6">
												<?php echo cmb_dinamis('simpan_ke_kas','nama_kas_tbl','nama','id'); ?>
												<small id="simpan_ke_kas_error" class="text-danger"></small>
											</div>
										</div>

										<input type="hidden" id="id_bayar" name="id_bayar" value="" />

										<div class="form-group row">
											<label for="inputPassword" class="col-sm-6 col-form-label">Keterangan</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" name="input_keterangan" value="">
											</div>
										</div>

									</div>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="card">
									<div class="card-body">
										<h5 class="card-title">Pembayaran Simpanan</h5>

										<div class="row form-group">
											<div class="col col-md-6"><label class=" form-control-label">Bayar Simpanan</label></div>
											<div class="col col-md-6">
												<div class="form-check-inline form-check">
													<label for="inline-radio1" class="form-check-label ">
														<input type="radio" id="inline-radio1" name="inline-radios" value="y" class="form-check-input" onchange="opsiBayarSimpanan()" >Yes
													</label>
													&nbsp
													<label for="inline-radio2" class="form-check-label ">
														<input type="radio" id="inline-radio2" name="inline-radios" value="n" class="form-check-input" onchange="opsiBayarSimpanan()" checked>No
													</label>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="inputPassword" class="col-sm-6 col-form-label">Simpanan Wajib </label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" id="input_simpanan_wajib" name="input_simpanan_wajib" value="<?php echo number_format(0); ?>">
												<input type="text" class="form-control form-control-sm" id="input_simpanan_wajib_hidden" name="input_simpanan_wajib_hidden" value="<?php echo number_format($sim_wajib); ?>">
											</div>
										</div>

										<div class="form-group row">
											<label for="staticEmail" class="col-sm-6 col-form-label">Simpanan Sukarela </label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" id="input_simpanan_sukarela" name="input_simpanan_sukarela" value="<?php echo number_format(0); ?>">
												<input type="text" class="form-control form-control-sm" id="input_simpanan_sukarela_hidden" name="input_simpanan_sukarela_hidden" value="<?php echo number_format($sim_sukarela); ?>">
											</div>
										</div>

										<input type="hidden" name="input_anggota_id" value="<?php echo $row_pinjam->anggota_id; ?>">

									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-sm btn-primary" id="btn-save">Simpan</button>
						<button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Batal</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	document.title = "Bayar Angsuran - KSU Sakrawarih"
</script>


<!-- Modal -->
<div class="modal fade" id="modal_petunjuk_pembayaran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="exampleModalLabel"><b class="fa fa-book"></b> Cara Pembayaran</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="list-unstyled" style="font-size: 15px">
					<li><strong>A. Pembayaran Angsuran</strong></li>
					<li>- Admin mencatat <mark class="text-danger">Pembayaran Angsuran </mark>sesuai Jumlah Angsuran setiap anggota</li>
					<li>- Anggota akan dikenakan <mark class="text-danger">Denda </mark>apabila terlambat melakukan pembayaran sesuai jatuh tempo</li>
					<li>- Batas maksimal pembayaran adalah pada tanggal 15 (Lima Belas) setiap bulan, <mark class="text-danger">Tanggal Dapat diubah pada menu Setting App » Suku Bunga </mark></li>

					<li><strong>B. Pelunasan Cepat</strong></li>
					<li>- Anggota dinyatakan <mark class="text-danger">LUNAS </mark>apabila telah membayar sejumlah tagihan yang dibebankan dan tidak memiliki tagihan <mark class="text-danger">Denda </mark> atau tagihan lainnya</li>
					<li>- Pelunasan dapat dilakukan walau Anggota masih memiliki kewajiban angsuran atau kurang dari tanggal jatuh tempo</li>
					<li>- Jika Anggota telah menyelesaikan angsuran, Admin diharuskan melakukan <mark class="text-danger">Validasi Pelunasan </mark>untuk menghitung sisa pembayaran dan denda yang dibebankan kepada anggota</li>
					<li>- Anggota dapat melakukan peminjaman selanjutnya jika tidak mempunyai tagihan dipinjaman sebelumnya</li>
				</ul>
			</div>
		</div>
	</div>
</div>
