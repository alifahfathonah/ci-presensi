<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapsimpanan extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model(array('Lapsimpanan_model'));
        $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table', 'PDF_MC_Table2', 'Pdf_2'));
        $this->load->helper(array('url', 'language', 'app_helper'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    function index(){
        $this->template->load('Template', 'back/simpanan/view_lap_simpanan');
    }

    function getdata($param1='', $param2 = ''){
        $data_jns_simpanan = $this->Lapsimpanan_model->get_data_jenis_simpan()->result();
        $no = 1;
        $mulai = 1;
        $simpanan_arr = array();
        $simpanan_row_total = 0;
        $simpanan_total = 0;
        $penarikan_total = 0;

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

        echo '<h1 class="display-4 text-center" style="font-size: 25px" id="">Laporan Simpanan Anggota </h1>
                <h1 class="display-4 text-center" style="font-size: 25px" id="label_page">Periode '. formatTglIndo($tgl_dari).' - '. formatTglIndo($tgl_samp). ' </h1>
                <br>
                <table id="table_lap_trans_kas" class="table table-bordered table-sm small" style="width:100%">
                    <thead style="background-color:#e3dccf">
                        <tr class="text-center">
                            <th style="width:1%;">No. </th>
                            <th style="width:39%;">Jenis Akun </th>
                            <th style="width:20%;">Simpanan </th>
                            <th style="width:20%;">Penarikan </th>
                            <th style="width:20%;">Jumlah </th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($data_jns_simpanan as $jenis) {
            $simpanan_arr[$jenis->id] = $jenis->jns_simpan;
            $nilai_s = $this->Lapsimpanan_model->get_jml_simpanan($jenis->id);
            $nilai_p = $this->Lapsimpanan_model->get_jml_penarikan($jenis->id);

            $simpanan_row = $nilai_s->jml_total;
            $penarikan_row = $nilai_p->jml_total;
            $sub_total = $simpanan_row - $penarikan_row;

            $simpanan_total += $simpanan_row;
            $penarikan_total += $penarikan_row;
            $simpanan_row_total += $sub_total;

            echo '<tr>';
            echo '<td class="text-center">'.$no.'</td>';
            echo '<td class="text-left">'.$jenis->jns_simpan.'</td>';
            echo '<td class="text-right">'. number_format($simpanan_row).'</td>';
            echo '<td class="text-right">'. number_format($penarikan_row) .'</td>';
            echo '<td class="text-right">'. number_format($sub_total).'</td>';
            echo '</tr>';
            $no++;
        }
        echo '<tr style="background-color:#e3dccf">
				<td colspan="2" class="text-center"><strong>Jumlah Total</strong></td>
				<td class="text-right"><strong>' . number_format($simpanan_total) . '</strong></td>
				<td class="text-right"><strong>' . number_format($penarikan_total) . '</strong></td>
				<td class="text-right"><strong>' . number_format($simpanan_row_total) . '</strong></td>
			</tr>';
        echo '</tbody></table>';
    }

    function cetak($param1 = '', $param2 = ''){
        $data_jns_simpanan = $this->Lapsimpanan_model->get_data_jenis_simpan()->result();
        $no = 1;
        $mulai = 1;
        $simpanan_arr = array();
        $simpanan_row_total = 0;
        $simpanan_total = 0;
        $penarikan_total = 0;

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

        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(277, 15, 'Laporan Saldo Kas Simpanan Periode ' . formatTglIndo($tgl_dari) . ' - ' . formatTglIndo($tgl_samp), 0, 1, 'C');

        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetFont('Arial', 'B', 10, true);

        $pdf->Cell(20, 7, 'No.', 1, 0, 'C', true);
        $pdf->Cell(77, 7, 'Jenis Akun', 1, 0, 'C', true);
        $pdf->Cell(60, 7, 'Simpanan', 1, 0, 'C', true);
        $pdf->Cell(60, 7, 'Penarikan', 1, 0, 'C', true);
        $pdf->Cell(60, 7, 'Jumlah', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10, true);
        foreach ($data_jns_simpanan as $jenis) {
            $simpanan_arr[$jenis->id] = $jenis->jns_simpan;
            $nilai_s = $this->Lapsimpanan_model->get_jml_simpanan($jenis->id);
            $nilai_p = $this->Lapsimpanan_model->get_jml_penarikan($jenis->id);

            $simpanan_row = $nilai_s->jml_total;
            $penarikan_row = $nilai_p->jml_total;
            $sub_total = $simpanan_row - $penarikan_row;

            $simpanan_total += $simpanan_row;
            $penarikan_total += $penarikan_row;
            $simpanan_row_total += $sub_total;

            $pdf->Cell(20, 7, $no, 1, 0, 'C');
            $pdf->Cell(77, 7, $jenis->jns_simpan, 1, 0, 'L');
            $pdf->Cell(60, 7, number_format($simpanan_row), 1, 0, 'R');
            $pdf->Cell(60, 7, number_format($penarikan_row), 1, 0, 'R');
            $pdf->Cell(60, 7, number_format($sub_total), 1, 1, 'R');

            $no++;
        }

        $pdf->SetFont('Arial', 'B', 10, true);
        $pdf->Cell(97, 7, 'Jumlah Total', 1, 0, 'C', true);
        $pdf->Cell(60, 7, number_format($simpanan_total), 1, 0, 'R', true);
        $pdf->Cell(60, 7, number_format($penarikan_total), 1, 0, 'R', true);
        $pdf->Cell(60, 7, number_format($simpanan_row_total), 1, 1, 'R', true);
        
        $pdf->Output();
    }

}