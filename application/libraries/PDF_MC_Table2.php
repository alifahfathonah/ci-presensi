<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once APPPATH . '/third_party/fpdf181/fpdf.php';
class PDF_MC_Table2 extends FPDF
{
    var $widths;
    var $aligns;

    function SetWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data)
    {
        //Calculate the height of the row
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            $this->Rect($x, $y, $w, $h);
            //Print the text
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }



    function Header()
    {
        $this->SetFont('Arial', 'B', 15);

        $this->Image(base_url() . 'assets\img\logo.png', 10, 10, -180);

        $this->cell(30, 0, '', 0, 0);
        $this->cell(247, 5, 'KOPERASI SERBA USAHA SAKRA WARIH', 0, 1);

        $this->SetFont('Arial', '', 8.5);
        $this->cell(30, 0, '', 0, 0);
        $this->cell(247, 5, 'Jl. Dr. Suharso No.52 Purwokerto', 0, 1);

        $this->cell(30, 0, '', 0, 0);
        $this->cell(247, 5, 'Tel.0281-632324 Email : ksu_sakrawarih@gmail.com', 0, 1);

        $this->cell(30, 0, '', 0, 0);
        $this->cell(247, 5, 'Web : -', 0, 1);

        $this->Line(10, 34, 287, 34);
        // $this->cell(277, 1, '', 0, 1);
        // Line break
        $this->Ln();
    }


    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print current and total page numbers
        $this->Line(10, 197, 287, 197);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . ' dari {nb}', 0, 0, 'L');
        $this->Cell(0, 10, 'Tanggal Cetak : ' . Date('d M Y H:m:s'), 0, 0, 'R');
    }

    function NbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}
