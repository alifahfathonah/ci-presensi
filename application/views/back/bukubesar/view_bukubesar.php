<div class="content flashdata_container">
    <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
    <div class="card" style="border-top: solid 3px #7ea4b3">
        <div class="card-body card-block">
            <h5 class="card-title display-4" style="font-size: 25px">Cetak Laporan Buku Besar</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><b class="fa fa-calendar"></b></span>
                        </div>
                        <select class="custom-select custom-select-sm" id="input_bulan" onchange="getdata()">
                            <!-- <option >Pilih Bulan</option> -->
                            <?php
                            $nama_bulan = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                            foreach ($nama_bulan as $r) {
                                $selected = ($r == Date('m')) ? 'selected' : '';
                                echo '<option ' . $selected . ' value="' . $r . '">' . bulan_1($r) . '</option>';
                            }
                            ?>
                        </select>
                        <select class="custom-select custom-select-sm" id="input_tahun" onchange="getdata()">
                            <?php
                            $cur_year = Date('Y');
                            for ($i = $cur_year - 4; $i <= $cur_year; $i++) {
                                $selected = ($i == $cur_year) ? 'selected' : '';
                                echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="cetak_laporan()"><b class="fa fa-print"></b> Cetak Laporan</button>
                        <a class="btn btn-sm btn-outline-secondary" role="button" href="<?php echo base_url('lapbukubesar') ?>"><b class="fa fa-times"></b> Hapus Filter</a>
                    </div>
                </div>
            </div>
            <hr>
            <!-- content -->
            <div id="content_bukubesar">
                <h1 class="display-4 text-center" style="font-size: 24px; font-weight: bold;">Laporan Buku Besar Periode <?php echo bulan_1(Date('m')) . " " . Date('Y'); ?></h1>
                <br>
            </div>

        </div>
    </div>
</div>