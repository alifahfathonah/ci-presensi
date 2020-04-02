<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapbukubesar extends CI_Controller{

    public function __construct(){
        parent::__construct();
        //Codeigniter : Write Less Do More
        $this->load->model(array('Lapbukubesar_model'));
        $this->load->library(array('ion_auth', 'form_validation', 'Pdf', 'PDF_MC_Table', 'PDF_MC_Table2', 'Pdf_2'));
        $this->load->helper(array('url', 'language', 'app_helper'));

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->pdf = new FPDF('l', 'mm', 'A4');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }

    function index(){
        $this->template->load('Template', 'back/bukubesar/view_bukubesar');
    }

    function getdata($param=''){

        if ($param == null) {
            $param = Date('Y') . "-" . Date('m');
            $_REQUEST['periode'] = $param;
        } else {
            $_REQUEST['periode'] = $param;
        }

        $param = explode('-', $param);
        $thn = $param[0];
        $bln = $param[1];

        $nama_kas = $this->Lapbukubesar_model->get_nama_kas();

        echo '<h1 class="display-4 text-center" style="font-size: 24px; font-weight: bold;">
                Laporan Saldo Kas Periode ' . bulan_1($bln) . " " . $thn . '
                </h1>
                <br>';

        $total_saldo = 0;
        foreach ($nama_kas as $key) {
            $transD = $this->Lapbukubesar_model->get_transaksi_kas($key->id);
            $jmlD = 0;
            $jmlk = 0;
            $no = 1;
            $saldo = 0;   
            echo '<h1 class="display-4" style="font-size: 22px; ">' . $key->nama . '</h1>
                <div class="table-responsive">
                    <table class="table table-sm small table-bordered" style="width:100%">
                        <thead style="background-color:#e3dccf">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="10%" class="text-center">Tanggal</th>
                                <th width="20%" class="text-center">Jenis Transaksi</th>
                                <th width="30%" class="text-center">Keterangan</th>
                                <th width="10%" class="text-center">Debet</th>
                                <th width="10%" class="text-center">Kredit</th>
                                <th width="15%" class="text-center">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($transD as $rows){
                $nm_akun = $this->Lapbukubesar_model->get_nama_akun_id($rows->transaksi);
                $tglD = explode(' ', $rows->tgl);
                $txt_tanggalD = jin_date_ina($tglD[0], 'p');

                if ($rows->dari_kas == $key->id) {
                    $jmlk += $rows->kredit;
                    $rows->debet = 0;
                }
                if ($rows->untuk_kas == $key->id) {
                    $jmlD += $rows->debet;
                    $rows->kredit = 0;
                }
                $saldo = $jmlD - $jmlk;
                echo '<tr>
					    <td class="text-center"> ' . $no++ . ' </td>
                        <td class="text-center"> ' . $txt_tanggalD . ' </td>
                        <td> ' . @$nm_akun->jns_trans . '</td>
                        <td> ' . $rows->ket . '</td>
                        <td class="text-right"> ' . rupiah(nsi_round($rows->debet)) . ' </td>
                        <td class="text-right"> ' . rupiah(nsi_round($rows->kredit)) . ' </td>
                        <td class="text-right"> ' . rupiah(nsi_round($saldo)) . ' </td>
				    </tr>';
            }
            $total_saldo += $saldo;
            echo        '</tbody>
                    </table>
                </div>'; 
        }        
        echo '<br><table  class="table table-bordered">
				<tr style="background-color:#e3dccf">
					<td class="text-right" style="font-weight: bold;">TOTAL SALDO KAS BANK</td>
					<td class="text-right" style="font-weight: bold;">'. rupiah(nsi_round($total_saldo)).'</td>
					
				</tr>
			</table>';
    }

    function cetak($param = ''){
        if ($param == null) {
            $param = Date('Y') . "-" . Date('m');
            $_REQUEST['periode'] = $param;
        } else {
            $_REQUEST['periode'] = $param;
        }

        $param = explode('-', $param);
        $thn = $param[0];
        $bln = $param[1];

        $nama_kas = $this->Lapbukubesar_model->get_nama_kas();

        $pdf = new PDF_MC_Table2('l', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->addPage();

        $pdf->SetFont('Arial', 'B', 17);
        $pdf->Cell(277, 15, 'Laporan Buku Besar Kas Periode ' .  bulan_1($bln) . ' ' . $thn, 0, 1, 'C');

        $total_saldo = 0;

        foreach ($nama_kas as $key) {
            $transD = $this->Lapbukubesar_model->get_transaksi_kas($key->id);
            $jmlD = 0;
            $jmlk = 0;
            $no = 1;
            $saldo = 0;
            $pdf->SetFont('Arial', 'B', 16);
            
            $pdf->Cell(0, 7, $key->nama, 0, 1, 'L');

            $pdf->SetFillColor(192, 192, 192);

            $pdf->SetFont('Arial', 'B', 10, true);
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C', true);
            $pdf->Cell(27, 7, 'Tanggal', 1, 0, 'C', true);
            $pdf->Cell(50, 7, 'Jenis Transaksi', 1, 0, 'C', true);
            $pdf->Cell(100, 7, 'Keterangan', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Debet', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Kredit', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Saldo', 1, 1, 'C', true);

            foreach ($transD as $rows) {
                $nm_akun = $this->Lapbukubesar_model->get_nama_akun_id($rows->transaksi);
                $tglD = explode(' ', $rows->tgl);
                $txt_tanggalD = jin_date_ina($tglD[0], 'p');

                if ($rows->dari_kas == $key->id) {
                    $jmlk += $rows->kredit;
                    $rows->debet = 0;
                }
                if ($rows->untuk_kas == $key->id) {
                    $jmlD += $rows->debet;
                    $rows->kredit = 0;
                }
                $saldo = $jmlD - $jmlk;

                $pdf->SetFont('Arial', '', 10);

                $pdf->SetWidths(array(10, 27, 50, 100, 30, 30, 30));
                $pdf->SetAligns(array('C', 'C', 'L', 'L', 'R', 'R', 'R'));
                $pdf->Row(array(
                    $no++,
                    $txt_tanggalD,
                    @$nm_akun->jns_trans,
                    $rows->ket,
                    rupiah(nsi_round($rows->debet)),
                    rupiah(nsi_round($rows->kredit)),
                    rupiah(nsi_round($saldo))
                ));
            }
            $pdf->Cell(0, 7, '', 0, 1, 'L');
            $total_saldo += $saldo;
        }
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 14, true);
        $pdf->Cell(217, 7, 'TOTAL SALDO KAS BANK', 1, 0, 'R', true);
        $pdf->Cell(60, 7, rupiah(nsi_round($total_saldo)), 1, 1, 'R', true);

        $pdf->Output();
    }
}