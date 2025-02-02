<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connect</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- Personal style -->
  <link rel="stylesheet" href="dist/css/style.css">
  <style>
      body {
        font-family: 'Poppins', serif;
        font-size: 14px;
      }
      .sidebarStyle{
        background: #f9fafb;
        background-image: url('dist/img/fondo.jpg');
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        background-color: rgba(255,255,255,0.3);
        background-blend-mode: lighten;
      }
      .fontStyle{
        color: #01418a !important;
      }
      .fontStyleSubMenu{
        color: #373d43 !important;
      }
      .navbarStyle{
        background: #155ea8;
        color: #fff;
      }
      .col-center-block {
          float: none;
          display: block;
          margin: 0 auto;
          /* margin-left: auto; margin-right: auto; */
      }
 </style>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard.js"></script>
  <!-- Sweet Alert 2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Connect -->
  <script src="dist/js/connect.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/connect_logo.png" alt="Connect">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbarStyle">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="menu.php?v=inicio" class="nav-link">Inicio</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" style="background-color: white; border: 1px solid #e6e9ec; height: 57px; box-shadow: 0 -4px 7px -7px grey inset; padding-top: 10px; padding-left: 20px; text-align: left;">
      <img src="dist/img/connect_logo.png" alt="Connect">
    </a>

    <!-- Sidebar -->
    <div class="sidebar sidebarStyle">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block fontStyle">Administrador</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header fontStyle">MENU</li>
          <li class="nav-item">
            <a href="menu.php?v=inicio" class="nav-link fontStyleSubMenu">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Visión General
              </p>
            </a>
          </li>
          <li class="nav-header fontStyle">FUNCIONES</li>
          <li class="nav-item">
            <a href="#" class="nav-link fontStyleSubMenu">
              <i class="nav-icon fas fa-network-wired"></i>
              <p>
                Crear Grupo
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link fontStyleSubMenu">
              <i class="nav-icon fas fa-upload"></i>
              <p>
                Mis Recursos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link fontStyleSubMenu">
              <i class="nav-icon fas fa-calendar"></i>
              <p>
                Calendario
              </p>
            </a>
          </li>
          <li class="nav-header fontStyle">AJUSTES</li>
          <li class="nav-item">
            <a href="#" class="nav-link fontStyleSubMenu">
              <i class="nav-icon fas fa-bars"></i>
              <p>
                Orden de Grupos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link fontStyleSubMenu">
              <i class="nav-icon fas fa-bars"></i>
              <p>
                Orden de Dispositivos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link fontStyleSubMenu">
              <i class="nav-icon fas fa-lock"></i>
              <p>
                Permisos
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <?php
    require_once('config/routes.php');
  ?>

  <!-- Content Wrapper. Contains page content -->
  <!-- <div class="content-wrapper">
    <div class="input-group date" id="txtfecha" data-target-input="nearest">
        <input type="text" class="form-control datetimepicker-input" data-target="#txtfecha">
        <div class="input-group-append" data-target="#txtfecha" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
    </div>
  </div> -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="https://adminlte.io">Connect</a>.</strong>
    Todos los derechos reservados.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.1
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script>
  $(function () {
    
    //Date picker
    $('#txtfecha').datetimepicker({
        format: 'DD/MM/yyyy'
    });

  })
</script>
</body>
</html>
