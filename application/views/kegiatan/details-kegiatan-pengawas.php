<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg">

            <?= $this->session->flashdata('message'); ?>
            <div class="row" style="color:#00264d;">
                <div class="col-lg-6">
                    <h2>Pengawas: <?= $pengawas['nama']; ?></h2>
                </div>
            </div>

            <div class="card shadow">
                <div class="row">
                    <div class="col-lg mt-2 ml-2 mr-2">
                        <div id="chart_div" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <br>

            <div class="table-responsive">
                <table class="table table-borderless table-hover" id="mydata">
                    <thead style="background-color: #00264d; color:#e6e6e6;">
                        <tr align=center>

                            <th scope="col">Nama Kegiatan</th>
                            <th scope="col">Start</th>
                            <th scope="col">Finish</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #ffffff; color: #00264d;">


                        <?php $i = 1; ?>
                        <?php foreach ($details as $d) : ?>
                            <tr align=center>
                                <td><?= $d['nama']; ?></td>
                                <td><?= date('d F Y', $d['start']); ?></td>
                                <td><?= date('d F Y', $d['finish']); ?></td>
                                <?php $now = (time()); ?>
                                <?php if ($now < $d['start']) : ?>
                                    <td><a class="badge badge-warning">belum mulai</a></td>
                                <?php elseif ($now > $d['finish']) : ?>
                                    <td><a class="badge badge-danger">selesai</a></td>
                                <?php else : ?>
                                    <td><a class="badge badge-primary">sedang berjalan</a></td>
                                <?php endif; ?>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>

    </div>
    <br>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            // 'packages': ['gantt']
            packages: ["timeline"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

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
            // var data = new google.visualization.DataTable();
            // data.addColumn('string', 'Task ID');
            // data.addColumn('string', 'Task Name');
            // data.addColumn('string', 'Resource');
            // data.addColumn('date', 'Start Date');
            // data.addColumn('date', 'End Date');
            // data.addColumn('number', 'Duration');
            // data.addColumn('number', 'Percent Complete');
            // data.addColumn('string', 'Dependencies');


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

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->