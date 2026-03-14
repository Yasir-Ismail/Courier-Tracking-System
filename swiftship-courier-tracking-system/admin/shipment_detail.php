<?php
// admin/shipment_detail.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header("Location: shipments.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM shipments WHERE id = ?");
$stmt->execute([$id]);
$shipment = $stmt->fetch();

if (!$shipment) {
    header("Location: shipments.php");
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $status_message = trim($_POST['status_message']);
    $location = trim($_POST['location']);

    try {
        $pdo->beginTransaction();

        $stmt_update = $pdo->prepare("INSERT INTO tracking_updates (shipment_id, status_message, location) VALUES (?, ?, ?)");
        $stmt_update->execute([$id, $status_message, $location]);

        // Derive current status based on predefined keywords or keep it simple
        $current_status = $status_message; // Directly update status
        $stmt_shipment = $pdo->prepare("UPDATE shipments SET current_status = ? WHERE id = ?");
        $stmt_shipment->execute([$current_status, $id]);

        $pdo->commit();
        $success = "Tracking timeline updated successfully!";
        // Refresh shipment data
        $shipment['current_status'] = $current_status;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Failed to add update: " . $e->getMessage();
    }
}

$stmt_updates = $pdo->prepare("SELECT * FROM tracking_updates WHERE shipment_id = ? ORDER BY created_at DESC");
$stmt_updates->execute([$id]);
$updates = $stmt_updates->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Details | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">SwiftShip Admin</a>
        <div class="d-flex">
            <a class="nav-link text-white me-3" href="dashboard.php">Dashboard</a>
            <a class="nav-link text-white" href="shipments.php">Shipments</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-box-open"></i> Shipment: <?= htmlspecialchars($shipment['tracking_number']) ?></h2>
        <span class="badge bg-primary fs-5"><?= htmlspecialchars($shipment['current_status']) ?></span>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle text-primary"></i> Information</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sender Name
                            <strong><?= htmlspecialchars($shipment['sender_name']) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sender City
                            <strong><?= htmlspecialchars($shipment['sender_city']) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Receiver Name
                            <strong><?= htmlspecialchars($shipment['receiver_name']) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Receiver City
                            <strong><?= htmlspecialchars($shipment['receiver_city']) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Parcel Weight
                            <strong><?= htmlspecialchars($shipment['parcel_weight']) ?> kg</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Delivery Type
                            <strong><?= htmlspecialchars($shipment['delivery_type']) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Shipment Date
                            <strong><?= date('M d, Y', strtotime($shipment['shipment_date'])) ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit"></i> Add Tracking Update</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Status Update Message</label>
                            <select name="status_message" class="form-select" required>
                                <option value="Booked">Booked</option>
                                <option value="Arrived at Hub">Arrived at Hub</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Out for Delivery">Out for Delivery</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location (e.g., Lahore Hub, En Route)</label>
                            <input type="text" name="location" class="form-control" required placeholder="Enter location">
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="update_status" class="btn btn-warning"><i class="fas fa-plus"></i> Add Status Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Tracking Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline mt-3 ms-2">
                        <?php foreach ($updates as $update): ?>
                            <li>
                                <div class="timeline-date mb-1 text-muted small">
                                    <i class="far fa-clock"></i> <?= date('d M Y - h:i A', strtotime($update['created_at'])) ?>
                                </div>
                                <div class="timeline-content text-primary fw-bold mb-1">
                                    <?= htmlspecialchars($update['status_message']) ?>
                                </div>
                                <?php if ($update['location']): ?>
                                    <div class="text-secondary small"><i class="fas fa-map-pin"></i> <?= htmlspecialchars($update['location']) ?></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($updates)): ?>
                            <li>No tracking updates found.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
