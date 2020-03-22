<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasanggota extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model(array('Lap_kas_anggota_model', 'Klien_korporasi_model', 'Kas_anggota_model'));
        $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table', 'PDF_MC_Table2', 'Pdf_2'));
        $this->load->helper(array('url', 'language', 'app_helper'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    function index(){
        redirect("kasanggota/lists");
    }

    function lists(){
        $this->session->unset_userdata('id_anggota');
        $data['model']             = $this->Lap_kas_anggota_model->get_data_anggota();
        $data['data_jns_simpanan'] = $this->Lap_kas_anggota_model->get_jenis_simpan();
        $this->template->load('Template', 'back/kas/view_kas_anggota', $data);
    }

    function cari($param=null){
        if($param !== null){
            $newdata = array(
                'id_anggota'     => $param,
            );
            $this->session->set_userdata($newdata);
            
            $data['model']             = $this->Lap_kas_anggota_model->get_data_anggota($param);
            $data['data_jns_simpanan'] = $this->Lap_kas_anggota_model->get_jenis_simpan();

            $this->template->load('Template', 'back/kas/view_kas_anggota', $data);
        } else {
            redirect('kasanggota');
        }
        
        // redirect("kasanggota/lists");
    }

    function fetch_autocomplete(){
        echo $this->Kas_anggota_model->fetch_autocomplete_data($this->uri->segment(3));
    }

    function fetch_autocomplete_($r){
        echo $this->Kas_anggota_model->fetch_autocomplete_data($r);
    }

    public function cetak(){
        $pdf = new PDF_MC_Table2('l', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->addPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(277, 15, 'Laporan Data Kas Anggota', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 9);

        $pdf->SetWidths(array(12, 25, 80, 40, 40, 40, 40));

        $pdf->SetFillColor(210, 221, 242);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(12, 5, 'No.', 1, 0, 'C', TRUE);
        $pdf->Cell(25, 5, 'Photo', 1, 0, 'C', TRUE);
        $pdf->Cell(80, 5, 'Identitas', 1, 0, 'C', TRUE);
        $pdf->Cell(80, 5, 'Kas Simpanan', 1, 0, 'C', TRUE);
        $pdf->Cell(80, 5, 'Tagihan Pinjaman', 1, 0, 'C', TRUE);

        $pdf->Ln();
        $pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'L', 'R'));

        $param_id = $this->session->userdata('id_anggota');

        if($param_id !== ""){
            $list = $this->Lap_kas_anggota_model->get_data_anggota($param_id, "CETAK");
        } else {
            $list = $this->Lap_kas_anggota_model->get_data_anggota(null, "CETAK");
        }

        $data_jns_simpanan = $this->Lap_kas_anggota_model->get_jenis_simpan();
        $i = 1;

        $pdf->SetFont('Arial', '', 8);
        foreach ($list['anggota'] as $anggota) {

            if ($anggota->jk == "L") {
                $gender = "Laki-laki";
            } else {
                $gender = "Perempuan";
            }

            //jabatan
            if ($anggota->jabatan_id == "1") {
                $jabatan = "Pengurus";
            } else {
                $jabatan = "Anggota";
            }

            $field_identitas = array(
                $anggota->nama,
                $anggota->identitas,
                $gender,
                $jabatan . ' - ' . $anggota->departement,
                $anggota->alamat . ", " . $anggota->kota,
                "Telp. ".$anggota->notelp 
            );

            
            $simpanan_arr = array();
            $simpanan_row_total = 0;
            $simpanan_total = 0;

            $field_kas_simpanan_label = array();
            $field_kas_simpanan_data = array();
            $ii = 1;
            foreach ($data_jns_simpanan as $jenis) {
                $simpanan_arr[$jenis->id] = $jenis->jns_simpan;
                $nilai_s = $this->Lap_kas_anggota_model->get_jml_simpanan($jenis->id, $anggota->id);
                $nilai_p = $this->Lap_kas_anggota_model->get_jml_penarikan($jenis->id, $anggota->id);

                $simpanan_row = $nilai_s->jml_total - $nilai_p->jml_total;
                $simpanan_row_total += $simpanan_row;
                $simpanan_total += $simpanan_row_total;

                array_push($field_kas_simpanan_data, rupiah($simpanan_row));
                array_push($field_kas_simpanan_label, $jenis->jns_simpan);
            }

            array_push($field_kas_simpanan_data, rupiah($simpanan_row_total));
            array_push($field_kas_simpanan_label, "Jumlah Simpanan");

            $field_tagihan_pinjaman_label = array();
            $field_tagihan_pinjamann_data = array();

            $pinjaman = $this->Lap_kas_anggota_model->get_data_pinjam($anggota->id)->row_array();
            $pinjam_id = @$pinjaman['id'];
            $anggota_id = @$pinjaman['anggota_id'];

            $jml_pj = $this->Lap_kas_anggota_model->get_jml_pinjaman($anggota_id)->row_array();
            $pj_anggota = @$jml_pj['total'];

            $denda = $this->Lap_kas_anggota_model->get_jml_denda($pinjam_id)->row_array();
            $tagihan = @$pinjaman['tagihan'] + $denda['total_denda'];

            $dibayar = $this->Lap_kas_anggota_model->get_jml_bayar($pinjam_id)->row_array();
            $sisa_tagihan = $tagihan - $dibayar['total'];

            $field_tagihan_pinjaman_label = array('Pokok Pinjaman', 'Tagihan + Denda', 'Dibayar', 'Sisa Tagihan');
            $field_tagihan_pinjamann_data = array(rupiah(nsi_round($pinjaman['jumlah'])), rupiah(nsi_round($tagihan)), rupiah(nsi_round($dibayar['total'])), rupiah(nsi_round($sisa_tagihan)) );
            $photo     = ($anggota->file_pic == null || $anggota->file_pic == "") ? base_url() . 'uploads/photo_default.jpg' : base_url() . 'uploads/anggota/' . $anggota->file_pic;
            $pdf->Row(array(
                $i,
                "-",
                $this->GenerateSentence($field_identitas),
                $this->GenerateSentence($field_kas_simpanan_label),
                $this->GenerateSentence($field_kas_simpanan_data),
                $this->GenerateSentence($field_tagihan_pinjaman_label),
                $this->GenerateSentence($field_tagihan_pinjamann_data),
            ));

            $i++;
        }

        $pdf->Output();
    }

    function GenerateSentence($parameter){
        $s = '';
        for ($i = 0; $i < sizeof($parameter); $i++)
            $s .=  $parameter[$i]. "\n";
        return $s;
    }
    
}