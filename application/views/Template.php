<!doctype html>
<html lang="en" class="no-js">
<script>
  var base_url = '<?php echo base_url() ?>';
</script>
<head>
  <title><?php
  if(!empty($this->session->userdata['page_title'])){
    echo $this->session->userdata['page_title'];
  } else {
    echo "PT Hablun Citramas Persada";
  }
  ?></title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"> -->
  <!-- Material Kit CSS -->
  <link href="<?php echo base_url('assets/'); ?>css/material-dashboard.css" rel="stylesheet" />
  <link href="<?php echo base_url('assets/'); ?>css/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url('assets/'); ?>css/style.css" rel="stylesheet" />
</head>

<body>
  <?php
  if($this->session->userdata('id_hak_akses') !== "1"){
    $style_main_panel = 'style="border: 1px solid none; background-image: url('.base_url('assets/img/2927262.jpg') .'); "';
  } else{
    $style_main_panel = "";
  }
  ?>

  <div class="wrapper " <?php echo $style_main_panel; ?> >
    <div class="sidebar" data-color="azure" data-background-color="white" data-image="<?php echo base_url(); ?>/assets/img/sidebar-3.jpg">
      <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
      <div class="logo">
        <a href="<?php echo base_url(); ?>" class="simple-text logo-normal">
          Cekin Absen
        </a>
      </div>
      <div class="sidebar-wrapper">

        <?php if($this->session->userdata('id_hak_akses') === "1"){ ?>
          <ul class="nav">
            <li class="nav-item active ">
              <a class="nav-link" href="<?php echo base_url();?>" >
                <i class="material-icons">dashboard</i>
                <p>Dashboard</p>
              </a>
            </li>

            <li class="nav-item ">
              <a class="nav-link collapsed" data-toggle="collapse" href="#profilpage" aria-expanded="false">
                <i class="material-icons">home</i>
                <p> Profil
                  <b class="caret"></b>
                </p>
              </a>
              <div class="collapse" id="profilpage" style="">
                <ul class="nav">
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('profil');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Profil Perusahaan </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('kantor');?>">
                      <i class="material-icons">corporate_fare</i>
                      <span class="sidebar-normal"> Data Kantor </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('lokasi');?>">
                      <i class="material-icons">place</i>
                      <span class="sidebar-normal"> Data Lokasi Absen </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('departemen');?>">
                      <i class="material-icons">apartment</i>
                      <span class="sidebar-normal"> Data Departemen</span>
                    </a>
                  </li>
                </ul>
              </div>
            </li>

            <li class="nav-item ">
              <a class="nav-link collapsed" data-toggle="collapse" href="#karyawanpage" aria-expanded="false">
                <i class="material-icons">people_alt</i>
                <p> Karyawan
                  <b class="caret"></b>
                </p>
              </a>
              <div class="collapse" id="karyawanpage" style="">
                <ul class="nav">
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('karyawan');?>">
                      <i class="material-icons">folder_shared</i>
                      <span class="sidebar-normal"> Data Karyawan </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('kehadiran');?>">
                      <i class="material-icons">folder_shared</i>
                      <span class="sidebar-normal"> Data Kehadiran </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('izin');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Data Pengajuan Izin </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('shiftkaryawan');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Data Shift Karyawan </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('gaji');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Data Gaji</span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('lembur');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Data Lembur</span>
                    </a>
                  </li>
                </ul>
              </div>
            </li>

            <li class="nav-item ">
              <a class="nav-link collapsed" data-toggle="collapse" href="#utilpage" aria-expanded="false">
                <i class="material-icons">list</i>
                <p> Utilitas
                  <b class="caret"></b>
                </p>
              </a>
              <div class="collapse" id="utilpage" style="">
                <ul class="nav">
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('jeniskantor');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Jenis Kantor </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('jabatan');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Data Jabatan </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('shift');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Data Shift </span>
                    </a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('jamkerja');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Jam Kerja</span>
                    </a>
                  </li>
                </ul>
              </div>
            </li>

            <li class="nav-item ">
              <a class="nav-link collapsed" data-toggle="collapse" href="#settingpage" aria-expanded="false">
                <i class="material-icons">settings</i>
                <p> Pengaturan
                  <b class="caret"></b>
                </p>
              </a>
              <div class="collapse" id="settingpage" style="">
                <ul class="nav">
                  <li class="nav-item ">
                    <a class="nav-link" href="<?php echo base_url('cuti');?>">
                      <i class="material-icons">topic</i>
                      <span class="sidebar-normal"> Cuti </span>
                    </a>
                  </li>
                </ul>
              </div>
            </li>

          </ul>
        <?php } else { ?>
          <ul class="nav">
            <li class="nav-item ">
                <a class="nav-link" href="<?php echo base_url('ubahpassword');?>">
                    <i class="material-icons">vpn_key</i>
                    <span class="sidebar-normal"> Ubah Password </span>
                </a>                        
            </li>
                  
            <li class="nav-item ">
              <a class="nav-link text-danger" href="<?php echo base_url('login/logout');?>" >
                <i class="material-icons text-danger">close</i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        <?php } ?>

      </div>
    </div>

    <div class="main-panel" >
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top " >
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <?php
            // print_r($this->session->userdata());
            if($this->session->userdata('id_hak_akses') !== "1"){
              $where = array('is_del' => 0, 'client_id' => $this->session->userdata('client_id'));
              $this->db->where($where);
              $this->db->where(array('id' => $this->session->userdata('id_karyawan')));
              $data = $this->db->get('tbl_karyawan')->row_array();
              $user_name = $data['nama_lengkap'];
            } else{
              $user_name = "Administrator";
            }
            ?>
            <div id="jam" >

            </div>
          </div>
          <?php
          // print_r($this->session->userdata());
          if($this->session->userdata('id_hak_akses') !== "1"){
             echo '<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
              <span class="sr-only">Toggle navigation</span>
              <span class="navbar-toggler-icon icon-bar"></span>
              <span class="navbar-toggler-icon icon-bar"></span>
              <span class="navbar-toggler-icon icon-bar"></span>
            </button>';
          } else{
            echo '<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
              <span class="sr-only">Toggle navigation</span>
              <span class="navbar-toggler-icon icon-bar"></span>
              <span class="navbar-toggler-icon icon-bar"></span>
              <span class="navbar-toggler-icon icon-bar"></span>
            </button>';
          }
          ?>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                <div class="ripple-container"></div></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="<?php echo base_url('administrator'); ?>">Ubah Password</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?php echo base_url('login/logout'); ?>">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="content" style="border: 1px solid none; padding-top: 8px">
        <div class="container-fluid" style="border: 1px solid none;  padding: 0; margin:0">
          <?php echo $contents; ?>
        </div>
      </div>

      <?php
      if($this->session->userdata('id_hak_akses') == "1"){ ?>
        <footer class="footer">
          <div class="container-fluid">
            <div class="copyright float-right"> Copyright
              &copy;
              <script>
                document.write(new Date().getFullYear())
              </script>,
              <a href="https://persada.web.id" target="_blank">PT Hablun Citramas Persada</a>.
            </div>
          </div>
        </footer>
      <?php } else{

      }
      ?>
    </div>
  </div>
