<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/third_party/fpdf181/fpdf.php';
class PDF_MC_Table extends FPDF {

  public function method(){
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  // variable to store widths and aligns of cells, and line height
  var $widths;
  var $aligns;
  var $lineHeight;

  //Set the array of column widths
  function SetWidths($w){
    $this->widths=$w;
  }

  //Set the array of column alignments
  function SetAligns($a){
    $this->aligns=$a;
  }

  //Set line height
  function SetLineHeight($h){
    $this->lineHeight=$h;
  }

  //Calculate the height of the row
  function Row($data)
  {
    // number of line
    $nb=0;

    // loop each data to find out greatest line number in a row.
    for($i=0;$i<count($data);$i++){
      // NbLines will calculate how many lines needed to display text wrapped in specified width.
      // then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
      $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    }

    //multiply number of line with line height. This will be the height of current row
    $h=$this->lineHeight * $nb;

    //Issue a page break first if needed
    $this->CheckPageBreak($h);

    //Draw the cells of current row
    for($i=0;$i<count($data);$i++)
    {
      // width of the current col
      $w=$this->widths[$i];
      // alignment of the current col. if unset, make it left.
      $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
      //Save the current position
      $x=$this->GetX();
      $y=$this->GetY();
      //Draw the border
      $this->Rect($x,$y,$w,$h);
      //Print the text
      $this->MultiCell($w,5,$data[$i],0,$a);
      //Put the position to the right of the cell
      $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
  }

  function CheckPageBreak($h)
  {
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
    $this->AddPage($this->CurOrientation);
  }

  function NbLines($w,$txt)
  {
    //calculate the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
    $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
    $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
      $c=$s[$i];
      if($c=="\n")
      {
        $i++;
        $sep=-1;
        $j=$i;
        $l=0;
        $nl++;
        continue;
      }
      if($c==' ')
      $sep=$i;
      $l+=$cw[$c];
      if($l>$wmax)
      {
        if($sep==-1)
        {
          if($i==$j)
          $i++;
        }
        else
        $i=$sep+1;
        $sep=-1;
        $j=$i;
        $l=0;
        $nl++;
      }
      else
      $i++;
    }
    return $nl;
  }

  function Header(){
    $this->SetFont('Arial','B',15);

    $this->Image(base_url().'assets\img\logo.png',10,10,-180);

    $this->cell(30, 0, '', 0, 0);
    $this->cell(247, 5, 'KOPERASI SERBA USAHA SAKRA WARIH', 0, 1);

    $this->SetFont('Arial','', 8.5);
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


  function Footer(){
    // Go to 1.5 cm from bottom
    $this->SetY(-15);
    // Select Arial italic 8
    $this->SetFont('Arial','I',8);
    // Print current and total page numbers
    $this->Line(10, 197, 287, 197);
    $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'L');
    $this->Cell(0,10,'Tanggal Cetak : ' . Date('d M Y H:m:s'),0,0,'R');
  }

}
