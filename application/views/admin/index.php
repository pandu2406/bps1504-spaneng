<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jumlah Mitra</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $mitra ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Jumlah Pegawai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pegawai ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Kegiatan <br>Berjalan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $k_berjalan ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Kegiatan <br>Yang akan datang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $k_akan_datang ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl">
            <div class="card shadow mb-2">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kegiatan BPS Kabupaten Batang Hari</h6>
                </div>
                <!-- Card Body
                <div class="card-body">
                    
                </div> -->
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-xl">
            <div class="card shadow">
                <div class="card-body">
                    <?php if ($jlhk > 0) : ?>
                        <div id="chart_div" style="height: 400px;"></div>
                    <?php else : ?>
                        <div style="height: 300px;">Tidak ada kegiatan</div>
                    <?php endif; ?>
                </div>



            </div>
        </div>
    </div>
    <br>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {
        packages: ["timeline"]
        // 'packages': ['gantt']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        // var data = new google.visualization.DataTable();
        // data.addColumn('string', 'Task ID');
        // data.addColumn('string', 'Task Name');
        // data.addColumn('string', 'Resource');
        // data.addColumn('date', 'Start Date');
        // data.addColumn('date', 'End Date');
        // data.addColumn('number', 'Duration');
        // data.addColumn('number', 'Percent Complete');
        // data.addColumn('string', 'Dependencies');
        var container = document.getElementById('chart_div');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn({
            type: 'string',
            id: 'Position'
        });
        dataTable.addColumn({
            type: 'string',
            id: 'Name'
        });
        dataTable.addColumn({
            type: 'date',
            id: 'Start'
        });
        dataTable.addColumn({
            type: 'date',
            id: 'End'
        });


        <?php foreach ($details as $d) : ?>
            dataTable.addRows([

                ['<?= $d['nama'] ?>', '<?= $d['nama'] ?>', new Date(<?= date('Y, n-1, j', $d['start']); ?>), new Date(<?= date('Y, n-1, j', $d['finish']); ?>)]

            ]);

        <?php endforeach; ?>


        var options = {
            timeline: {
                colorByRowLabel: true
            }
        };

        // var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

        chart.draw(dataTable, options);
    }
</script>