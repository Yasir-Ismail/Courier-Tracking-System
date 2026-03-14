<?php
// admin/create_shipment.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../config/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_name = trim($_POST['sender_name']);
    $sender_city = trim($_POST['sender_city']);
    $receiver_name = trim($_POST['receiver_name']);
    $receiver_city = trim($_POST['receiver_city']);
    $parcel_weight = trim($_POST['parcel_weight']);
    $delivery_type = trim($_POST['delivery_type']);
    $shipment_date = $_POST['shipment_date'];
    
    // Generate unique tracking number (SS + 6 digits)
    $tracking_id = '';
    while (true) {
        $tracking_id = 'SS' . mt_rand(100000, 999999);
        $check = $pdo->prepare("SELECT id FROM shipments WHERE tracking_number = ?");
        $check->execute([$tracking_id]);
        if (!$check->fetch()) {
            break;
        }
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO shipments (tracking_number, sender_name, sender_city, receiver_name, receiver_city, parcel_weight, delivery_type, shipment_date, current_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Booked')");
        $stmt->execute([
            $tracking_id, 
            $sender_name, 
            $sender_city, 
            $receiver_name, 
            $receiver_city, 
            $parcel_weight, 
            $delivery_type, 
            $shipment_date
        ]);

        $shipment_id = $pdo->lastInsertId();

        $stmt_update = $pdo->prepare("INSERT INTO tracking_updates (shipment_id, status_message, location) VALUES (?, 'Parcel Booked', ?)");
        $stmt_update->execute([$shipment_id, $sender_city]);

        $pdo->commit();
        $success = "Shipment created successfully! Tracking ID: " . $tracking_id;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Failed to create shipment: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shipment | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Create New Shipment</h5>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6>Sender Details</h6>
                                <input type="text" name="sender_name" class="form-control mb-2" placeholder="Full Name" required>
                                <input type="text" name="sender_city" class="form-control" placeholder="City" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Receiver Details</h6>
                                <input type="text" name="receiver_name" class="form-control mb-2" placeholder="Full Name" required>
                                <input type="text" name="receiver_city" class="form-control" placeholder="City" required>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Parcel Weight (kg)</label>
                                <input type="number" step="0.01" name="parcel_weight" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Delivery Type</label>
                                <select name="delivery_type" class="form-select" required>
                                    <option value="Standard">Standard</option>
                                    <option value="Express">Express</option>
                                    <option value="Same Day">Same Day</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Shipment Date</label>
                                <input type="date" name="shipment_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Generate Shipment & Tracking ID</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
