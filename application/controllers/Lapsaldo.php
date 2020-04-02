<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapsaldo extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Lap_saldo_model'));
        $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table', 'PDF_MC_Table2', 'Pdf_2'));
        $this->load->helper(array('url', 'language', 'app_helper'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    function index()
    {
        // if(isset($_POST["parameter"])){
        //     $param = $_POST["parameter"];
        // } else {
        // }
        $param = Date('Y') . "-" . Date('m');
        $_REQUEST['periode'] = $param;    
        $data['jenis_kas']  = $this->Lap_saldo_model->get_data_jenis_kas()->result();
        $data['saldo_sblm'] = $this->Lap_saldo_model->get_saldo_sblm();
        $this->template->load('Template', 'back/kas/view_lap_saldo', $data);
    }

    function cari($param)
    {
        if($param == null){
            redirect("lapsaldo");
        } else {
            $_REQUEST['periode'] = $param;
            $data['jenis_kas']  = $this->Lap_saldo_model->get_data_jenis_kas()->result();
            $data['saldo_sblm'] = $this->Lap_saldo_model->get_saldo_sblm();
            $this->template->load('Template', 'back/kas/view_lap_saldo', $data);
        }
    }

    function getdata($param=''){
        if ($param == null) {
            $param = Date('Y')."-".Date('m');
        }

        $tgl_arr = explode('-', $param);
        $thn = $tgl_arr[0];
        $bln = $tgl_arr[1];

        $_REQUEST['periode'] = $param;
        $jenis_kas  = $this->Lap_saldo_model->get_data_jenis_kas()->result();
        $saldo_sblm = $this->Lap_saldo_model->get_saldo_sblm();

        echo '<h1 class="display-4 text-center" style="font-size: 24px; font-weight: bold;">
                Laporan Saldo Kas Periode ' . bulan_1($bln) . " " . $thn . '
                </h1>
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
                                <td class="text-right"><strong>'. rupiah(nsi_round($saldo_sblm)) .'</strong></td>
                            </tr> ';
        $no = 0 + 1;

        $kas_arr = array();
        $debet_total = 0;
        $kredit_total = 0;
        $saldo_total = 0;
        foreach ($jenis_kas as $jenis) {

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

        echo '<tr class="table-active">
                                <td></td>
                                <td class="text-right"><strong>Jumlah</strong></td>
                                <td class="text-right"><strong>'. rupiah(nsi_round($saldo_total)) .'</strong></td>
                            </tr>
                            <tr class="table-success">
                                <td></td>
                                <td class="text-right"><strong>Saldo</strong></td>
                                <td class="text-right"><strong>'. rupiah(nsi_round($saldo_total + $saldo_sblm)) .'</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>';
    }

    function cetak($param=''){
        if ($param == null) {
            $param = Date('Y') . "-" . Date('m');
        }

        $tgl_arr = explode('-', $param);
        $thn = $tgl_arr[0];
        $bln = $tgl_arr[1];

        $_REQUEST['periode'] = $param;
        $jenis_kas  = $this->Lap_saldo_model->get_data_jenis_kas()->result();
        $saldo_sblm = $this->Lap_saldo_model->get_saldo_sblm();

        $pdf = new PDF_MC_Table2('l', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->addPage();

        $pdf->SetFont('Arial', 'B', 17);
        $pdf->Cell(277, 15, 'Laporan Saldo Kas Periode ' .  bulan_1($bln) . ' ' . $thn, 0, 1, 'C');

        $pdf->SetFont('Arial', '', 16);

        $pdf->SetFillColor(210, 221, 242);

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(20, 10, 'No.', 1, 0, 'C', TRUE);
        $pdf->Cell(157, 10, 'Nama Kas', 1, 0, 'C', TRUE);
        $pdf->Cell(100, 10, 'Saldo', 1, 0, 'C', TRUE);

        $pdf->Ln();
        $pdf->SetAligns(array('C', 'C', 'C'));

        $i = 1;
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetWidths(array(20, 157, 100));

        $pdf->Cell(177, 10, 'SALDO PERIODE SEBELUMNYA', 1, 0, 'R');
        $pdf->Cell(100, 10, rupiah(nsi_round($saldo_sblm)), 1, 1, 'R');

        $pdf->SetWidths(array(20, 157, 100));

        $no = 0 + 1;

        $kas_arr = array();
        $debet_total = 0;
        $kredit_total = 0;
        $saldo_total = 0;

        foreach ($jenis_kas as $jenis) {
            $kas_arr[$jenis->id] = $jenis->nama;
            $nilai_debet = $this->Lap_saldo_model->get_jml_debet($jenis->id);
            $nilai_kredit = $this->Lap_saldo_model->get_jml_kredit($jenis->id);

            $debet_row = $nilai_debet->jml_total;
            $kredit_row = $nilai_kredit->jml_total;
            $saldo_row = $debet_row - $kredit_row;

            $saldo_total += $saldo_row;

            $pdf->SetFont('Arial', '', 16);
            $pdf->Cell(20, 10, $no++, 1, 0, 'C');
            $pdf->Cell(157, 10, $jenis->nama, 1, 0, 'L');
            $pdf->Cell(100, 10, rupiah(nsi_round($saldo_row)), 1, 1, 'R');

        }

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(177, 10, 'Jumlah', 1, 0, 'R', TRUE);
        $pdf->Cell(100, 10, rupiah(nsi_round($saldo_total)), 1, 1, 'R', TRUE);

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(177, 10, 'Total Saldo', 1, 0, 'R', TRUE);
        $pdf->Cell(100, 10, rupiah(nsi_round($saldo_total + $saldo_sblm)), 1, 1, 'R', TRUE);

        $pdf->Output();
    }

}