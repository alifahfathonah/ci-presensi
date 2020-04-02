<div class="content flashdata_container">
    <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
    <div class="card" style="border-top: solid 3px #7ea4b3">
        <div class="card-body card-block">
            <h5 class="card-title display-4" style="font-size: 25px">Laporan Saldo Kas</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><b class="fa fa-calendar"></b></span>
                        </div>
                        <select class="custom-select custom-select-sm" id="input_bulan" onchange="cari_data()">
                            <!-- <option >Pilih Bulan</option> -->
                            <?php
                            $nama_bulan = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                            foreach ($nama_bulan as $r) {
                                $selected = ($r == Date('m')) ? 'selected' : '';
                                echo '<option ' . $selected . ' value="' . $r . '">' . bulan_1($r) . '</option>';
                            }
                            ?>
                        </select>
                        <select class="custom-select custom-select-sm" id="input_tahun" onchange="cari_data()">
                            <?php
                            $cur_year = Date('Y');
                            for ($i = $cur_year - 4; $i <= $cur_year; $i++) {
                                $selected = ($i == $cur_year) ? 'selected' : '';
                                echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="cetak_laporan()"><b class="fa fa-print"></b> Cetak Laporan</button>
                        <a class="btn btn-sm btn-outline-secondary" role="button" href="<?php echo base_url('lapsaldo') ?>"><b class="fa fa-times"></b> Hapus Filter</a>
                    </div>
                </div>
            </div>
            <hr>
            <!-- content -->
            <div id="content_lap_saldo">
                <h1 class="display-4 text-center" style="font-size: 24px; font-weight: bold;">Laporan Saldo Kas Periode <?php echo bulan_1(Date('m')) . " " . Date('Y'); ?></h1>
                <br>
                <div class="table-responsive">
                    <table id="table_saldo_kas" class="table table-sm small table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No.</th>
                                <th width="50%" class="text-center">Nama Kas</th>
                                <th width="40%" class="text-center">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td class="text-right"><strong>SALDO PERIODE SEBELUMNYA</strong></td>
                                <td class="text-right"><strong><?php echo rupiah(nsi_round($saldo_sblm)); ?></strong></td>
                            </tr>
                            <?php
                            $no = 0 + 1;

                            $kas_arr = array();
                            $debet_total = 0;
                            $kredit_total = 0;
                            $saldo_total = 0;
                            foreach ($jenis_kas as $jenis) {

                                //Apabila sisa baginya genap,
                                if (($no % 2) == 0) {
                                    $warna = "#EEEEEE";
                                }
                                //Apabila sisa baginya tidak genap, 
                                else {
                                    $warna = "#FFFFFF";
                                }

                                $kas_arr[$jenis->id] = $jenis->nama;
                                $nilai_debet = $this->Lap_saldo_model->get_jml_debet($jenis->id);
                                $nilai_kredit = $this->Lap_saldo_model->get_jml_kredit($jenis->id);

                                $debet_row = $nilai_debet->jml_total;
                                $kredit_row = $nilai_kredit->jml_total;
                                $saldo_row = $debet_row - $kredit_row;

                                $saldo_total += $saldo_row;

                                echo '
                                <tr>
                                <td class="">' . $no++ . '</td>
                                <td>' . $jenis->nama . '</td>
                                <td class="text-right">' . rupiah(nsi_round($saldo_row)) . '</td>
                                </tr>';
                            }
                            ?>
                            <tr class="table-active">
                                <td></td>
                                <td class="text-right"><strong>Jumlah</strong></td>
                                <td class="text-right"><strong><?php echo rupiah(nsi_round($saldo_total)); ?></strong></td>
                            </tr>
                            <tr class="table-success">
                                <td></td>
                                <td class="text-right"><strong>Saldo</strong></td>
                                <td class="text-right"><strong><?php echo rupiah(nsi_round($saldo_total + $saldo_sblm)); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>