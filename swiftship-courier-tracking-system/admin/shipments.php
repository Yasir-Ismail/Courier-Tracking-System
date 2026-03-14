<?php
// admin/shipments.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../config/db.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM shipments WHERE id = ?");
    $stmt->execute([$del_id]);
    header("Location: shipments.php");
    exit;
}

$shipments = $pdo->query("SELECT * FROM shipments ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Shipments | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">SwiftShip Admin</a>
        <div class="d-flex">
            <a class="nav-link text-white me-3" href="dashboard.php">Dashboard</a>
            <a class="nav-link text-white" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list-alt"></i> All Shipments</h2>
        <a href="create_shipment.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Shipment</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Tracking ID</th>
                            <th>Sender</th>
                            <th>Receiver</th>
                            <th>City Route</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($shipments as $s): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary"><?= htmlspecialchars($s['tracking_number']) ?></td>
                            <td><?= htmlspecialchars($s['sender_name']) ?></td>
                            <td><?= htmlspecialchars($s['receiver_name']) ?></td>
                            <td><?= htmlspecialchars($s['sender_city']) ?> → <?= htmlspecialchars($s['receiver_city']) ?></td>
                            <td><?= htmlspecialchars($s['delivery_type']) ?></td>
                            <td>
                                <span class="badge bg-<?= $s['current_status'] == 'Delivered' ? 'success' : 'info' ?>">
                                    <?= htmlspecialchars($s['current_status']) ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="shipment_detail.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                <a href="shipments.php?delete=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this shipment?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($shipments)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No shipments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
