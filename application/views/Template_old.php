<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<script>
  var base_url = '<?php echo base_url() ?>';
</script>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HCP Presensi</title>
  <meta name="description" content="Inventori Online">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" href="apple-icon.png">
  <link rel="shortcut icon" href="favicon.ico">

  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>vendors/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>vendors/themify-icons/css/themify-icons.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>vendors/selectFX/css/cs-skin-elastic.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>vendors/jqvmap/dist/jqvmap.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/bootstrap-datepicker3.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/typehead_example.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>css/jquery.datetimepicker.css">
  <!-- <link rel="stylesheet" href="https://twitter.github.io/typeahead.js/css/examples.css" /> -->


  <link rel="stylesheet" href="<?php echo base_url('assets/template/backend/'); ?>assets/css/style.css">

  <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'> -->

  <style>
    .glyphicon {
      font-family: "Glyphicons Halflings"
    }

    .export_button_group_container {
      padding-right: 0px;
    }

    .pol_kiri {
      padding-left: 0px;
    }

    .tracking-detail {
      padding: 3rem 0
    }

    #tracking {
      margin-bottom: 1rem
    }

    [class*=tracking-status-] p {
      margin: 0;
      font-size: 1.1rem;
      color: #fff;
      text-transform: uppercase;
      text-align: center
    }

    [class*=tracking-status-] {
      padding: 0.85rem 0
    }

    .tracking-status-intransit {
      background-color: #65aee0
    }

    .tracking-status-outfordelivery {
      background-color: #f5a551
    }

    .tracking-status-deliveryoffice {
      background-color: #f7dc6f
    }

    .tracking-status-delivered {
      background-color: #4cbb87
    }

    .tracking-status-attemptfail {
      background-color: #b789c7
    }

    .tracking-status-error,
    .tracking-status-exception {
      background-color: #d26759
    }

    .tracking-status-expired {
      background-color: #616e7d
    }

    .tracking-status-pending {
      background-color: #ccc
    }

    .tracking-status-inforeceived {
      background-color: #214977
    }

    .tracking-list {
      border: 1px solid #e5e5e5
    }

    .tracking-item {
      border-left: 1px solid #e5e5e5;
      position: relative;
      padding: 2rem 1.5rem .5rem 2.5rem;
      font-size: .9rem;
      margin-left: 3rem;
      min-height: 5rem
    }

    .tracking-item:last-child {
      padding-bottom: 4rem
    }

    .tracking-item .tracking-date {
      margin-top: .6rem;
      margin-bottom: .5rem
    }

    .tracking-item .tracking-date span {
      color: #888;
      font-size: 85%;
      padding-left: .4rem
    }

    .tracking-item .tracking-content {
      padding: .5rem .8rem;
      background-color: #f4f4f4;
      border-radius: .5rem
    }

    .tracking-item .tracking-content span {
      display: block;
      color: #888;
      font-size: 85%
    }

    .tracking-item .tracking-icon {
      line-height: 2.6rem;
      position: absolute;
      left: -1.3rem;
      width: 2.6rem;
      height: 2.6rem;
      text-align: center;
      border-radius: 50%;
      font-size: 1.1rem;
      background-color: #fff;
      color: #fff
    }

    .tracking-item .tracking-icon.status-sponsored {
      background-color: #f68
    }

    .tracking-item .tracking-icon.status-delivered {
      background-color: #4cbb87
    }

    .tracking-item .tracking-icon.status-outfordelivery {
      background-color: #f5a551
    }

    .tracking-item .tracking-icon.status-deliveryoffice {
      background-color: #f7dc6f
    }

    .tracking-item .tracking-icon.status-attemptfail {
      background-color: #b789c7
    }

    .tracking-item .tracking-icon.status-exception {
      background-color: #d26759
    }

    .tracking-item .tracking-icon.status-inforeceived {
      background-color: #214977
    }

    .tracking-item .tracking-icon.status-intransit {
      color: #e5e5e5;
      border: 1px solid #e5e5e5;
      font-size: .6rem
    }

    @media(min-width:992px) {
      .tracking-item {
        margin-left: 10rem
      }

      .tracking-item .tracking-date {
        position: absolute;
        left: -10rem;
        width: 7.5rem;
        text-align: right
      }

      .tracking-item .tracking-date span {
        display: block
      }

      .tracking-item .tracking-content {
        padding: 0;
        background-color: transparent
      }
    }

    ul.timeline {
      list-style-type: none;
      position: relative;
    }

    ul.timeline:before {
      content: ' ';
      background: #d4d9df;
      display: inline-block;
      position: absolute;
      left: 29px;
      width: 2px;
      height: 100%;
      z-index: 400;
    }

    ul.timeline>li {
      margin: 20px 0;
      padding-left: 20px;
    }

    ul.timeline>li:before {
      content: ' ';
      background: white;
      display: inline-block;
      position: absolute;
      border-radius: 50%;
      border: 3px solid #22c0e8;
      left: 20px;
      width: 20px;
      height: 20px;
      z-index: 400;
    }
  </style>

