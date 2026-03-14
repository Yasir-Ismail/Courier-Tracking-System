<?php
// public/index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftShip Couriers | Fast Delivery. Smart Tracking.</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-shipping-fast"></i> SwiftShip Couriers</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="../admin/login.php">Admin Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero-section">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Fast Delivery. Smart Tracking.</h1>
        <p class="lead mb-5">Track Your Parcel Instantly with our real-time tracking system.</p>
    </div>
</section>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="tracking-box text-center">
                <h3 class="mb-4">Track Your Shipment</h3>
                <form action="track.php" method="GET" class="d-flex gx-2 justify-content-center">
                    <input type="text" name="tracking_id" class="form-control me-2" placeholder="Enter Tracking ID (e.g., SS123456)" required style="max-width: 400px; height: 50px; font-size: 1.2rem;">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-search"></i> Track Shipment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<section id="services" class="py-5 mt-5 bg-light">
    <div class="container text-center">
        <h2 class="mb-5">Our Services</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                <h4>Local Delivery</h4>
                <p>Fast and reliable same-day delivery across the city.</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-plane fa-3x text-primary mb-3"></i>
                <h4>Intercity Courier</h4>
                <p>Next-day express shipping to major cities nationwide.</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-box-open fa-3x text-primary mb-3"></i>
                <h4>Express Shipping</h4>
                <p>Priority handling for your most urgent parcels.</p>
            </div>
            <div class="col-md-3">
                <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
                <h4>E-commerce</h4>
                <p>Complete logistics solutions for online businesses.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container text-center">
        <h2 class="mb-5">How Tracking Works</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="p-3 border rounded mb-3 bg-white">
                    <i class="fas fa-box fa-2x text-warning mb-2"></i>
                    <h5>1. Shipment is Booked</h5>
                    <p class="text-muted">Your parcel gets registered and a unique Tracking ID is generated.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded mb-3 bg-white">
                    <i class="fas fa-warehouse fa-2x text-primary mb-2"></i>
                    <h5>2. Moves through Hubs</h5>
                    <p class="text-muted">The parcel passes through different logistics hubs securely.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded mb-3 bg-white">
                    <i class="fas fa-home fa-2x text-success mb-2"></i>
                    <h5>3. Delivery Completed</h5>
                    <p class="text-muted">The parcel reaches the receiver's address successfully.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-dark text-white text-center py-4">
    <p class="mb-0">&copy; <?php echo date('Y'); ?> SwiftShip Couriers. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
