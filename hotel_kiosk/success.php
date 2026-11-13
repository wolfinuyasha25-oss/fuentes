
<?php
require_once "db.php";

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    die("Invalid reservation ID.");
}

$stmt = $conn->prepare(
    "SELECT r.*, rooms.room_name, rooms.price
     FROM reservations r
     INNER JOIN rooms ON r.room_id = rooms.id
     WHERE r.id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$reservation = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$reservation) {
    die("Reservation not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservation Successful | Hotel Haven</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="success-page">

    <div class="success-card">

        <div class="success-icon">✓</div>

        <h1>Reservation Successful!</h1>

        <p>
            Thank you for choosing Hotel Haven.
            Your reservation has been recorded.
        </p>

        <div class="receipt">

            <h4>Reservation Receipt</h4>

            <hr>

            <p>
                <strong>Reservation ID:</strong>
                #<?= (int)$reservation["id"] ?>
            </p>

            <p>
                <strong>Customer Name:</strong>
                <?= e($reservation["customer_name"]) ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?= e($reservation["email"]) ?>
            </p>

            <p>
                <strong>Contact Number:</strong>
                <?= e($reservation["contact_number"]) ?>
            </p>

            <p>
                <strong>Room:</strong>
                <?= e($reservation["room_name"]) ?>
            </p>

            <p>
                <strong>Check-in:</strong>
                <?= date("F d, Y", strtotime($reservation["check_in"])) ?>
            </p>

            <p>
                <strong>Check-out:</strong>
                <?= date("F d, Y", strtotime($reservation["check_out"])) ?>
            </p>

            <p>
                <strong>Number of Guests:</strong>
                <?= (int)$reservation["guests"] ?>
            </p>

            <p>
                <strong>Number of Nights:</strong>
                <?= (int)$reservation["nights"] ?>
            </p>

            <p>
                <strong>Price per Night:</strong>
                ₱<?= number_format($reservation["price"], 2) ?>
            </p>

            <hr>

            <h4 class="receipt-total">
                Total Amount: ₱<?= number_format($reservation["total_price"], 2) ?>
            </h4>

            <p class="mt-3">
                <strong>Status:</strong>
                <?= e($reservation["status"]) ?>
            </p>

            <p>
                <strong>Booking Date:</strong>
                <?= date("F d, Y h:i A", strtotime($reservation["booking_date"])) ?>
            </p>

        </div>

        <div class="d-flex gap-2">

            <a href="index.php" class="main-btn flex-fill text-center">
                Back to Home
            </a>

            <button onclick="window.print()"
                    class="btn btn-outline-success flex-fill mt-3">
                Print Receipt
            </button>

        </div>

    </div>

</div>

</body>
</html>