</head>

<body>


  <!-- Left Panel -->

  <aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">

      <div class="navbar-header">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand" href="<?php echo base_url('welcome'); ?>">HCP</a>
        <!-- <a class="navbar-brand" href="./"><img src="<?php echo base_url('assets/img/logo2.png'); ?>" alt="Logo"></a> -->
      </div>

      <div id="main-menu" class="main-menu collapse navbar-collapse">
        <ul class="nav navbar-nav">

          <li>
            <a href="<?php echo base_url('dashboard'); ?>"> <i class="menu-icon fa fa-home"></i>Dashboard </a>
          </li>

          <li class="menu-item-has-children dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-gears"></i>Utilitas</a>
            <ul class="sub-menu children dropdown-menu">
              <li><i class="fa fa-folder-open-o"></i><a href="<?php echo base_url(''); ?>">Sub Menu 1</a></li>
              <li><i class="fa fa-folder-open-o"></i><a href="<?php echo base_url(''); ?>">Sub Menu 2</a></li>
            </ul>
          </li>

        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>
  </aside><!-- /#left-panel -->

  <!-- Left Panel -->

  <!-- Right Panel -->

  <div id="right-panel" class="right-panel">

    <!-- Header-->
    <header id="header" class="header">

      <div class="header-menu">

        <div class="col-sm-7">
          <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-dropbox"></i></a>

          <div class="header-left">
            <a class="navbar-brand" href="<?php echo base_url(''); ?>">HCP Presensi</a>
          </div>
        </div>

        <div class="col-sm-5">
          <div class="user-area dropdown float-right">

            <button style="background: white; border: none" class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-user text-success"></i>
              <span class="text-dark"><?php echo ucwords($this->session->userdata('name')) . " ( " . $this->session->userdata('user_role_name') . " ) " ?></span>
              <i class="fa fa-angle-double-down text-success"></i>
            </button>

            <div class="user-menu dropdown-menu">
              <a class="text-danger nav-link" href="<?php echo base_url('login/logout/'); ?>"><i class="fa fa-sign-out"></i> Logout</a>
            </div>
          </div>

        </div>
      </div>

    </header><!-- /header -->
    <!-- Header-->
    <?php echo $contents; ?>

  </div><!-- /#right-panel -->

  <!-- Right Panel -->

  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/jquery/dist/jquery.3.2.1.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/popper.js/dist/umd/popper.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

  <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>assets/js/main.js"></script>
  <script src="<?php echo base_url('assets/js/'); ?>bootstrap3-typeahead.min.js"></script>
  <script src="<?php echo base_url('assets/js/'); ?>handlebars.js"></script>
  <script src="<?php echo base_url('assets/js/'); ?>typeahead.bundle.js"></script>

  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/javascript.util/0.12.12/javascript.util.min.js"></script> -->
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/jszip/dist/jszip.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/pdfmake/build/pdfmake.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/pdfmake/build/vfs_fonts.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
  <script src="<?php echo base_url('assets/template/backend/'); ?>assets/js/init-scripts/data-table/datatables-init.js"></script>
  <script src="<?php echo base_url('assets/js/'); ?>jquery.mask.min.js"></script>
  <script src="<?php echo base_url('assets/js/'); ?>moment.min.js"></script>
  <script src="<?php echo base_url('assets/js/'); ?>daterangepicker.js"></script>
  <script src="<?php echo base_url('assets/js/'); ?>jquery.datetimepicker.full.js"></script>

  <script type="text/javascript">
    var $ = jQuery;
    $('body').removeClass('glyphicon glyphicon-calendar');
    $('body').removeClass('glyphicon glyphicon-chevron-left');
  </script>

  <script src="<?php
                $uriSegment = $this->uri->segment(1);
                switch ($uriSegment) {
                  case "dashboard":
                    echo base_url() . "assets/js/dashboard.js";
                    break;
                  
                }
                ?>"></script>

</body>

</html>
