<?php
// admin/dashboard.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../config/db.php';

// Get stats
$stats = [
    'total' => $pdo->query("SELECT count(*) FROM shipments")->fetchColumn(),
    'in_transit' => $pdo->query("SELECT count(*) FROM shipments WHERE current_status = 'In Transit'")->fetchColumn(),
    'out_delivery' => $pdo->query("SELECT count(*) FROM shipments WHERE current_status = 'Out for Delivery'")->fetchColumn(),
    'delivered' => $pdo->query("SELECT count(*) FROM shipments WHERE current_status = 'Delivered'")->fetchColumn(),
];

// Get recent shipments
$recent = $pdo->query("SELECT * FROM shipments ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SwiftShip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php"><i class="fas fa-shipping-fast"></i> SwiftShip Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="adminNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="shipments.php">Shipments</a></li>
                <li class="nav-item"><a class="btn btn-outline-light btn-sm ms-3" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
        <a href="create_shipment.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Shipment</a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm border-0">
                <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-boxes fa-3x mb-3 opacity-75"></i>
                    <h5 class="card-title fw-semibold">Total Shipments</h5>
                    <h2 class="display-5 fw-bold mb-0"><?= $stats['total'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info h-100 shadow-sm border-0">
                <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-truck-moving fa-3x mb-3 opacity-75"></i>
                    <h5 class="card-title fw-semibold">In Transit</h5>
                    <h2 class="display-5 fw-bold mb-0"><?= $stats['in_transit'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100 shadow-sm border-0">
                <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center text-dark">
                    <i class="fas fa-motorcycle fa-3x mb-3 opacity-75"></i>
                    <h5 class="card-title fw-semibold">Out for Delivery</h5>
                    <h2 class="display-5 fw-bold mb-0"><?= $stats['out_delivery'] ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm border-0">
                <div class="card-body py-4 d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                    <h5 class="card-title fw-semibold">Delivered</h5>
                    <h2 class="display-5 fw-bold mb-0"><?= $stats['delivered'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list-ul text-primary"></i> Recent Shipments</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tracking ID</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent as $s): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary"><?= htmlspecialchars($s['tracking_number']) ?></td>
                            <td><?= htmlspecialchars($s['sender_name']) ?><br><small class="text-muted"><?= htmlspecialchars($s['sender_city']) ?></small></td>
                            <td><?= htmlspecialchars($s['receiver_name']) ?><br><small class="text-muted"><?= htmlspecialchars($s['receiver_city']) ?></small></td>
                            <td><span class="badge bg-secondary px-3 py-2 rounded-pill"><?= htmlspecialchars($s['current_status']) ?></span></td>
                            <td><?= date('M d, Y', strtotime($s['created_at'])) ?></td>
                            <td class="pe-4 text-end">
                                <a href="shipment_detail.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No shipments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
