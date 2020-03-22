<div class="content flashdata_container">
    <?php echo $this->session->flashdata('message'); ?>
</div>
<div class="content mt-3">
    <div class="card" style="border-top: solid 3px #7ea4b3">
        <div class="card-body card-block">
            <h5 class="card-title display-4" style="font-size: 25px">Cetak Data Kas Anggota</h5>
            <div class="input-group input-group-sm" id="prefetch">
                <input type="text" class="form-control form-control-sm " placeholder="Ketik nama anggota" aria-label="Ketik nama anggota" name="input_nama_anggota" id="input_nama_anggota" style="width: 500px">
                <div class="input-group-append" id="button-addon4">
                    <button class="btn btn-outline-secondary" type="button" onclick="lihat_laporan()"><b class="fa fa-search"></b> Lihat Laporan</button>
                    <button class="btn btn-outline-secondary" type="button" onclick="cetak_laporan()"><b class="fa fa-print"></b> Cetak Laporan</button>
                    <a class="btn btn-outline-secondary" role="button" href="<?php echo base_url('kasanggota') ?>"><b class="fa fa-times"></b> Hapus Filter</a>
                </div>
            </div>
            <hr>

            <div class="table-responsive">
                <h1 class="display-4 text-center" style="font-size: 25px">Laporan Data Kas Per Anggota </h1>
                <br>
                <table id="table_laporan_anggota" class="table table-hover table-sm small" style="width:100%">
                    <thead class='thead-dark'>
                        <tr>
                            <th>No </th>
                            <th style="width:10%;">Foto </th>
                            <th style="width:25%;">Identitas </th>
                            <th style="width:25%;">Saldo Simpanan </th>
                            <th style="width:20%;">Tagihan Kredit </th>
                            <th style="width:20%;">Keterangan </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = $this->uri->segment(3) + 1;
                        foreach ($model['anggota'] as $r) {

                            //pinjaman
                            $pinjaman = $this->Lap_kas_anggota_model->get_data_pinjam($r->id)->row_array();
                            $pinjam_id = @$pinjaman['id'];
                            $anggota_id = @$pinjaman['anggota_id'];

                            $jml_pj = $this->Lap_kas_anggota_model->get_jml_pinjaman($anggota_id)->row_array();
                            $pj_anggota = @$jml_pj['total'];

                            //denda
                            $denda = $this->Lap_kas_anggota_model->get_jml_denda($pinjam_id)->row_array();
                            $tagihan = @$pinjaman['tagihan'] + $denda['total_denda'];
                            //dibayar
                            $dibayar = $this->Lap_kas_anggota_model->get_jml_bayar($pinjam_id)->row_array();
                            $sisa_tagihan = $tagihan - $dibayar['total'];

                            $peminjam_tot = $this->Lap_kas_anggota_model->get_peminjam_tot($r->id)->num_rows();
                            $peminjam_lunas = $this->Lap_kas_anggota_model->get_peminjam_lunas($r->id)->num_rows();

                            $data = "";
                            $tgl_tempo_txt = "";

                            if (count($pinjaman) > 0) {
                                $tgl_tempo = explode(' ', @$pinjaman['tempo']);
                                $tgl_tempo_txt = jin_date_ina($tgl_tempo[0], 'p');
                                $tgl_tempo_r = $tgl_tempo[0];

                                $tgl_tempo_rr = explode('-', $tgl_tempo_r);
                                $thn = $tgl_tempo_rr[0];
                                $bln = @$tgl_tempo_rr[1];

                                if ((@$pinjaman['lunas'] == 'Belum') && (date('m') > $bln)) {
                                    $data = 'Macet';
                                } else {
                                    $data = 'Lancar';
                                }
                            }

                            $photo = ($r->file_pic == null || $r->file_pic == "") ? base_url() . 'uploads/photo_default.jpg' : base_url() . 'uploads/anggota/' . $r->file_pic;
                            $gender    = ($r->jk == 'L') ? 'Laki-laki' : 'Perempuan';

                            if ($r->jabatan_id == "1") {
                                $jabatan = "Pengurus";
                            } else {
                                $jabatan = "Anggota";
                            }

                            echo "<tr>";
                            echo "<td>" . $i . "</td>";
                            echo '<td><img src="' . $photo . '" alt="..." class="img-thumbnail" style="height: auto;"></td>';
                            echo "<td>    
                                        ID Anggota : " . $r->identitas . "<br>
                                        Nama : <b>" . $r->nama . "</b><br>
                                        Jenis Kelamin : " . $gender . "<br>
                                        Jabatan : " . $jabatan . ' - ' . $r->departement . "<br>
                                        Alamat : " . $r->alamat . ", " . $r->kota . "<br>
                                        Telp. : " . $r->notelp . "<br>
                                     </td>";

                            $simpanan_arr = array();
                            $simpanan_row_total = 0;
                            $simpanan_total = 0;

                            echo "<td>";
                            echo '<table style="width:100%;">';
                            foreach ($data_jns_simpanan as $jenis) {
                                $simpanan_arr[$jenis->id] = $jenis->jns_simpan;
                                $nilai_s = $this->Lap_kas_anggota_model->get_jml_simpanan($jenis->id, $r->id);
                                $nilai_p = $this->Lap_kas_anggota_model->get_jml_penarikan($jenis->id, $r->id);

                                $simpanan_row = $nilai_s->jml_total - $nilai_p->jml_total;
                                $simpanan_row_total += $simpanan_row;
                                $simpanan_total += $simpanan_row_total;

                                echo '
                                        <tr>
                                            <td>' . $jenis->jns_simpan . '</td>
                                            <td class="text-right">' . rupiah($simpanan_row) . '</td>
                                        </tr>';
                            }
                            echo '<tr>
                                        <td><strong> Jumlah Simpanan </strong></td>
                                        <td class="text-right"><strong> ' . rupiah($simpanan_row_total) . '</strong></td>
                                    </tr>
                                    </table>';
                            echo "</td>";

                            echo '  <td>
                                        <table style="width:100%;">
                                            <tr>
                                                <td> Pokok Pinjaman</td>
                                                <td class="text-right"' . rupiah(nsi_round($pinjaman['jumlah'])) . '</td>
                                            </tr>
                                            <tr>
                                                <td> Tagihan + Denda </td> 
                                                <td class="text-right"> ' . rupiah(nsi_round($tagihan)) . ' </td>
                                            </tr>
                                            <tr>
                                                <td> Dibayar </td>
                                                <td class="text-right"> ' . rupiah(nsi_round($dibayar['total'])) . '</td>
                                            </tr>
                                            <tr>
                                                <td><strong> Sisa Tagihan</strong></td>
                                                <td class="text-right"> <strong>' . rupiah(nsi_round($sisa_tagihan)) . '</strong></td>
                                            </tr>
                                        </table>
                                    </td>';
                            echo '<td>
                                    <table style="width:100%;"> 
                                        <tr>
                                            <td> Jumlah Pinjaman </td>
                                            <td class="text-right">' . $peminjam_tot . '</td>
                                        </tr>
                                        <tr>
                                            <td> Pinjaman Lunas </td>
                                            <td class="text-right">' . $peminjam_lunas . '</td>
                                        </tr>
                                        <tr>
                                            <td> Pembayaran</td>
                                            <td class="text-right"> <code>' . $data . '</code></td>
                                        </tr>
                                        <tr>
                                            <td> Tanggal Tempo</td>
                                            <td class="text-right"> <code>' . $tgl_tempo_txt . '</code></td>
                                        </tr>
                                    </table>
                                  </td>';
                            echo "</tr>";
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation example">
                <?php echo $model['pagination']; ?>
            </nav>
        </div>
    </div>