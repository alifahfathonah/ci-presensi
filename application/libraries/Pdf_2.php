<?php
defined('BASEPATH') or exit('No direct script access allowed');
Class Pdf_2 extends PDF_MC_Table{
  protected $imageKey = '';

  public function setImageKey($key){
    $this->imageKey = $key;
  }

  public function Row($data){
    $nb=0;
    for($i=0;$i<count($data);$i++)
      $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
      $h=5*$nb;
      $this->CheckPageBreak($h);
      for($i=0;$i<count($data);$i++){
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        $x=$this->GetX();
        $y=$this->GetY();
        $this->Rect($x,$y,$w,$h);

        //modify functions for image 
        if(!empty($this->imageKey) && in_array($i,$this->imageKey)){
          $ih = $h - 0.5;
          $iw = $w - 0.5;
          $ix = $x + 0.25;
          $iy = $y + 0.25;
          $this->MultiCell($w,5,$this->Image ($data[$i],$ix,$iy,$iw,$ih),0,$a);
        }
        else
          $this->MultiCell($w,5,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
      }
      $this->Ln($h);
    }
}

