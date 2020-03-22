<div class="content flashdata_container">
    <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
    <div class="card" style="border-top: solid 3px #7ea4b3">
        <div class="card-body card-block">
            <h5 class="card-title display-4" style="font-size: 25px">Cetak Laporan Data Anggota</h5>
            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                <a role="button" class="btn btn-sm btn-success" href="<?php echo base_url('anggota/cetak'); ?>"><b class="fa fa-print"></b> Cetak Laporan</a>
            </div>
            <hr>
            <div class="table-responsive">
                <table id="table_laporan_anggota" class="table table-hover table-sm small" style="width:100%">
                    <thead class='thead-dark'>
                        <tr>
                            <th>No </th>
                            <th>ID Anggota </th>
                            <th>Nama Anggota </th>
                            <th>L/P </th>
                            <th>Jabatan </th>
                            <th>Alamat </th>
                            <th>Status </th>
                            <th>Tgl Registrasi </th>
                            <th>Foto </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>