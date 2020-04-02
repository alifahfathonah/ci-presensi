<div class="content flashdata_container">
    <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
    <div class="card" style="border-top: solid 3px #7ea4b3">
        <div class="card-body card-block">
            <h5 class="card-title display-4" style="font-size: 25px">Cetak Data Simpanan</h5>
            <div class="input-group input-group-sm" id="prefetch">
                <div class="input-group-append" id="button-addon4">
                    <div id="reportrange" class="btn btn-sm btn-outline-secondary ">
                        <b class="fa fa-calendar"></b> Silahkan Pilih Periode Laporan!
                    </div>
                    <button class="btn btn-outline-secondary" type="button" onclick="cetak_laporan()"><b class="fa fa-print"></b> Cetak Laporan</button>
                    <a class="btn btn-outline-secondary" role="button" href="<?php echo base_url('lapsimpanan') ?>"><b class="fa fa-times"></b> Hapus Filter</a>
                </div>
            </div>
            <hr>

            <div class="table-responsive" id="content_lapsimpanan">
                
            </div>
        </div>
    </div>