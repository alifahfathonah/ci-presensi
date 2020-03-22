<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laptranskas extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model(array('Lap_trans_kas_model'));
        $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table', 'PDF_MC_Table2', 'Pdf_2'));
        $this->load->helper(array('url', 'language', 'app_helper'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    function index(){
        $this->template->load('Template', 'back/kas/view_lap_trans_kas');
    }

    public function ajax_list(){
        $list = $this->Lap_trans_kas_model->get_datatables();
        $data = array();
        $i=1;
        $no = $_POST['start'];

        $saldo_awal  = $this->Lap_trans_kas_model->get_saldo_awal($_POST['length'], $_POST['start']);
        $saldo_sblm  = $this->Lap_trans_kas_model->get_saldo_sblm();

        $count_saldo = array('' , '', '', '', '', '', '', 
        '<h1 class="display-4" style="font-size: 17px; font-weight: bold">Saldo Sebelumnya</h1>',
        '<h1 class="display-4" style="font-size: 17px; font-weight: bold">'. rupiah(nsi_round($saldo_awal + $saldo_sblm)) .'</h1>');

        $data[] = $count_saldo;
        $saldo = $saldo_awal + $saldo_sblm;
        foreach ($list as $r) {
            $saldo += ($r->debet - $r->kredit);

            switch ($r->tbl) {
                case 'A':
                    $kode = 'TPJ';
                    break;

                case 'B':
                    $kode = 'TBY';
                    break;

                case 'C':
                    if ($r->dari_kas == NULL) {
                        $kode = 'TRD';
                    } else {
                        $kode = 'TRK';
                    }
                    break;

                case 'D':
                    $kode = 'TRF';
                    if ($r->dari_kas == NULL) {
                        $kode = 'TKD';
                    }
                    if ($r->untuk_kas == NULL) {
                        $kode = 'TKK';
                    }
                    break;

                default:
                    $kode = '';
                    break;
            }

            $dari_kas = $this->Lap_trans_kas_model->get_nama_kas_id($r->dari_kas);
            $untuk_kas = $this->Lap_trans_kas_model->get_nama_kas_id($r->untuk_kas);

            if ($r->dari_kas == NULL) {
                $dari_kas = '-';
            } else {
                $dari_kas = $dari_kas->nama;
            }

            if ($r->untuk_kas == NULL) {
                $untuk_kas = '-';
            } else {
                $untuk_kas = $untuk_kas->nama;
            }

            $row = array();
            $row[] = $no+1;
            $row[] = $kode . sprintf('%05d', $r->id);
            $row[] = substr(formatTglIndo_datetime_3($r->tgl), 0, 11);
            $row[] = $r->jns_trans . "<br> " . "<code>" . $r->ket . "</code>";
            $row[] = $dari_kas;
            $row[] = $untuk_kas;
            $row[] = rupiah($r->debet);
            $row[] = rupiah($r->kredit);
            $row[] = rupiah(nsi_round($saldo));
            $data[] = $row;

            $no++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Lap_trans_kas_model->count_all(),
            "recordsFiltered" => $this->Lap_trans_kas_model->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function cetak($start_date, $end_date){
        $_POST['start_date'] = $start_date;
        $_POST['end_date']   = $end_date;
        $pdf = new PDF_MC_Table2('l', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->addPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(277, 15, 'Laporan Data Kas Periode ' . formatTglIndo($start_date) .' - '. formatTglIndo($end_date), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 9);

        

        $pdf->SetFillColor(210, 221, 242);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 5, 'No.', 1, 0, 'C', TRUE);
        $pdf->Cell(25, 5, 'Tanggal', 1, 0, 'C', TRUE);
        $pdf->Cell(45, 5, 'Jenis Transaksi', 1, 0, 'C', TRUE);
        $pdf->Cell(45, 5, 'Keterangan', 1, 0, 'C', TRUE);
        $pdf->Cell(30, 5, 'Dari Kas', 1, 0, 'C', TRUE);
        $pdf->Cell(30, 5, 'Untuk Kas', 1, 0, 'C', TRUE);
        $pdf->Cell(30, 5, 'Debet', 1, 0, 'C', TRUE);
        $pdf->Cell(30, 5, 'Kredit', 1, 0, 'C', TRUE);
        $pdf->Cell(30, 5, 'Saldo', 1, 0, 'C', TRUE);

        $pdf->Ln();
        $pdf->SetAligns(array('C', 'C', 'L', 'L', 'L', 'L', 'R', 'R', 'R', 'R', 'R'));
        
        $list = $this->Lap_trans_kas_model->get_datatables_cetak($start_date, $end_date);
        $saldo_awal  = $this->Lap_trans_kas_model->get_saldo_awal('', 0);
        $saldo_sblm  = $this->Lap_trans_kas_model->get_saldo_sblm();

        $i = 1;
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetWidths(array(0, 0, 0, 0, 0, 0, 0, 245, 30));
        
        $pdf->Row(array(
            '', '', '', '', '', '', '', 'SALDO SEBELUMNYA',
            rupiah(nsi_round($saldo_awal + $saldo_sblm))
        ));

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetWidths(array(10, 25, 45, 45, 30, 30, 30, 30, 30));

        $saldo = $saldo_awal + $saldo_sblm;
        foreach ($list as $r) {
            $saldo += ($r->debet - $r->kredit);

            switch ($r->tbl) {
                case 'A':
                    $kode = 'TPJ';
                    break;

                case 'B':
                    $kode = 'TBY';
                    break;

                case 'C':
                    if ($r->dari_kas == NULL) {
                        $kode = 'TRD';
                    } else {
                        $kode = 'TRK';
                    }
                    break;

                case 'D':
                    $kode = 'TRF';
                    if ($r->dari_kas == NULL) {
                        $kode = 'TKD';
                    }
                    if ($r->untuk_kas == NULL) {
                        $kode = 'TKK';
                    }
                    break;

                default:
                    $kode = '';
                    break;
            }

            $dari_kas = $this->Lap_trans_kas_model->get_nama_kas_id($r->dari_kas);
            $untuk_kas = $this->Lap_trans_kas_model->get_nama_kas_id($r->untuk_kas);

            if ($r->dari_kas == NULL) {
                $dari_kas = '-';
            } else {
                $dari_kas = $dari_kas->nama;
            }

            if ($r->untuk_kas == NULL) {
                $untuk_kas = '-';
            } else {
                $untuk_kas = $untuk_kas->nama;
            }

            $pdf->Row(array(
                $i,
                substr(formatTglIndo_datetime_3($r->tgl), 0, 11),
                $r->jns_trans,
                $r->ket,
                $dari_kas,
                $untuk_kas,
                rupiah($r->debet),
                rupiah($r->kredit),
                rupiah(nsi_round($saldo))
            ));

            $i++;
        }

        $pdf->Output();
    }
}