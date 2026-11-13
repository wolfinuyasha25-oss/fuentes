
<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

function showError($message) {
    echo "<script>
        alert(" . json_encode($message) . ");
        window.location.href = 'index.php';
    </script>";
    exit;
}

// GET FORM DATA
$customer_name = trim($_POST["customer_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$contact_number = trim($_POST["contact_number"] ?? "");

$room_id = filter_var(
    $_POST["room_id"] ?? null,
    FILTER_VALIDATE_INT
);

$guests = filter_var(
    $_POST["guests"] ?? null,
    FILTER_VALIDATE_INT
);

$check_in = $_POST["check_in"] ?? "";
$check_out = $_POST["check_out"] ?? "";

// VALIDATE CUSTOMER
if ($customer_name === "" || strlen($customer_name) > 100) {
    showError("Please enter a valid customer name.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 100) {
    showError("Please enter a valid email.");
}

if ($contact_number === "" || strlen($contact_number) > 30) {
    showError("Please enter a valid contact number.");
}

if (!$room_id || !$guests || $guests < 1) {
    showError("Invalid room or guest information.");
}

// VALIDATE DATES
$in = DateTime::createFromFormat("!Y-m-d", $check_in);
$out = DateTime::createFromFormat("!Y-m-d", $check_out);

if (!$in || $in->format("Y-m-d") !== $check_in ||
    !$out || $out->format("Y-m-d") !== $check_out) {
    showError("Invalid check-in or check-out date.");
}

if ($check_in < date("Y-m-d") || $check_out <= $check_in) {
    showError("Please select valid reservation dates.");
}

// GET ROOM FROM DATABASE
$stmt = $conn->prepare(
    "SELECT room_name, capacity, price
     FROM rooms
     WHERE id = ?"
);

$stmt->bind_param("i", $room_id);
$stmt->execute();

$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$room) {
    showError("Room not found.");
}

// CHECK CAPACITY
if ($guests > (int)$room["capacity"]) {
    showError("Number of guests exceeds room capacity.");
}

// COMPUTE TOTAL
$nights = (int)$in->diff($out)->days;
$total_price = (float)$room["price"] * $nights;

// CHECK ROOM AVAILABILITY
$check = $conn->prepare(
    "SELECT id FROM reservations
     WHERE room_id = ?
     AND status = 'Confirmed'
     AND check_in < ?
     AND check_out > ?
     LIMIT 1"
);

$check->bind_param("iss", $room_id, $check_out, $check_in);
$check->execute();

$existing = $check->get_result()->fetch_assoc();
$check->close();

if ($existing) {
    showError("This room is already reserved for those dates.");
}

// SAVE RESERVATION
$stmt = $conn->prepare(
    "INSERT INTO reservations
    (customer_name, email, contact_number,
     room_id, check_in, check_out,
     guests, nights, total_price)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "sssissiid",
    $customer_name,
    $email,
    $contact_number,
    $room_id,
    $check_in,
    $check_out,
    $guests,
    $nights,
    $total_price
);

if ($stmt->execute()) {

    $reservation_id = $conn->insert_id;

    $stmt->close();

    header("Location: success.php?id=" . $reservation_id);
    exit;

} else {

    showError("Reservation failed: " . $stmt->error);
}
?>
