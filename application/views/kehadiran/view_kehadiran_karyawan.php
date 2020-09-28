<?php $this->session->userdata['page_title'] = "Data Kehadiran"; ?>
<div class="row" style="">
  <div class="col-lg-12 col-md-12">
    <div class="card">

      <div class="card-header card-header-info d-flex flex-row align-items-center">
        <h4 class="card-title">Data Kehadiran Karyawan</h4>
      </div>
      <div class="card-body ">
        <div class="row">
          <div class="col-md-4" >
            <?php echo $karyawan['nama_lengkap']; ?>
            <br>
            <?php echo $departemen['nama_departemen']; ?>
            <br>
            <?php echo $periode; ?>
            <br>
            <br>
            Rekap
            <hr>
            <?php
            echo "Total Hadir ".$total_hadir." hari <br>";
            echo "Total Akumulasi Terlambat ".$total_hadir_terlambat."<br>";
            echo "Total Alpha/Tanpa Keterangan ".$total_alpha." hari <br>";
            echo "Total Izin ".$total_izin." hari <br>";
            echo "Total Cuti ".$total_cuti." hari <br>";
            echo "Total Sakit ".$total_sakit." hari <br>";
             ?>
          </div>
          <div class="col-md-8">
            <form class="form-inline">
              <input type="hidden" name="id_karyawan" id="id_karyawan" value="<?php echo $karyawan['id']; ?>">
              <input type="hidden" value="<?php echo $start; ?>">
              <input type="hidden" value="<?php echo $end; ?>">
              <label class="sr-only" for="inlineFormInputGroupUsername2">Mulai</label>
              <div class="input-group mb-2 mr-sm-2">
                <input type="text" class="form-control datetimepicker" id="input_tanggal_awal_filter_2" name="input_tanggal_awal_filter_2"/>
              </div>

              <label class="sr-only" for="inlineFormInputGroupUsername2">Sampai</label>
              <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                  <div class="input-group-text">s/d</div>
                </div>
                <input type="text" class="form-control datetimepicker" id="input_tanggal_akhir_filter_2" name="input_tanggal_akhir_filter_2"/>
              </div>

              <button type="button" class="btn btn-sm btn-success mb-2" onclick="cari()">Cari</button>
              <a role="button" class="btn btn-sm btn-primary mb-2" href="<?php echo base_url('kehadiran/excel/'.$karyawan['id'].'/'.$start.'/'.$end); ?>">Export</a>
            </form>
            <hr>
            <div class="table-responsive">
              <table class="table table-striped table-sm " style="width: 100%">
                <thead>
                  <tr>
                    <th>Hari</th>
                    <th>Tanggal</th>
                    <th>Shifting</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                    <th>#</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($record as $r) {
                    $libur_mark = ($r['jam_kerja'] == 'Libur') ? 'text-info' : '' ;
                    $button_detail = "";
                    if($r['status_kehadiran'] == "C" || $r['status_kehadiran'] == "I" || $r['status_kehadiran'] == "S" ){
                      $button_detail = '<a href="'.base_url('izin/edit/'.$r['id_izin']).'" role="button" name="button" class="btn btn-sm btn-success"><b class="fa fa-file"></b></a>';
                    } else if($r['status_kehadiran'] == "Hadir" OR $r['status_kehadiran'] == "Hadir (Terlambat)" ){
                      $button_detail = '<a href="'.base_url('kehadiran/detail/'.$r['id_karyawan'].'/'.$r['tanggal']).'" role="button" name="button" class="btn btn-sm btn-success"><b class="fa fa-file"></b></a>';
                    }

                    echo "<tr class='".$libur_mark."'>";
                    echo "<td>".$r['hari']."</td>";
                    echo "<td>".formatTglIndo($r['tanggal'])."</td>";
                    echo "<td>".$r['jam_kerja']."</td>";
                    echo "<td>".$r['jam_masuk']."</td>";
                    echo "<td>".$r['jam_pulang']."</td>";
                    echo "<td>".$r['status_kehadiran']."<br><small class='text-warning'>".$r['terlambat']."</small></td>";
                    echo '<td>'.$button_detail.'</td>';
                    echo "</tr>";
                  }
                  ?>
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
