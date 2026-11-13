
<?php
require_once "db.php";

$sql = "SELECT * FROM rooms ORDER BY id ASC";
$rooms = $conn->query($sql);

if (!$rooms) {
    die("Unable to load rooms: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hotel Haven | Room Reservation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">HOTEL HAVEN</a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#rooms">Rooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero" id="home">
    <span class="hero-tag">WELCOME TO HOTEL HAVEN</span>

    <h1>Relax. Stay. Enjoy.</h1>

    <p>
        Experience comfort, elegance, and unforgettable moments
        in every stay.
    </p>

    <div>
        <a href="#rooms" class="main-btn">
            Explore Our Rooms
        </a>
    </div>
</section>

<!-- ROOMS -->
<section class="container py-5" id="rooms">

    <div class="text-center mb-5">
        <span class="section-tag">OUR ACCOMMODATIONS</span>
        <h2 class="section-title">Discover Our Rooms</h2>
        <p>Choose the perfect room for your comfortable stay.</p>
    </div>

    <div class="row g-4">

        <?php while ($room = $rooms->fetch_assoc()): ?>

        <div class="col-lg-4 col-md-6">

            <div class="room-card">

                
                 <img
                    src="hotel_images/<?= htmlspecialchars($room['image'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($room['room_name']) ?>"
                    class="room-image"
                    onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=Image+Not+Found';"
                >


                <div class="room-info">

                    <h3><?= e($room['room_name']) ?></h3>

                    <p><?= e($room['description']) ?></p>

                    <p class="capacity">
                        <strong>Maximum Guests:</strong>
                        <?= (int)$room['capacity'] ?> persons
                    </p>

                    <div class="price mb-3">
                        ₱<?= number_format($room['price'], 2) ?>
                        <small>/ night</small>
                    </div>

                    <button
                        type="button"
                        class="book-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#bookingModal"
                        data-id="<?= (int)$room['id'] ?>"
                        data-name="<?= e($room['room_name']) ?>"
                        data-price="<?= e($room['price']) ?>"
                        data-capacity="<?= (int)$room['capacity'] ?>">

                        Book Now

                    </button>

                </div>
            </div>

        </div>

        <?php endwhile; ?>

    </div>
</section>

<!-- BOOKING MODAL -->
<div class="modal fade" id="bookingModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

           <form action="insert.php" method="POST" id="bookingForm">

                <div class="modal-header">
                    <h5 class="modal-title">Hotel Reservation Form</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <input type="hidden" name="room_id" id="room_id">

                        <div class="col-12">
                            <label class="form-label">Selected Room</label>
                            <input type="text" id="room_name"
                                   class="form-control" readonly>
                        </div>

                        <input type="hidden" name="room_price" id="room_price">

                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name"
                                   class="form-control" maxlength="100"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email"
                                   class="form-control" maxlength="100"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" name="contact_number"
                                   class="form-control" maxlength="30"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Number of Guests</label>
                            <input type="number" name="guests" id="guests"
                                   class="form-control"
                                   min="1" value="1" required>
                            <small id="capacityText" class="text-muted"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Check-in Date</label>
                            <input type="date" name="check_in" id="check_in"
                                   class="form-control"
                                   min="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Check-out Date</label>
                            <input type="date" name="check_out" id="check_out"
                                   class="form-control"
                                   min="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-12">
                            <div class="total-box">
                                <p class="mb-1">Estimated Reservation Total</p>

                                <h3 id="totalPrice">₱0.00</h3>

                                <small id="nightInfo">
                                    Select your dates.
                                </small>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-success">
                        Confirm Reservation
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

<!-- ABOUT -->
<section class="about-section py-5" id="about">
    <div class="container text-center">

        <span class="section-tag">GET TO KNOW US</span>

        <h2>About Hotel Haven</h2>

        <p>
            Hotel Haven provides comfortable accommodations and convenient
            online reservations. Whether you are traveling alone, with friends,
            or with family, we have a room for your needs.
        </p>

    </div>
</section>

<!-- CONTACT -->
<section class="container text-center py-5" id="contact">

    <h2 class="section-title">Contact Us</h2>

    <p>Have questions about your stay?</p>

    <p>📧 hotelhaven@gmail.com</p>

    <p>📱 0912 345 6789</p>

</section>

<!-- FOOTER -->
<footer>
    <p class="mb-0">
        &copy; <?= date("Y") ?> Hotel Haven. All Rights Reserved.
    </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// BOOKING MODAL
const modal = document.getElementById("bookingModal");

const checkIn = document.getElementById("check_in");
const checkOut = document.getElementById("check_out");
const guests = document.getElementById("guests");

let selectedPrice = 0;
let selectedCapacity = 1;

modal.addEventListener("show.bs.modal", function(event) {

    const button = event.relatedTarget;

    selectedPrice = parseFloat(button.dataset.price) || 0;
    selectedCapacity = parseInt(button.dataset.capacity) || 1;

    document.getElementById("room_id").value = button.dataset.id;
    document.getElementById("room_name").value = button.dataset.name;
    document.getElementById("room_price").value = selectedPrice;

    guests.max = selectedCapacity;
    guests.value = 1;

    document.getElementById("capacityText").textContent =
        "Maximum capacity: " + selectedCapacity + " guests";

    checkIn.value = "";
    checkOut.value = "";
    checkOut.min = checkIn.min;

    updateTotal();
});

// CHECK-IN
checkIn.addEventListener("change", function() {

    checkOut.min = this.value;

    if (checkOut.value && checkOut.value <= this.value) {
        checkOut.value = "";
    }

    updateTotal();
});

// CHECK-OUT
checkOut.addEventListener("change", updateTotal);

// GUEST VALIDATION
guests.addEventListener("input", function() {

    if (parseInt(this.value) > selectedCapacity) {
        this.value = selectedCapacity;
    }

    if (parseInt(this.value) < 1) {
        this.value = 1;
    }
});

// AUTOMATIC TOTAL
function updateTotal() {

    const totalElement = document.getElementById("totalPrice");
    const nightElement = document.getElementById("nightInfo");

    if (!checkIn.value || !checkOut.value) {

        totalElement.textContent = "₱0.00";
        nightElement.textContent = "Select your dates.";

        return;
    }

    const start = new Date(checkIn.value + "T00:00:00");
    const end = new Date(checkOut.value + "T00:00:00");

    const nights = Math.round((end - start) / 86400000);

    if (nights <= 0) {

        totalElement.textContent = "Invalid dates";
        nightElement.textContent =
            "Check-out must be after check-in.";

        return;
    }

    const total = selectedPrice * nights;

    totalElement.textContent =
        "₱" + total.toLocaleString("en-PH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    nightElement.textContent =
        nights + " night(s) × ₱" +
        selectedPrice.toLocaleString("en-PH");
}

// FORM VALIDATION
document.getElementById("bookingForm").addEventListener("submit", function(event) {

    if (!checkIn.value || !checkOut.value ||
        checkOut.value <= checkIn.value) {

        event.preventDefault();
        alert("Please select a valid check-in and check-out date.");
        return;
    }

    if (parseInt(guests.value) > selectedCapacity ||
        parseInt(guests.value) < 1) {

        event.preventDefault();
        alert("Please enter a valid number of guests.");
    }
});
</script>

</body>
</html>
