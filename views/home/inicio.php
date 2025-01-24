<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="color: #195ca6;">Estadisticas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Estadisticas</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $totalRbds; ?></h3>

                <p>Colegios</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">Mas informacion <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>100<sup style="font-size: 20px">%</sup></h3>

                <p>Activos</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">Mas informacion <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $totalGroups; ?></h3>

                <p>Grupos</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">Mas informacion <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $totalDevices; ?></h3>

                <p>Dispositivos</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">Mas informacion <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Custom tabs (Charts with tabs)-->
          <div class="card col-md-12">
            <div class="card-header">
              <h2 class="card-title" style="color: #195ca6;"><i class="fas fa-university"></i> Colegios</h2>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th style="color: #195ca6; width: 10px">#</th>
                    <th style="color: #195ca6;">Nombre</th>
                    <th class="text-center" style="color: #195ca6;">Grupos</th>
                    <th class="text-center" style="color: #195ca6;">Dispositivos</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- <tr>
                    <td>1.</td>
                    <td>Colegio Nuestra Señora de la Merced</td>
                    <td>
                      <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                      </div>
                    </td>
                    <td><span class="badge bg-danger">55%</span></td>
                  </tr> -->
                  <?php
                    foreach ($schools['data']['data'] as $a => $school) {
                      echo "<tr>
                              <td>" . ($a + 1) . "</td>
                              <td>" . $school['nombre'] . "</td>
                              <td class='text-center'>" . $school['grupos'] . "</td>
                              <td class='text-center'>" . $school['dispositivos'] . "</td>
                            </tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </section>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->