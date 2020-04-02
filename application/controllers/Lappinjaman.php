<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lappinjaman extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Lap_pinjaman_model'));
        $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table', 'PDF_MC_Table2', 'Pdf_2'));
        $this->load->helper(array('url', 'language', 'app_helper'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    function index(){
        $this->template->load('Template', 'back/pinjaman/view_lappinjaman');
    }

    function getdata($param1='', $param2=''){
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

        $jml_pinjaman = $this->Lap_pinjaman_model->get_jml_pinjaman();
        $jml_tagihan  = $this->Lap_pinjaman_model->get_jml_tagihan();
        $jml_angsuran = $this->Lap_pinjaman_model->get_jml_angsuran();
        $jml_denda    = $this->Lap_pinjaman_model->get_jml_denda();

        $peminjam_aktif = $this->Lap_pinjaman_model->get_peminjam_aktif();
        $peminjam_lunas = $this->Lap_pinjaman_model->get_peminjam_lunas();
        $peminjam_belum = $this->Lap_pinjaman_model->get_peminjam_belum();
        $tot_tagihan    = $jml_tagihan->jml_total + $jml_denda->total_denda;
        echo '
        <h1 class="display-4 text-center" style="font-size: 25px" id="label_page">Laporan Pinjaman Periode '.formatTglIndo($tgl_dari).' - '.formatTglIndo($tgl_samp).'</h1>
                <br>
                <table width="20%">
                    <tr>
                        <td> Jumlah Peminjam </td>
                        <td> : </td>
                        <td> '. $peminjam_aktif . ' </td>
                    </tr>
                    <tr>
                        <td> Peminjam Lunas </td>
                        <td> : </td>
                        <td> ' . $peminjam_lunas . ' </td>
                    </tr>
                    <tr>
                        <td> Pinjaman Belum Lunas </td>
                        <td> : </td>
                        <td> ' . $peminjam_belum . ' </td>
                    </tr>
                </table>
                <br>
                <table class="table table-bordered table-sm small">
                    <tr style="background-color:#e3dccf">
                        <th style=" width:5%; vertical-align: middle; text-align:center"> No. </th>
                        <th style="width:35%; vertical-align: middle; text-align:center">Keterangan </th>
                        <th style="width:20%; vertical-align: middle; text-align:center"> Jumlah </th>
                    </tr>
                    <tr>
                        <td class="text-center"> 1 </td>
                        <td> Pokok Pinjaman</td>
                        <td class="text-right">' . rupiah(nsi_round($jml_pinjaman->jml_total)). '</td>
                    </tr>
                    <tr>
                        <td class="text-center"> 2 </td>
                        <td> Tagihan Pinjaman</td>
                        <td class="text-right">' . rupiah(nsi_round($jml_tagihan->jml_total)). '</td>
                    </tr>
                    <tr>
                        <td class="text-center"> 3 </td>
                        <td> Tagihan Denda </td>
                        <td class="text-right">' . rupiah(nsi_round($jml_denda->total_denda)). '</td>
                    </tr>
                    <tr style="background-color:#e3dccf; font-weight: bold;">
                        <td class="text-center">  </td>
                        <td> Jumlah Tagihan + Denda </td>
                        <td class="text-right">'. rupiah(nsi_round($tot_tagihan)) . '</td>
                    </tr>
                    <tr>
                        <td class="text-center"> 4 </td>
                        <td> Tagihan Sudah Dibayar </td>
                        <td class="text-right">' . rupiah(nsi_round($jml_angsuran->jml_total)). '</td>
                    </tr>
                    <tr style="background-color: #98FB98 ;">
                        <td class="text-center"> 5 </td>
                        <td> Sisa Tagihan </td>
                        <td class="text-right">' . rupiah(nsi_round($tot_tagihan -$jml_angsuran->jml_total)). '</td>
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

        $pdf = new PDF_MC_Table2('l', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->addPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(277, 15, 'Laporan Pinjaman Periode ' . formatTglIndo($tgl_dari) . ' - ' . formatTglIndo($tgl_samp), 0, 1, 'C');

        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetFont('Arial', 'B', 10, true);
        $pdf->Cell(20, 7, 'No.', 0, 0, 'C', true);
        $pdf->Cell(155, 7, 'Keterangan', 0, 0, 'C', true);
        $pdf->Cell(102, 7, 'Jumlah', 0, 1, 'C', true);

        $jml_pinjaman = $this->Lap_pinjaman_model->get_jml_pinjaman();
        $jml_tagihan  = $this->Lap_pinjaman_model->get_jml_tagihan();
        $jml_angsuran = $this->Lap_pinjaman_model->get_jml_angsuran();
        $jml_denda    = $this->Lap_pinjaman_model->get_jml_denda();

        $peminjam_aktif = $this->Lap_pinjaman_model->get_peminjam_aktif();
        $peminjam_lunas = $this->Lap_pinjaman_model->get_peminjam_lunas();
        $peminjam_belum = $this->Lap_pinjaman_model->get_peminjam_belum();
        $tot_tagihan    = $jml_tagihan->jml_total + $jml_denda->total_denda;

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 7, '1.', 0, 0, 'C');
        $pdf->Cell(155, 7, 'Pokok Pinjaman', 0, 0, 'L');
        $pdf->Cell(102, 7, rupiah(nsi_round($jml_pinjaman->jml_total)), 0, 1, 'R');

        $pdf->Cell(20, 7, '2.', 0, 0, 'C');
        $pdf->Cell(155, 7, 'Tagihan  Pinjaman', 0, 0, 'L');
        $pdf->Cell(102, 7, rupiah(nsi_round($jml_tagihan->jml_total)), 0, 1, 'R');

        $pdf->Cell(20, 7, '3.', 0, 0, 'C');
        $pdf->Cell(155, 7, 'Tagihan Denda', 0, 0, 'L');
        $pdf->Cell(102, 7, rupiah(nsi_round($jml_denda->total_denda)), 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 7, '', 0, 0, 'C', true);
        $pdf->Cell(155, 7, 'Jumlah Tagihan + Denda', 0, 0, 'L', true);
        $pdf->Cell(102, 7, rupiah(nsi_round($tot_tagihan)), 0, 1, 'R', true);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(20, 7, '4.', 0, 0, 'C');
        $pdf->Cell(155, 7, 'Tagihan Sudah Dibayar', 0, 0, 'L');
        $pdf->Cell(102, 7, rupiah(nsi_round($jml_angsuran->jml_total)), 0, 1, 'R');

        $pdf->SetFillColor(153, 255, 153);

        $pdf->Cell(20, 7, '5.', 0, 0, 'C', true);
        $pdf->Cell(155, 7, 'Sisa Tagihan', 0, 0, 'L', true);
        $pdf->Cell(102, 7, rupiah(nsi_round($tot_tagihan - $jml_angsuran->jml_total)), 0, 1, 'R', true);

        $pdf->Output();
    }
}