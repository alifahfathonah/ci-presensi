<!-- buaat tanggal sekarang -->
<?php
	$tagihan = $row_pinjam->ags_per_bulan * $row_pinjam->lama_angsuran;
	$dibayar = $hitung_dibayar->total;
	$jml_denda = $hitung_denda->total_denda;
	$sisa_bayar = $tagihan - $dibayar;
	$total_bayar = $sisa_bayar + $jml_denda;
?>

<div class="content">
  <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
    <a href="<?php echo base_url('pinjaman'); ?>" role="button" class="btn btn-sm btn-danger" ><b class="fa fa-angle-double-left"></b> Kembali</a>
		<!-- cetak_pinjaman_detail -->
		<a href="<?php echo base_url('pinjaman/cetak_pinjaman_detail/'.$this->uri->segment(3)); ?>" role="button" class="btn btn-sm btn-success" ><b class="fa fa-print"></b> Cetak Detail</a>
    <a href="<?php echo base_url('angsuran/index/'.$this->uri->segment(3)); ?>" role="role" class="btn btn-sm btn-info" ><b class="fa fa-money"></b> Bayar Angsuran</a>
    <a href="<?php echo base_url('angsuran_lunas/index/'.$this->uri->segment(3)); ?>" role="button" class="btn btn-sm btn-success" ><b class="fa fa-check-square-o"></b> Validasi Lunas</a>
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
                <td>Bunga</td><td>:</td>
                <td class="text-right"><?php echo rupiah($row_pinjam->bunga_pinjaman); ?></td>
              </tr>
              <tr>
                <td>Biaya Admin</td><td>:</td>
                <td class="text-right"><?php echo rupiah($row_pinjam->biaya_adm); ?></td>
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
          <td>Sisa Angsuran : <?php echo $row_pinjam->lama_angsuran - $sisa_ags; ?> Bulan</td>
          <td>Dibayar : Rp. <?php echo rupiah(nsi_round($dibayar)); ?></td>
          <td>Denda : Rp. <?php echo rupiah(nsi_round($jml_denda)); ?></td>
          <td>Sisa Tagihan : Rp. <?php echo rupiah(nsi_round($total_bayar)); ?> </td>
          <td>Status Pelunasan : <?php echo $row_pinjam->lunas; ?></td>
        </tr>
      </table>
    </div>
  </div>

  <div class="card" style="border-top: solid 3px red">
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 20px">Simulasi Tagihan</h5>
      <div class="btn-group btn-group-sm float-right" id="button_container">
      </div>
      <hr>
      <table class="small  text-dark" width="100%" border="1" style="border: grey">
        <thead class="bg-info">
          <tr class="text-center">
            <th style="width:10%; vertical-align: middle"> Bln ke</th>
    				<th style="width:15%; vertical-align: middle"> Angsuran Pokok</th>
    				<th style="width:15%; vertical-align: middle"> Angsuran Bunga</th>
    				<th style="width:30%; vertical-align: middle"> Jumlah Angsuran</th>
    				<th style="width:20%; vertical-align: middle"> Tanggal Tempo</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(!empty($simulasi_tagihan)) {
            $no = 1;
  					// $row = array();
  					$jml_pokok = 0;
  					$jml_bunga = 0;
  					$jml_ags = 0;
  					$jml_adm = 0;
            foreach ($simulasi_tagihan as $row){
              if(($no % 2) == 0) {
  							$warna="bg-warning";
  							} else {
  							$warna="";
  						}

  						$txt_tanggal = formatTglIndo_3($row['tgl_tempo']);
  						$jml_pokok += $row['angsuran_pokok'];
  						$jml_bunga += $row['bunga_pinjaman'];
  						$jml_ags += $row['jumlah_ags'];

              echo "<tr class='$warna'>";
              echo "<td class='text-center'>".$no."</td>";
              echo "<td class='text-right'>".rupiah(nsi_round($row['angsuran_pokok']))."</td>";
              echo "<td class='text-right'>".rupiah(nsi_round($row['bunga_pinjaman']))."</td>";
              echo "<td class='text-right'>".rupiah(nsi_round($row['jumlah_ags']))."</td>";
              echo "<td class='text-center'>".$txt_tanggal."</td>";
              echo "</tr>";
              $no++;
            }
            echo "<tr class='bg-light'>";
            echo "<td class='text-center'><b>Jumlah</b></td>";
            echo "<td class='text-right'><b>".rupiah(nsi_round($jml_pokok))."</b></td>";
            echo "<td class='text-right'><b>".rupiah(nsi_round($jml_bunga))."</b></td>";
            echo "<td class='text-right'><b>".rupiah(nsi_round($jml_ags))."</b></td>";
            echo "<td class='text-center'></td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card" style="border-top: solid 3px green">
    <div class="card-body card-block">
      <h5 class="card-title display-4" style="font-size: 20px">Detail Transaksi Pembayaran</h5>
      <div class="btn-group btn-group-sm float-right" id="button_container">
      </div>
      <hr>

      <?php

      $mulai=1;
  		$no=1;
  		$jml_tot = 0;
  		$jml_denda = 0;

  		if(empty($angsuran)) { ?>
        <div class="alert alert-warning">
          <code> Tidak Ada Transaksi Pembayaran</code>
        </div>
      <?php } else { ?>

        <table class="small" width="100%" border="1">
          <thead class="bg-info">
            <tr class="text-center">
              <th style="width:5%; vertical-align: middle " > No. </th>
          		<th style="width:12%; vertical-align: middle"> Kode Bayar</th>
          		<th style="width:13%; vertical-align: middle"> Tanggal Bayar</th>
          		<th style="width:5%; vertical-align: middle"> Angsuran Ke </th>
          		<th style="width:15%; vertical-align: middle"> Jenis Pembayaran </th>
          		<th style="width:20%; vertical-align: middle"> Jumlah Bayar</th>
          		<th style="width:20%; vertical-align: middle"> Denda  </th>
          		<th style="width:10%; vertical-align: middle"> User  </th>
            </tr>
          </thead>
          <tbody>

            <?php

            foreach ($angsuran as $row) {
          		if(($no % 2) == 0) {
          		$warna="#FAFAD2";
          		} else {
          		$warna="#FFFFFF";
          		}

          		$tgl_bayar = explode(' ', $row->tgl_bayar);
          		$txt_tanggal = formatTglIndo_3($tgl_bayar[0]);
          		$jml_tot += $row->jumlah_bayar;
          		$jml_denda += $row->denda_rp;

              echo "<tr>";
              echo "<td class='text-center'>".$no."</td>";
              echo "<td class='text-center'>".'TBY'.sprintf('%05d', $row->id)."</td>";
              echo "<td class='text-center'>".$txt_tanggal."</td>";
              echo "<td class='text-center'>".$row->angsuran_ke."</td>";
              echo "<td class='text-center'>".$row->ket_bayar."</td>";
              echo "<td class='text-right'>".rupiah(nsi_round($row->jumlah_bayar))."</td>";
              echo "<td class='text-right'>".rupiah(nsi_round($row->denda_rp))."</td>";
              echo "<td class='text-center'>".$row->user_name."</td>";
              echo "</tr>";

              $no++;
            }

            echo "<tr class='bg-light'>";
            echo "<td class='text-center' colspan='5'><b>Jumlah</b></td>";
            echo "<td class='text-right'><b>".number_format(nsi_round($jml_tot))."</b></td>";
            echo "<td class='text-right'><b>".number_format(nsi_round($jml_denda))."</b></td>";
            echo "</tr>";
            ?>

          </tbody>
        </table>

      <?php } ?>
    </div>
  </div>

</div>

<script type="text/javascript">
    document.title = "Detail Pinjaman - KSU Sakrawarih"
</script>
