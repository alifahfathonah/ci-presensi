<div class="content flashdata_container">
    <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
    <div class="card" style="border-top: solid 3px #7ea4b3">
        <div class="card-body card-block">
            <h5 class="card-title display-4" style="font-size: 25px">Cetak Data Transaksi Kas</h5>
            <div class="input-group input-group-sm" id="prefetch">
                <div class="input-group-append" id="button-addon4">
                    <!-- <div id="reportrange" class="btn btn-sm btn-outline-secondary ">
                        <b class="fa fa-calendar"></b> Silahkan Pilih Periode Laporan!
                    </div> -->
                    <!-- <button class="btn btn-outline-secondary" type="button" onclick="cetak_laporan()"><b class="fa fa-print"></b> Cetak Laporan</button>
                    <a class="btn btn-outline-secondary" role="button" href="<?php echo base_url('kasanggota') ?>"><b class="fa fa-times"></b> Hapus Filter</a> -->
                </div>
            </div>
            <hr>

            <div class="table-responsive">
                <h1 class="display-4 text-center" style="font-size: 25px" id="label_page">Laporan Data Kas Per Periode </h1>
                <br>
                <table id="table_lap_trans_kas" class="table table-striped table-sm small" style="width:100%">
                    <thead class='thead-darks'>
                        <tr class="text-center">
                            <th>No </th>
                            <th style="width:10%;">Kode Trans. </th>
                            <th style="width:10%;">Tanggal Trans. </th>
                            <th style="width:30%;">Akun Transaksi </th>
                            <th style="width:10%;">Dari Kas </th>
                            <th style="width:10%;">Untuk Kas </th>
                            <th style="width:10%;">Debet </th>
                            <th style="width:20%;">Kredit </th>
                            <th style="width:20%;">Saldo </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <?php
                                $no = $offset + 1;
                                echo '
                            <tr class="bg bg-warning">
                                    <td class="text-right" colspan="7"> <strong>SALDO SEBELUMNYA</strong></td>
                                    <td class="text-right" colspan="3"><strong>' . rupiah(nsi_round($saldo_awal + $saldo_sblm)) . '</strong></td>
                            </tr>';
                                ?> -->
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation example">
                <!-- <?php echo $model['pagination']; ?> -->
            </nav>
        </div>
    </div>