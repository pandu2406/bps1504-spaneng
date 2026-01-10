
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-shield-alt text-primary"></i> Security Command Center</h1>
        <a href="<?= base_url('admin/logging') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-sync-alt fa-sm text-white-50"></i> Refresh System</a>
    </div>

    <!-- Security Posture Alert -->
    <div class="alert alert-<?= $posture_color; ?> border-left-<?= $posture_color; ?> shadow" role="alert">
        <h4 class="alert-heading font-weight-bold"><i class="fas fa-heartbeat"></i> Security Status: <?= strtoupper($posture); ?></h4>
        <p class="mb-0"><?= $posture_text; ?></p>
    </div>

    <!-- Security Metrics Cards -->
    <div class="row">
        <!-- Critical Alerts -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Critical Threats</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($critical_alerts); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-biohazard fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banned IPs -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Banned IPs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($banned_count); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-gray-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Traffic -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_requests); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unique Sources -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Unique Sources</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($unique_ips); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-network-wired fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABS Navigation -->
    <ul class="nav nav-tabs mb-4" id="securityTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true"><i class="fas fa-chart-line"></i> Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="alerts-tab" data-toggle="tab" href="#alerts" role="tab" aria-controls="alerts" aria-selected="false"><i class="fas fa-bell"></i> Alerts <span class="badge badge-danger"><?= count($alerts); ?></span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="logs-tab" data-toggle="tab" href="#logs" role="tab" aria-controls="logs" aria-selected="false"><i class="fas fa-list"></i> Live Traffic Payload</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="banned-tab" data-toggle="tab" href="#banned" role="tab" aria-controls="banned" aria-selected="false"><i class="fas fa-ban"></i> Banned IPs</a>
        </li>
    </ul>

    <div class="tab-content" id="securityTabsContent">
        
        <!-- Tab 1: Overview & Charts -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Traffic Traffic (24 Hours)</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area" style="height: 350px;">
                                <canvas id="trafficChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Top 5 Active Users</h6>
                        </div>
                        <div class="card-body">
                             <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        <?php foreach($top_users as $u): ?>
                                        <tr>
                                            <td><i class="fas fa-user-circle mr-2 text-gray-400"></i> <?= $user_map[$u['user_id']] ?? 'User #' . $u['user_id']; ?></td>
                                            <td class="text-right font-weight-bold"><?= $u['hits']; ?> hits</td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php if(empty($top_users)): ?>
                                            <tr><td>No user activity.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Alerts -->
        <div class="tab-pane fade" id="alerts" role="tabpanel" aria-labelledby="alerts-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">Recent Threat Detections</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                         <table class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Severity</th>
                                    <th>Rule Name</th>
                                    <th>Source IP</th>
                                    <th>Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alerts as $alert): ?>
                                <tr>
                                    <td><?= date('d M H:i:s', strtotime($alert['created_at'])); ?></td>
                                    <td>
                                        <span class="badge badge-<?= $alert['severity'] == 'critical' ? 'dark' : 'danger'; ?>">
                                            <?= strtoupper($alert['severity']); ?>
                                        </span>
                                    </td>
                                    <td><?= $alert['rule_name']; ?></td>
                                    <td><?= $alert['ip_address']; ?></td>
                                    <td><small><?= $alert['details']; ?></small></td>
                                    <td>
                                        <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#banModal" data-ip="<?= $alert['ip_address']; ?>"><i class="fas fa-gavel"></i> Ban IP</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($alerts)): ?>
                                    <tr><td colspan="6" class="text-center">No threats detected. Great job!</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Detailed Logs -->
        <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Live Activity Feed (Payload Analysis)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="dataTableLogs" width="100%" cellspacing="0" style="font-size: 0.85rem;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Time</th>
                                    <th>IP / User</th>
                                    <th>Method</th>
                                    <th>URL</th>
                                    <th>Score</th>
                                    <th>Payload</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log) : ?>
                                    <tr class="<?= $log['risk_score'] > 50 ? 'table-warning' : ''; ?>">
                                        <td><?= date('H:i:s', strtotime($log['created_at'])); ?></td>
                                        <td>
                                            <code><?= $log['ip_address']; ?></code><br>
                                            <small><?= $log['user_id'] ? ($user_map[$log['user_id']] ?? $log['user_id']) : 'Guest'; ?></small>
                                        </td>
                                        <td><span class="badge badge-secondary"><?= $log['method']; ?></span></td>
                                        <td style="word-break: break-all; font-family: monospace;">
                                            <?= substr($log['url'], 0, 60); ?>
                                        </td>
                                        <td>
                                            <?php if($log['risk_score'] >= 80): ?>
                                                <span class="badge badge-danger"><?= $log['risk_score']; ?></span>
                                            <?php elseif($log['risk_score'] >= 50): ?>
                                                <span class="badge badge-warning"><?= $log['risk_score']; ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-success"><?= $log['risk_score']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(!empty($log['request_body']) || !empty($log['request_headers'])): ?>
                                                <button class="btn btn-xs btn-info btn-view-payload" 
                                                    data-toggle="modal" data-target="#payloadModal"
                                                    data-body='<?= htmlspecialchars($log['request_body'], ENT_QUOTES); ?>'
                                                    data-headers='<?= htmlspecialchars($log['request_headers'], ENT_QUOTES); ?>'
                                                    data-id="<?= $log['id']; ?>">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-outline-danger" data-toggle="modal" data-target="#banModal" data-ip="<?= $log['ip_address']; ?>">Ban</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 4: Banned IPs -->
        <div class="tab-pane fade" id="banned" role="tabpanel" aria-labelledby="banned-tab">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">Blocked IP Addresses (Firewall)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Reason</th>
                                    <th>Banned At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banned_ips_list as $ban): ?>
                                <tr>
                                    <td><?= $ban['ip_address']; ?></td>
                                    <td><?= $ban['reason']; ?></td>
                                    <td><?= $ban['banned_at']; ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/unban_ip/' . $ban['id']); ?>" class="btn btn-sm btn-success" onclick="return confirm('Unban PC?');"><i class="fas fa-check"></i> Unban</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($banned_ips_list)): ?>
                                    <tr><td colspan="4" class="text-center">No IPs are currently banned.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Payload Modal -->
