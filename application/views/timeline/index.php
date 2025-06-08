<!-- Begin Page Content -->
<div class="container-fluid">


    <div class="card shadow">
        <div class="row">
            <div class="col-lg mt-2 ml-2 mr-2 mb-2">
                <?php if ($jlhk > 0) : ?>
                    <div id="chart_div" style="height: 500px;"></div>
                <?php else : ?>
                    <div style="height: 500px;">Tidak ada kegiatan</div>
                <?php endif; ?>
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


            <?php foreach ($kegiatan as $k) : ?>
                dataTable.addRows([

                    ['<?= $k['nama'] ?>', '<?= $k['nama'] ?>', new Date(<?= date('Y, n-1, j', $k['start']); ?>), new Date(<?= date('Y, n-1, j', $k['finish']); ?>)]

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