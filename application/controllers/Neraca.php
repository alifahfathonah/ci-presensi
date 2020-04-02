<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neraca extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Lapneraca_model'));
        $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table', 'PDF_MC_Table2', 'Pdf_2'));
        $this->load->helper(array('url', 'language', 'app_helper'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    function index(){
        $this->template->load('Template', 'back/neraca/view_neraca');
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

        $data_jns_kas = $this->Lapneraca_model->get_data_jenis_kas();
        $data_akun    = $this->Lapneraca_model->get_data_akun();

        echo '
        <h1 class="display-4 text-center" style="font-size: 25px" id="label_page">Laporan Neraca Saldo Periode '.formatTglIndo($tgl_dari).' - '.formatTglIndo($tgl_samp).'</h1>
                <br>
                <table class="table table-sm small table-bordered" style="width: 100%">
                    <thead style="background-color:#e3dccf">
                        <tr>
                            <th width="5%"></th>
                            <th class="text-center" width="40%">Nama Akun</th>
                            <th class="text-center" width="20%">Debet</th>
                            <th class="text-center" width="20%">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="5%" style="font-size: 15px;" class="text-center"><b class="fa fa-folder-open-o"></<b>
                            </td>
                            <td width="40%" style="font-weight: bold;">A. Aktiva Lancar </td>
                            <td width="20%"></td>
                            <td width="20%"></td>
                        </tr>';
        $jum_debet = 0;
        $jum_kredit = 0;

        //ambil data kass
        $no_kas = 1;
        foreach ($data_jns_kas as $jenis) {
            $nilai_debet = $this->Lapneraca_model->get_jml_debet($jenis->id);
            $nilai_kredit = $this->Lapneraca_model->get_jml_kredit($jenis->id);

            $debet_row  = $nilai_debet->jml_total;
            $kredit_row = $nilai_kredit->jml_total;
            $saldo_row  = $debet_row - $kredit_row;
            echo '
					<tr>
						<td></td>
						<td>A' . $no_kas . '. ' . $jenis->nama . '</td>
						<td class="text-right"> ' . number_format(nsi_round($saldo_row)) . ' </td>
						<td class="text-right"> 0 </td>
				  </tr>';
            $jum_debet += $saldo_row;
            $no_kas++;
        }

        foreach ($data_akun as $nama) {
            echo '<tr>';
            if (strlen($nama->kd_aktiva) != 1) {
                echo '<td id="disini"> &nbsp; </td>
							<td>' . $nama->kd_aktiva . '. ' . $nama->jns_trans . '</td>';
            } else {
                echo '<td id="disitu" class="text-center"> &nbsp; <i class="fa fa-folder-open-o"></i> </td>
						  <td> <strong>' . $nama->kd_aktiva . '. ' . $nama->jns_trans . '</strong></td>';
            }

            $jml_akun = $this->Lapneraca_model->get_jml_akun($nama->id);
            $akun_d = $jml_akun->jum_debet;
            $akun_k = $jml_akun->jum_kredit;

            if ($nama->akun == 'Aktiva') {
                $lancar_j = $akun_k - $akun_d;
                echo '
					<td class="text-right">' . number_format($lancar_j) . '</td>
					<td class="text-right">0</td>';
                $jum_debet += $lancar_j;
            }

            if ($nama->akun == 'Pasiva') {
                $pasiva_j = $akun_d - $akun_k;
                echo '
					<td class="text-right">0</td>
					<td class="text-right">' . number_format($pasiva_j) . '</td>';
                $jum_kredit += $pasiva_j;
            }
            echo '</tr>';
        }
        echo '<tr style="background-color:#e3dccf; font-weight: bold" >
			<td class="text-center" colspan="2"> JUMLAH </td>
			<td class="text-right">'.number_format($jum_debet).'</td>
			<td class="text-right">'.number_format($jum_kredit).'</td>
		</tr>';
        echo '</tbody></table>';
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

        $pdf = new PDF_MC_Table2('l', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->addPage();

        $pdf->SetFont('Arial', 'B', 17);
        $pdf->Cell(277, 15, 'Laporan Neraca Saldo Periode ' .  formatTglIndo($tgl_dari) . ' - ' . formatTglIndo($tgl_samp), 0, 1, 'C');

        $pdf->SetFillColor(210, 221, 242);

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(20, 8, 'No.', 1, 0, 'C', TRUE);
        $pdf->Cell(151, 8, 'Nama Akun', 1, 0, 'C', TRUE);
        $pdf->Cell(53, 8, 'Debet', 1, 0, 'C', TRUE);
        $pdf->Cell(53, 8, 'Kredit', 1, 1, 'C', TRUE);

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(20, 8, 'A', 1, 0, 'C', TRUE);
        $pdf->Cell(151, 8, 'Aktifa Lancar', 1, 0, 'C', TRUE);
        $pdf->Cell(53, 8, '', 1, 0, 'C', TRUE);
        $pdf->Cell(53, 8, '', 1, 1, 'C', TRUE);

        $pdf->SetAligns(array('C', 'C', 'C', 'C'));

        $i = 1;
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetWidths(array(20, 151, 53, 53));

        $data_jns_kas = $this->Lapneraca_model->get_data_jenis_kas();
        $data_akun    = $this->Lapneraca_model->get_data_akun();

        $jum_debet = 0;
        $jum_kredit = 0;

        //ambil data kass
        $no_kas = 1;
        $pdf->SetFont('Arial', '', 13);
        foreach ($data_jns_kas as $jenis) {
            $nilai_debet = $this->Lapneraca_model->get_jml_debet($jenis->id);
            $nilai_kredit = $this->Lapneraca_model->get_jml_kredit($jenis->id);

            $debet_row  = $nilai_debet->jml_total;
            $kredit_row = $nilai_kredit->jml_total;
            $saldo_row  = $debet_row - $kredit_row;

            $pdf->Cell(20, 8,  'A' . $no_kas , 1, 0, 'L');
            $pdf->Cell(151, 8, $jenis->nama, 1, 0, 'L');
            $pdf->Cell(53, 8, number_format(nsi_round($saldo_row)), 1, 0, 'R');
            $pdf->Cell(53, 8, '0', 1, 1, 'R');
            $jum_debet += $saldo_row;
            $no_kas++;
        }

        foreach ($data_akun as $nama) {
            if (strlen($nama->kd_aktiva) != 1) {
                if ($nama->akun == '') {
                    $pdf->Cell(20, 8, $nama->kd_aktiva, 1, 0, 'L');
                    $pdf->Cell(151, 8, $nama->jns_trans, 1, 1, 'L');
                } else {
                    $pdf->Cell(20, 8, $nama->kd_aktiva, 1, 0, 'L');
                    $pdf->Cell(151, 8, $nama->jns_trans, 1, 0, 'L');
                }
            } else {
                $pdf->Cell(20, 8, $nama->kd_aktiva, 1, 0, 'L');
                $pdf->Cell(151, 8, $nama->jns_trans, 1, 0, 'L');
            }

            $jml_akun = $this->Lapneraca_model->get_jml_akun($nama->id);
            $akun_d = $jml_akun->jum_debet;
            $akun_k = $jml_akun->jum_kredit;

            if ($nama->akun == 'Aktiva') {
                $lancar_j = $akun_k - $akun_d;
                $pdf->Cell(53, 8, number_format($lancar_j), 1, 0, 'R');
                $pdf->Cell(53, 8, '0', 1, 1, 'R');
                $jum_debet += $lancar_j;
            }
 
            if ($nama->akun == 'Pasiva') {
                $pasiva_j = $akun_d - $akun_k;
                $pdf->Cell(53, 8, '0', 1, 0, 'R');
                $pdf->Cell(53, 8, number_format($pasiva_j), 1, 1, 'R');
                $jum_kredit += $pasiva_j;
            }
            // echo '</tr>';
        }

        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(171, 8, 'Jumlah', 1, 0, 'C', true);
        $pdf->Cell(53, 8, number_format($jum_debet), 1, 0, 'C', true);
        $pdf->Cell(53, 8, number_format($jum_kredit), 1, 1, 'C', true);

        $pdf->Output();
    }
}