<?php
// public/track.php
require_once '../config/db.php';

$tracking_id = isset($_GET['tracking_id']) ? strtoupper(trim($_GET['tracking_id'])) : '';
$shipment = null;
$updates = [];
$error_message = '';

if ($tracking_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM shipments WHERE tracking_number = ?");
        $stmt->execute([$tracking_id]);
        $shipment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($shipment) {
            $stmt_updates = $pdo->prepare("SELECT * FROM tracking_updates WHERE shipment_id = ? ORDER BY created_at DESC");
            $stmt_updates->execute([$shipment['id']]);
            $updates = $stmt_updates->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error_message = "Tracking number not found. Please review and try again.";
        }
    } catch (PDOException $e) {
        $error_message = "Database Error: " . $e->getMessage();
    }
} else {
    $error_message = "No tracking number provided. Please enter a valid Tracking ID.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Shipment | SwiftShip Couriers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-shipping-fast"></i> SwiftShip Couriers</a>
    </div>
</nav>

<div class="container py-5">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 text-center">Track Your Parcel</h2>
            <form action="track.php" method="GET" class="d-flex gx-2 mb-5">
                <input type="text" name="tracking_id" class="form-control me-2" value="<?= htmlspecialchars($tracking_id) ?>" placeholder="Enter Tracking ID (e.g., SS123456)" required>
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-search"></i> Track</button>
            </form>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger shadow-sm"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <?php if ($shipment): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0">Shipment Details (#<?= htmlspecialchars($shipment['tracking_number']) ?>)</h4>
                        <span class="badge bg-warning text-dark fs-6"><?= htmlspecialchars($shipment['current_status']) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 border-end">
                                <h6 class="text-muted text-uppercase mb-2"><i class="fas fa-user-circle"></i> Sender Information</h6>
                                <p class="mb-1 fw-bold"><?= htmlspecialchars($shipment['sender_name']) ?></p>
                                <p class="mb-0 text-secondary"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($shipment['sender_city']) ?></p>
                            </div>
                            <div class="col-md-6 ps-md-4">
                                <h6 class="text-muted text-uppercase mb-2"><i class="fas fa-user-circle"></i> Receiver Information</h6>
                                <p class="mb-1 fw-bold"><?= htmlspecialchars($shipment['receiver_name']) ?></p>
                                <p class="mb-0 text-secondary"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($shipment['receiver_city']) ?></p>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <h6 class="text-muted mb-1">Shipment Date</h6>
                                <strong><?= date('M d, Y', strtotime($shipment['shipment_date'])) ?></strong>
                            </div>
                            <div class="col-md-4 mb-2">
                                <h6 class="text-muted mb-1">Parcel Weight</h6>
                                <strong><?= htmlspecialchars($shipment['parcel_weight']) ?> kg</strong>
                            </div>
                            <div class="col-md-4 mb-2">
                                <h6 class="text-muted mb-1">Delivery Type</h6>
                                <strong><?= htmlspecialchars($shipment['delivery_type']) ?></strong>
                            </div>
                        </div>

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
                                    <div class="timeline-date mb-1">
                                        <i class="far fa-clock"></i> <?= date('d M Y - h:i A', strtotime($update['created_at'])) ?>
                                    </div>
                                    <div class="timeline-content text-primary fs-5 mb-1">
                                        <?= htmlspecialchars($update['status_message']) ?>
                                    </div>
                                    <?php if ($update['location']): ?>
                                        <div class="text-muted small"><i class="fas fa-map-pin"></i> <?= htmlspecialchars($update['location']) ?></div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                            <?php if (empty($updates)): ?>
                                <li>No tracking updates found.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">&copy; <?php echo date('Y'); ?> SwiftShip Couriers. All Rights Reserved.</p>
</footer>
</body>
</html>
