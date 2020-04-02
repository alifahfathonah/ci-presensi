<?php
$tanggal = date('Y-m');
$txt_periode_arr = explode('-', $tanggal);
if (is_array($txt_periode_arr)) {
  $txt_periode = jin_nama_bulan($txt_periode_arr[1]) . ' ' . $txt_periode_arr[0];
}

?>

<?php
$total_tagihan = $jml_pinjaman->jml_total;
$total_denda = $jml_denda->total_denda;
$jml_tot_tagihan = $total_tagihan + $total_denda;

?>

<div class="content mt-3">
  <h1 class="display-1" style="font-size: 25px">Selamat Datang!</h1>
  <h1 class="display-1" style="font-size: 20px">Hai, <?php echo $this->session->userdata('username'); ?>. Silahkan pilih menu disamping untuk mengoperasikan aplikasi</h1>
  <hr>
  <div class="col-sm-12 mb-4">
    <div class="card-group">
      <div class="card col-md-6 no-padding bg-warning">
        <div class="card-body text-light">
          <i class="fa fa-money" style="font-size: 75px; position: absolute; top: auto; bottom: 60px; right: 15px; z-index: 0; color: rgba(0, 0, 0, 0.15);"></i>
          <h1 class="display-1 font-weight-bold text-light text-uppercase" style="font-size: 20px">Pinjaman Kredit</h1>
          <hr>
          <div class="h6 mb-0">
            <table style="font-size: 15px">
              <tr class="font-weight-normal">
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $peminjam; ?></span>&nbsp</td>
                <td>Transaksi Bulan Ini</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo rupiah(nsi_round($jml_tot_tagihan)); ?></span>&nbsp</td>
                <td>Jml Tagihan Tahun Ini</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo rupiah(nsi_round($jml_tot_tagihan - $jml_angsuran->jml_total)); ?></span>&nbsp</td>
                <td>Sisa Tagihan Tahun Ini</td>
              </tr>
            </table>
          </div>
          <br>
          <a style="position: relative; text-align: center; padding: 3px 0; margin: 0; color: rgba(255, 255, 255, 0.8);
          display: block; z-index: 10; background: rgba(0, 0, 0, 0.1);" href="<?php echo base_url('lappinjaman') ?>">
            More info
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="card col-md-6 no-padding" style="background-color: #8565c4">
        <div class="card-body text-light">
          <i class="fa fa-book" style="font-size: 75px; position: absolute; top: auto; bottom: 60px; right: 15px; z-index: 0; color: rgba(0, 0, 0, 0.15);"></i>
          <h1 class="display-1 font-weight-bold text-light text-uppercase" style="font-size: 20px">Kas Bulan
            <?php

            switch (date('m')) {
              case '01':
                echo 'Januari';
                break;
              case '02':
                echo 'Februari';
                break;
              case '03':
                echo 'Maret';
                break;
              case '04':
                echo 'April';
                break;
              case '05':
                echo 'Mei';
                break;
              case '06':
                echo 'Juni';
                break;
              case '07':
                echo 'Juli';
                break;
              case '08':
                echo 'Agustus';
                break;
              case '09':
                echo 'September';
                break;
              case '10':
                echo 'Oktober';
                break;
              case '11':
                return 'November';
                break;
              case '12':
                return 'Desember';
                break;
            }

            echo " " . date('Y');

            ?>
          </h1>
          <hr>
          <div class="h6 mb-0">
            <table style="font-size: 15px">
              <tr class="font-weight-normal">
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php
                                                                                      $debet = $kas_debet->jml_total;
                                                                                      echo number_format(nsi_round($debet)); ?>
                  </span>&nbsp</td>
                <td>Debet</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count">
                    <?php
                    $kredit = $kas_kredit->jml_total;
                    echo number_format(nsi_round($kredit))
                    ?>
                  </span>&nbsp</td>
                <td>Kredit</td>
              </tr>
              <tr>
                <td class="text-left" style="font-weight: bold;"><span class="count"><?php echo number_format(nsi_round($debet - $kredit)); ?></span>&nbsp</td>
                <td>Jumlah</td>
              </tr>
            </table>
          </div>
          <br>
          <a style="position: relative; text-align: center; padding: 3px 0; margin: 0; color: rgba(255, 255, 255, 0.8);
        display: block; z-index: 10; background: rgba(0, 0, 0, 0.1);" href="<?php echo base_url('lapsaldo') ?>">
            More info
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="card col-md-6 no-padding bg-primary">
        <div class="card-body text-light">
          <i class="fa fa-user" style="font-size: 75px; position: absolute; top: auto; bottom: 60px; right: 15px; z-index: 0; color: rgba(0, 0, 0, 0.15);"></i>
          <h1 class="display-1 font-weight-bold text-light text-uppercase" style="font-size: 20px">Data Anggota</h1>
          <hr>
          <div class="h6 mb-0">
            <table style="font-size: 15px">
              <tr class="font-weight-normal">
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $anggota_aktif; ?></span>&nbsp</td>
                <td>Anggota Aktif</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $anggota_non; ?></span>&nbsp</td>
                <td>Anggota Non-Aktif</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $anggota_all; ?></span>&nbsp</td>
                <td>Jumlah Anggota</td>
              </tr>
            </table>
          </div>
          <br>
          <a style="position: relative; text-align: center; padding: 3px 0; margin: 0; color: rgba(255, 255, 255, 0.8);
      display: block; z-index: 10; background: rgba(0, 0, 0, 0.1);" href="<?php echo base_url('anggota/laporan_anggota') ?>">
            More info
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-12 mb-4">
    <div class="card-group">
      <div class="card col-md-6 no-padding bg-danger">
        <div class="card-body text-light">
          <i class="fa fa-calendar" style="font-size: 75px; position: absolute; top: auto; bottom: 60px; right: 15px; z-index: 0; color: rgba(0, 0, 0, 0.15);"></i>
          <h1 class="display-1 font-weight-bold text-light text-uppercase" style="font-size: 20px">Data Peminjam</h1>
          <hr>
          <div class="h6 mb-0">
            <table style="font-size: 15px">
              <tr class="font-weight-normal">
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $peminjam_aktif; ?></span>&nbsp</td>
                <td>Peminjam</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $peminjam_lunas; ?></span>&nbsp</td>
                <td>Sudah Lunas</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $peminjam_belum; ?></span>&nbsp</td>
                <td>Belum Lunas</td>
              </tr>
            </table>
          </div>
          <br>
          <a style="position: relative; text-align: center; padding: 3px 0; margin: 0; color: rgba(255, 255, 255, 0.8);
        display: block; z-index: 10; background: rgba(0, 0, 0, 0.1);" href="<?php echo base_url('pinjaman') ?>">
            More info
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="card col-md-6 no-padding bg-success">
        <div class="card-body text-light">
          <i class="fa fa-briefcase" style="font-size: 75px; position: absolute; top: auto; bottom: 60px; right: 15px; z-index: 0; color: rgba(0, 0, 0, 0.15);"></i>
          <h1 class="display-1 font-weight-bold text-light text-uppercase" style="font-size: 20px">Simpanan
            <?php

            switch (date('m')) {
              case '01':
                echo 'Januari';
                break;
              case '02':
                echo 'Februari';
                break;
              case '03':
                echo 'Maret';
                break;
              case '04':
                echo 'April';
                break;
              case '05':
                echo 'Mei';
                break;
              case '06':
                echo 'Juni';
                break;
              case '07':
                echo 'Juli';
                break;
              case '08':
                echo 'Agustus';
                break;
              case '09':
                echo 'September';
                break;
              case '10':
                echo 'Oktober';
                break;
              case '11':
                return 'November';
                break;
              case '12':
                return 'Desember';
                break;
            }

            echo " " . date('Y');

            ?>
          </h1>
          <hr>
          <div class="h6 mb-0">
            <table style="font-size: 15px">
              <tr class="font-weight-normal">
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo number_format(nsi_round($jml_simpanan->jml_total)); ?></span>&nbsp</td>
                <td>Simpanan Anggota</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo number_format(nsi_round($jml_penarikan->jml_total)); ?></span>&nbsp</td>
                <td>Penarikan Tunai</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo number_format(nsi_round($jml_simpanan->jml_total - $jml_penarikan->jml_total)); ?></span>&nbsp</td>
                <td>Jumlah Simpanan</td>
              </tr>
            </table>
          </div>
          <br>
          <a style="position: relative; text-align: center; padding: 3px 0; margin: 0; color: rgba(255, 255, 255, 0.8);
      display: block; z-index: 10; background: rgba(0, 0, 0, 0.1);" href="<?php echo base_url('lapsimpanan') ?>">
            More info
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
      <div class="card col-md-6 no-padding bg-info">
        <div class="card-body text-light">
          <i class="fa fa-users" style="font-size: 75px; position: absolute; top: auto; bottom: 60px; right: 15px; z-index: 0; color: rgba(0, 0, 0, 0.15);"></i>
          <h1 class="display-1 font-weight-bold text-light text-uppercase" style="font-size: 20px">Data Pengguna</h1>
          <hr>
          <div class="h6 mb-0">
            <table style="font-size: 15px">
              <tr class="font-weight-normal">
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $user_aktif; ?></span>&nbsp</td>
                <td>User Aktif</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $user_non;; ?></span>&nbsp</td>
                <td>User Non-Aktif</td>
              </tr>
              <tr>
                <td class="text-right" style="font-weight: bold;"><span class="count"><?php echo $user_aktif + $user_non; ?></span>&nbsp</td>
                <td>Jumlah User </td>
              </tr>
            </table>
          </div>
          <br>
          <a style="position: relative; text-align: center; padding: 3px 0; margin: 0; color: rgba(255, 255, 255, 0.8);
    display: block; z-index: 10; background: rgba(0, 0, 0, 0.1);" href="<?php echo base_url('auth') ?>">
            More info
            <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>


</div>