</body>

<!--   Core JS Files   -->
  <script src="<?php echo base_url('assets/'); ?>js/core/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url('assets/'); ?>js/core/popper.min.js" type="text/javascript"></script>
  <!--  Plugin for Typeahead -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/typeahead.bundle.js"></script>

  <script src="<?php echo base_url('assets/'); ?>js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
  <!-- Plugin for the Perfect Scrollbar -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/perfect-scrollbar.jquery.min.js"></script>

  <!-- Plugin for the momentJs  -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/moment.min.js"></script>

  <!--  Plugin for Sweet Alert -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/sweetalert2.js"></script>

  <!-- Forms Validations Plugin -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/jquery.validate.min.js"></script>

  <!--  Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/jquery.bootstrap-wizard.js"></script>

  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/bootstrap-selectpicker.js" ></script>

  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <!-- <script src="<?php echo base_url('assets/'); ?>js/plugins/bootstrap-datetimepicker.min.js"></script> -->

  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/    -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/jquery.dataTables.min.js"></script>

  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/bootstrap-tagsinput.js"></script>

  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/jasny-bootstrap.min.js"></script>

  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/fullcalendar.min.js"></script>

  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/jquery-jvectormap.js"></script>

  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/nouislider.min.js" ></script>

  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>

  <!-- Library for adding dinamically elements -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/arrive.min.js"></script>

  <!--  Google Maps Plugin    -->
  <!-- <script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Bi6uRlnFw_CNwd8E9VJ7xVtAVWE3tyc"></script> -->
  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Bi6uRlnFw_CNwd8E9VJ7xVtAVWE3tyc">> -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3Bi6uRlnFw_CNwd8E9VJ7xVtAVWE3tyc" defer></script>

  <!-- </script> -->

  <!-- Chartist JS -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/chartist.min.js"></script>

  <!--  Notifications Plugin    -->
  <script src="<?php echo base_url('assets/'); ?>js/plugins/bootstrap-notify.js"></script>

  <!-- <script src="<?php echo base_url('assets/'); ?>js/plugins/daterangepicker.js"></script> -->

  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?php echo base_url('assets/'); ?>js/material-dashboard.min.js?v=2.1.2" type="text/javascript"></script>

  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>

  <script src="<?php echo base_url('assets/'); ?>js/app_js/jam.js"></script>
  <script type="text/javascript">
    var $ = jQuery;
    // $('body').removeClass('glyphicon glyphicon-calendar');
    // $('body').removeClass('glyphicon glyphicon-chevron-left');
  </script>

  <script src="<?php
                $uriSegment = $this->uri->segment(1);
                switch ($uriSegment) {
                  case "dashboard":
                    echo base_url() . "assets/js/app_js/dashboard.js";
                    break;
                  case "karyawan":
                    echo base_url() . "assets/js/app_js/karyawan.js";
                    break;
                  case "profil":
                    echo base_url() . "assets/js/app_js/profil.js";
                    break;
                  case "kantor":
                    echo base_url() . "assets/js/app_js/kantor.js";
                    break;
                  case "departemen":
                    echo base_url() . "assets/js/app_js/departemen.js";
                    break;
                  case "jeniskantor":
                    echo base_url() . "assets/js/app_js/jeniskantor.js";
                    break;
                  case "jabatan":
                    echo base_url() . "assets/js/app_js/jabatan.js";
                    break;
                  case "jamkerja":
                    echo base_url() . "assets/js/app_js/jamkerja.js";
                    break;
                  case "izin":
                    echo base_url() . "assets/js/app_js/izin.js";
                    break;
                  case "shift":
                    echo base_url() . "assets/js/app_js/shift.js";
                    break;
                  case "kehadiran":
                    echo base_url() . "assets/js/app_js/kehadiran.js";
                    break;
                  case "shiftkaryawan":
                    echo base_url() . "assets/js/app_js/shiftkaryawan.js";
                    break;
                  case "lembur":
                    echo base_url() . "assets/js/app_js/lembur.js";
                    break;
                  case "lokasi":
                    echo base_url() . "assets/js/app_js/lokasi.js";
                    break;
                  case "cuti":
                    echo base_url() . "assets/js/app_js/cuti.js";
                    break;
                  case "administrator":
                    echo base_url() . "assets/js/app_js/admin.js";
                    break;
                  case "gaji":
                    echo base_url() . "assets/js/app_js/gaji.js";
                    break;
                  case "user":
                      echo base_url() . "assets/js/app_js/user.js";
                      break;
                  case "ubahpassword":
                      echo base_url() . "assets/js/app_js/ubahpassword.js";
                      break;          
                  default:
                    echo base_url() . "assets/js/app_js/dashboard.js";
                    break;

                }
                ?>">
  </script>
  <script type="text/javascript">
    function show_pass(){
        if( $('#password').attr('type') == 'password' ){
            $('#password').attr('type', 'text');
            $('#label_pass').text('visibility_off');
        } else {
            $('#password').attr('type', 'password');
            $('#label_pass').text('visibility');
        }
    }   
  </script>

</html>