<div class="modal fade" id="payloadModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Request Payload Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6>Headers</h6>
        <pre class="bg-light p-3 border rounded" id="modalHeaders" style="max-height: 200px; overflow: auto;"></pre>
        <h6>Body</h6>
        <pre class="bg-dark text-white p-3 border rounded" id="modalBody" style="max-height: 300px; overflow: auto;"></pre>
      </div>
    </div>
  </div>
</div>

<!-- Ban IP Modal -->
<div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?= base_url('admin/ban_ip'); ?>" method="post">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-ban"></i> Ban IP Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>IP Address</label>
            <input type="text" name="ip" id="banIpInput" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label>Reason</label>
            <select name="reason" class="form-control">
                <option value="Manual Block from Dashboard">Manual Block from Dashboard</option>
                <option value="Suspicious Activity (SQLi)">Suspicious Activity (SQLi)</option>
                <option value="Suspicious Activity (XSS)">Suspicious Activity (XSS)</option>
                <option value="Spam / Bruteforce">Spam / Bruteforce</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Confirm Ban</button>
      </div>
    </div>
    </form>
  </div>
</div>

<script>
    // Traffic Chart
    const trendData = <?= json_encode($traffic_trend) ?>;
    const labels = trendData.map(item => item.hour);
    const counts = trendData.map(item => item.count);

    const ctx = document.getElementById('trafficChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Requests',
                data: counts,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                pointRadius: 3,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { borderDash: [2] }, beginAtZero: true }
            }
        }
    });

    // View Payload Modal Handler
    $('#payloadModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var body = button.data('body');
        var headers = button.data('headers');
        
        // Pretty Print JSON if possible
        try {
            headers = JSON.stringify(JSON.parse(headers), null, 2);
        } catch(e) {}

        var modal = $(this);
        modal.find('#modalHeaders').text(headers);
        modal.find('#modalBody').text(body || 'No Body Content');
    });

    // Ban Modal Handler
    $('#banModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var ip = button.data('ip');
        var modal = $(this);
        modal.find('#banIpInput').val(ip);
    });
</script>