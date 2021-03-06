<?php

include "include/parse.php";
use Parse\ParseUser;
use Parse\ParseException;
use Parse\ParseQuery;

$currentUser = ParseUser::getCurrentUser();

if (empty($currentUser)) {
  header('Location: ../login.php');
  exit;
}
if ($currentUser->get('role') !== "admin") {
  header('Location: ../login.php');
  exit;
}

if (!empty($_POST['username'])) {
  $query = new ParseQuery("_User",$currentUser);
  $query->equalTo("username", $_POST['username']);
  $results = $query->first();

  if ($_POST['button'] == "deny") {
    $results->destroy(true);
  }
  else if ($_POST['button'] == "accept") {
    $results->set("approve",1);
    $results->save(true);
  }
}

$query = new ParseQuery("_User",$currentUser);
$query->notEqualTo("role", "admin");
$query->descending("createdAt");
$query->descending("verifed");

//$query->limit(30); // default 100, max 1000

$results = $query->find();
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>A.S.H.I. | Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
      </head>
      <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

          <header class="main-header">
            <!-- Logo -->
            <a href="index2.html" class="logo">
              <!-- mini logo for sidebar mini 50x50 pixels -->
              <span class="logo-mini"><b>A.S.H.I.</b></span>
              <!-- logo for regular state and mobile devices -->
              <span class="logo-lg"><b>A.S.H.I.</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
              <!-- Sidebar toggle button-->
              <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </a>
              <!-- Control Sidebar Toggle Button -->



            </nav>
          </header>
          <!-- Left side column. contains the logo and sidebar -->
          <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
              <!-- Sidebar user panel -->
              <div class="user-panel">
                <div class="pull-left image">
                  <img src="dist/img/Admin.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                  <p><?php echo $currentUser->getUsername(); ?></p>
                  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
              </div>

              <!-- sidebar menu: : style can be found in sidebar.less -->
              <ul class="sidebar-menu">
                <li class="header">Menu Bar</li>
                <li class="active"><a href="#"><i class="fa fa-check-square-o"></i> <span>Confirm Registration</span></a></li>
                <li><a href="logout.php"><i class="fa fa-power-off"></i> <span>Logout</span></a></li>
              </ul>
            </section>
            <!-- /.sidebar -->
          </aside>

          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Confirm Registration
              </h1>
            </section>

            <!-- Main content -->
            <section class="content">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Table</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Register at</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php


                      for ($i = 0; $i < count($results); $i++) {
                        $object = $results[$i];
                        $time = $object->getCreatedAt();
                        $time->setTimezone(new DateTimeZone('Asia/Bangkok'));
                        echo "<tr>";
                        echo "<td><a href=\"memberDetail.php?username=".$object->get('username')."\">". $object->get('username') ."</a></td>";
                        echo "<td>". $object->get('fullName') ."</td>";
                        echo "<td>". $object->get('phone') ."</td>";
                        echo "<td>". $object->get('address') ."</td>";
                        echo "<td>". $time->format('Y-m-d H:i:s') ."</td>";
                        if ($object->get('approve') == 1) {
                          echo "<td><a class=\"blabel label-success btn-xs\">Accepted</a></td>";
                        }
                        else {
                          echo "<td><a class=\"label label-warning btn-xs\">Wait to Accept</a></td>";
                        }
                        echo "</tr>";
                      }
                      ?>

                    </table>
                  </div><!-- /.box-body -->
                </div><!-- /.box -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </section><!-- /.content -->
        </div><!-- /.content-wrapper -->


        immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
      </div><!-- ./wrapper -->

      <!-- jQuery 2.1.4 -->
      <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
      <!-- Bootstrap 3.3.5 -->
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <!-- DataTables -->
      <script src="plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
      <!-- SlimScroll -->
      <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
      <!-- FastClick -->
      <script src="plugins/fastclick/fastclick.min.js"></script>
      <!-- AdminLTE App -->
      <script src="dist/js/app.min.js"></script>
      <!-- AdminLTE for demo purposes -->
      <script src="dist/js/demo.js"></script>
      <!-- page script -->
      <script>
        $(function () {
          $("#example1").DataTable();
          $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
          });
        });
      </script>
    </body>
    </html>
