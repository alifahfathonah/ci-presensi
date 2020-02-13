<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pdf {

  // public function method(){
  //   parent::__construct();
  //   include_once APPPATH . '/third_party/fpdf181/fpdf.php';
  // }

  function __construct() {
    include_once APPPATH . '/third_party/fpdf181/fpdf.php';
  }

}
