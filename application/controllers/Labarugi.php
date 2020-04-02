<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Labarugi extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model(array('Labarugi_model'));
        $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table', 'PDF_MC_Table2', 'Pdf_2'));
        $this->load->helper(array('url', 'language', 'app_helper'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    function index(){
        $this->template->load('Template', 'back/labarugi/view_labarugi');
    }

    function getdata($param1 = '', $param2 = ''){
        if ($param1 !== '' && $param2 !== '') {
            $_REQUEST['tgl_dari'] = $param1;
            $_REQUEST['tgl_samp'] = $param2;
            $tgl_dari = $_REQUEST['tgl_dari'];
            $tgl_samp = $_REQUEST['tgl_samp'];
        } else {

            $_REQUEST['tgl_dari'] = date('Y') . '-01-01';
            $_REQUEST['tgl_samp'] = date('Y') . '-12-31';

            $tgl_dari = $_REQUEST['tgl_dari'];
            $tgl_samp = $_REQUEST['tgl_samp'];
        }

        $jml_pinjaman  = $this->Labarugi_model->get_jml_pinjaman();
        $jml_biaya_adm = $this->Labarugi_model->get_jml_biaya_adm();
        $jml_bunga     = $this->Labarugi_model->get_jml_bunga();
        $jml_tagihan   = $this->Labarugi_model->get_jml_tagihan();
        $jml_angsuran  = $this->Labarugi_model->get_jml_angsuran();
        $jml_denda     = $this->Labarugi_model->get_jml_denda();
        $data_dapat    = $this->Labarugi_model->get_data_akun_dapat();
        $data_biaya    = $this->Labarugi_model->get_data_akun_biaya();

        echo '<h1 class="display-4 text-center" style="font-size: 25px" id="label_page">Laporan Laba Rugi Periode ' . formatTglIndo($tgl_dari) . ' - ' . formatTglIndo($tgl_samp) . '</h1><br>';

        $pinjaman  = $jml_pinjaman->jml_total;
        $biaya_adm = $jml_biaya_adm->jml_total;
        $bunga     = $jml_bunga->jml_total;
        $bulatan   = $jml_tagihan->jml_total - ($jml_pinjaman->jml_total + $jml_bunga->jml_total + $jml_biaya_adm->jml_total);
        $tagihan   = $jml_tagihan->jml_total;
        $estimasi  = $tagihan - $pinjaman; 
        echo '<h1 class="display-4 text-left" style="font-size: 20px; font-weight: bold" id="label_page">Estimasi Data Pinjaman</h1>
                <div class="table-responsive">
                    <table class="table table-sm small table-bordered" style="width: 100%">
                        <thead style="background-color:#e3dccf">
                            <tr>
                                <th class="text-center" width="1%">No.</th>
                                <th class="text-center" width="60%">Keterangan</th>
                                <th class="text-center" width="30%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Jumlah Pinjaman</td>
                                <td class="text-right">'.rupiah(nsi_round($pinjaman)). '</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Pendapatan Biaya Administrasi</td>
                                <td class="text-right">' . rupiah(nsi_round($biaya_adm)) . '</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pendapatan Biaya Bunga</td>
                                <td class="text-right">' . rupiah(nsi_round($bunga)) . '</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Pendapatan Biaya Pembulatan</td>
                                <td  class="text-right">' . rupiah(nsi_round($bulatan)) . '</td>
                            </tr>
                            <tr style="background-color:#e3dccf; font-weight: bold">
                                <td colspan="2" class="text-right">Jumlah Tagihan</td>
                                <td class="text-right">' . rupiah(nsi_round($tagihan)) . '</td>
                            </tr>
                            <tr style="font-weight: bold">
                                <td colspan="2" class="text-right">Estimasi Pendapatan Pinjaman</td>
                                <td class="text-right">' . rupiah(nsi_round($estimasi)) . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>';

        $sd_dibayar = $jml_angsuran->jml_total;
        $laba = $sd_dibayar - $pinjaman;
        echo '
        <h1 class="display-4 text-left" style="font-size: 20px; font-weight: bold" id="label_page">Pendapatan</h1>
                <div class="table-responsive">
                    <table class="table table-sm small table-bordered" style="width: 100%">
                        <thead style="background-color:#e3dccf">
                            <tr>
                                <th class="text-center" width="1%">No.</th>
                                <th class="text-center" width="60%">Keterangan</th>
                                <th class="text-center" width="30%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"> 1 </td>
                                <td> Pendapatan Pinjaman</td>
                                <td class="text-right">'. rupiah(nsi_round($laba)) . '</td>
                            </tr>';
        $no_dapat = 2;
        $jml_dapat = 0;
        foreach ($data_dapat as $row) {
            echo '
				<tr>
					<td class="text-center"> ' . $no_dapat . ' </td>';
            $jml_akun = $this->Labarugi_model->get_jml_akun($row->id);
            $jumlah = $jml_akun->jum_debet + $jml_akun->jum_kredit;
            echo '<td>' . $row->jns_trans . '</td>
				<td class="text-right">' . rupiah(nsi_round($jumlah)) . '</td>';
            $jml_dapat += $jumlah;
            echo '</tr>';
            $no_dapat++;
        }

        $jml_p = $laba + $jml_dapat;
        echo '<tr style="background-color:#e3dccf; font-weight: bold">
                    <td colspan="2" class="text-right"> Jumlah Pendapatan</td>
                    <td class="text-right">'. rupiah(nsi_round($jml_p)).'</td>
              </tr>';                
        echo '</tbody></table></div>';
        
        
        echo '<h1 class="display-4 text-left" style="font-size: 20px; font-weight: bold" id="label_page">Biaya-biaya </h1>
                <div class="table-responsive">
                    <table class="table table-sm small table-bordered" style="width: 100%">
                        <thead style="background-color:#e3dccf">
                            <tr>
                                <th class="text-center" width="1%">No.</th>
                                <th class="text-center" width="60%">Keterangan</th>
                                <th class="text-center" width="30%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>';
        $no = 1;
        $jml_beban = 0;
        foreach ($data_biaya as $rows) {
            $jml_akun = $this->Labarugi_model->get_jml_akun($rows->id);
            $jumlah = $jml_akun->jum_debet + $jml_akun->jum_kredit;
            $jml_beban += $jumlah;

            echo '<tr>
						<td class="text-center">' . $no++ . '</td>
						<td>' . $rows->jns_trans . '</td>
						<td class="text-right">' . rupiah(nsi_round($jumlah)) . '</td>
					</tr>';
        }
        echo '<tr style="background-color:#e3dccf; font-weight: bold">
                    <td colspan="2" class="text-right"> Jumlah Biaya</td>
                    <td class="text-right">' . rupiah(nsi_round($jml_beban)) . '</td>
              </tr>';          
        echo '</tbody></table></div>';

        echo '<table width="100%">
                <tr style="background-color: #98FB98; font-weight: bold">
                    <td width="70%" colspan="2" class="text-center"> Laba Rugi </td>
                    <td width="30%" class="text-right">'. number_format(nsi_round($jml_p - $jml_beban)).'</td>
                </tr>
              </table>';
    }

    function cetak($param1 = '', $param2 = ''){
        if ($param1 !== '' && $param2 !== '') {
            $_REQUEST['tgl_dari'] = $param1;
            $_REQUEST['tgl_samp'] = $param2;
            $tgl_dari = $_REQUEST['tgl_dari'];
            $tgl_samp = $_REQUEST['tgl_samp'];
        } else {

            $_REQUEST['tgl_dari'] = date('Y') . '-01-01';
            $_REQUEST['tgl_samp'] = date('Y') . '-12-31';

            $tgl_dari = $_REQUEST['tgl_dari'];
            $tgl_samp = $_REQUEST['tgl_samp'];
        }

        $jml_pinjaman  = $this->Labarugi_model->get_jml_pinjaman();
        $jml_biaya_adm = $this->Labarugi_model->get_jml_biaya_adm();
        $jml_bunga     = $this->Labarugi_model->get_jml_bunga();
        $jml_tagihan   = $this->Labarugi_model->get_jml_tagihan();
        $jml_angsuran  = $this->Labarugi_model->get_jml_angsuran();
        $jml_denda     = $this->Labarugi_model->get_jml_denda();
        $data_dapat    = $this->Labarugi_model->get_data_akun_dapat();
        $data_biaya    = $this->Labarugi_model->get_data_akun_biaya();

        $pinjaman  = $jml_pinjaman->jml_total;
        $biaya_adm = $jml_biaya_adm->jml_total;
        $bunga     = $jml_bunga->jml_total;
        $bulatan   = $jml_tagihan->jml_total - ($jml_pinjaman->jml_total + $jml_bunga->jml_total + $jml_biaya_adm->jml_total);
        $tagihan   = $jml_tagihan->jml_total;
        $estimasi  = $tagihan - $pinjaman; 

        $pdf = new PDF_MC_Table2('l', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->addPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(277, 5, 'Laporan Laba Rugi Periode ' . formatTglIndo($tgl_dari) . ' - ' . formatTglIndo($tgl_samp), 0, 1, 'C');

        $pdf->SetFillColor(210, 221, 242);

        $pdf->Cell(277, 10, 'Estimasi Data Pinjaman', 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, 'No.', 1, 0, 'C', TRUE);
        $pdf->Cell(157, 5, 'Keterangan', 1, 0, 'C', TRUE);
        $pdf->Cell(100, 5, 'Jumlah', 1, 1, 'C', TRUE);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 6, '1.', 1, 0, 'C');
        $pdf->Cell(157, 6, 'Jumlah Pinjaman', 1, 0, 'L');
        $pdf->Cell(100, 6, rupiah(nsi_round($pinjaman)), 1, 1, 'R');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 6, '2.', 1, 0, 'C');
        $pdf->Cell(157, 6, 'Pendapatan Biaya Administrasi', 1, 0, 'L');
        $pdf->Cell(100, 6, rupiah(nsi_round($biaya_adm)), 1, 1, 'R');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 6, '3.', 1, 0, 'C');
        $pdf->Cell(157, 6, 'Pendapatan Biaya Bunga', 1, 0, 'L');
        $pdf->Cell(100, 6, rupiah(nsi_round($bunga)), 1, 1, 'R');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 6, '4.', 1, 0, 'C');
        $pdf->Cell(157, 6, 'Pendapatan Biaya Pembulatan', 1, 0, 'L');
        $pdf->Cell(100, 6, rupiah(nsi_round($bulatan)), 1, 1, 'R');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(177, 6, 'Jumlah Tagihan', 1, 0, 'R', TRUE);
        $pdf->Cell(100, 6, rupiah(nsi_round($tagihan)), 1, 1, 'R', TRUE);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(177, 6, 'Estimasi Pendapatan Pinjaman', 1, 0, 'R');
        $pdf->Cell(100, 6, rupiah(nsi_round($estimasi)), 1, 1, 'R');

        $pdf->Cell(277, 3, '', 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(277, 10, 'Pendapatan ', 0, 1, 'L');

        $sd_dibayar = $jml_angsuran->jml_total;
        $laba = $sd_dibayar - $pinjaman;

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, 'No.', 1, 0, 'C', TRUE);
        $pdf->Cell(157, 5, 'Keterangan', 1, 0, 'C', TRUE);
        $pdf->Cell(100, 5, 'Jumlah', 1, 1, 'C', TRUE);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 6, '1.', 1, 0, 'C');
        $pdf->Cell(157, 6, 'Pendapatan Pinjaman', 1, 0, 'L');
        $pdf->Cell(100, 6, rupiah(nsi_round($laba)), 1, 1, 'R');

        $no_dapat = 2;
        $jml_dapat = 0;
        foreach ($data_dapat as $row) {
            $jml_akun = $this->Labarugi_model->get_jml_akun($row->id);
            $jumlah = $jml_akun->jum_debet + $jml_akun->jum_kredit;
            $jml_dapat += $jumlah;

            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(20, 6, $no_dapat, 1, 0, 'C');
            $pdf->Cell(157, 6, $row->jns_trans, 1, 0, 'L');
            $pdf->Cell(100, 6, rupiah(nsi_round($jumlah)), 1, 1, 'R');
            
            $no_dapat++;
        }

        $jml_p = $laba + $jml_dapat;
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(177, 6, 'Jumlah Pendapatan', 1, 0, 'R', TRUE);
        $pdf->Cell(100, 6, rupiah(nsi_round($jml_p)), 1, 1, 'R', TRUE);


        $pdf->Cell(277, 3, '', 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(277, 10, 'Biaya-biaya ', 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, 'No.', 1, 0, 'C', TRUE);
        $pdf->Cell(157, 5, 'Keterangan', 1, 0, 'C', TRUE);
        $pdf->Cell(100, 5, 'Jumlah', 1, 1, 'C', TRUE);

        $no = 1;
        $jml_beban = 0;
        foreach ($data_biaya as $rows) {
            $jml_akun = $this->Labarugi_model->get_jml_akun($rows->id);
            $jumlah = $jml_akun->jum_debet + $jml_akun->jum_kredit;
            $jml_beban += $jumlah;

            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(20, 6, $no++, 1, 0, 'C');
            $pdf->Cell(157, 6, $rows->jns_trans, 1, 0, 'L');
            $pdf->Cell(100, 6, rupiah(nsi_round($jumlah)), 1, 1, 'R');

        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(177, 6, 'Jumlah Biaya', 1, 0, 'R', TRUE);
        $pdf->Cell(100, 6, rupiah(nsi_round($jml_beban)), 1, 1, 'R', TRUE);

        $pdf->Cell(277, 3, '', 0, 1, 'L');

        $pdf->SetFillColor(152, 251, 152);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(177, 6, 'Laba / Rugi', 0, 0, 'C', TRUE);
        $pdf->Cell(100, 6, rupiah(nsi_round($jml_p - $jml_beban)), 0, 1, 'R', TRUE);

        
        $pdf->Output();
    }

}