<?php
include 'db.php';

// ---------------------------
// Fetch game safely by id
// ---------------------------
if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger text-center m-3">Invalid game selection.</div>';
    exit;
}

$game_id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param('i', $game_id);
$stmt->execute();
$res  = $stmt->get_result();
$game = $res->fetch_assoc();
$stmt->close();

if (!$game) {
    echo '<div class="alert alert-danger text-center m-3">Game not found.</div>';
    exit;
}

// ---------------------------
// Quantity & totals (PHP only)
// ---------------------------
$qty = 1;
if (isset($_POST['quantity'])) {
    $qty = max(1, (int)$_POST['quantity']);
}
$price_each     = (float)$game['price'];
$total_estimate = $qty * $price_each;

// ---------------------------
// Handle purchase (POST)
// ---------------------------
if (isset($_POST['purchase'])) {
    $name           = trim($_POST['name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');
    $transaction_id = trim($_POST['transaction_id'] ?? '');

    if ($name === '' || $email === '' || $payment_method === '' || $transaction_id === '') {
        $error_msg = 'Please fill in all required fields.';
    } else {
        $ins = $conn->prepare("
            INSERT INTO purchases (game_id, quantity, total_price, name, email, payment_method, transaction_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $ins->bind_param('iidssss', $game_id, $qty, $total_estimate, $name, $email, $payment_method, $transaction_id);
        if ($ins->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error_msg = 'Error placing order. Please try again.';
        }
        $ins->close();
    }
}

// ---------------------------
// Pick a banner image (same logic style as index.php)
// ---------------------------
$image_url = 'https://via.placeholder.com/1200x600';
$name_lower = strtolower($game['name'] ?? '');
if (strpos($name_lower, 'valorant') !== false) {
    $image_url = 'https://i.ytimg.com/vi/xi10SuaE49I/maxresdefault.jpg';
} elseif (strpos($name_lower, 'gta 5') !== false || strpos($name_lower, 'gta') !== false) {
    $image_url = 'https://sm.pcmag.com/pcmag_me/news/s/sony-confi/sony-confirms-gta-v-is-4k60fps-on-ps5_mvu1.jpg';
} elseif (strpos($name_lower, 'cod warzone') !== false || strpos($name_lower, 'cod') !== false) {
    $image_url = 'https://wallpapers.com/images/featured/call-of-duty-warzone-4k-kzetz7h7t75073ye.jpg';
} elseif (strpos($name_lower, 'nfs') !== false || strpos($name_lower, 'nfsmw') !== false || strpos($name_lower, 'need for speed') !== false) {
    $image_url = 'https://c4.wallpaperflare.com/wallpaper/551/273/298/bmw-m3-gtr-need-for-speed-most-wanted-need-for-speed-most-wanted-2012-video-game-car-street-racing-hd-wallpaper-preview.jpg';
}
?>

<?php include 'nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchase • <?php echo htmlspecialchars($game['name']); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">

  <style>
    :root{
      --v-dark:#0f1923;
      --v-card:#101c29;
      --v-red:#ff4655;
      --v-red2:#ff7e5f;
      --v-text:#ffffff;
      --v-muted:#c6d1e4;
      --v-field:#162334; /* brighter input bg */
      --v-field-border:#ff4655;
      --v-field-ph:#a9b5cc; /* placeholder */
    }
    body{background-color:var(--v-dark);color:var(--v-text);font-family:"Parkinsans",sans-serif;}
    .container{max-width:1100px;}

    /* HERO */
    .hero{position:relative;border-radius:16px;overflow:hidden;margin:20px 0 28px;background:#0c1620;border:1px solid rgba(255,255,255,.08);}
    .hero img{width:100%;height:320px;object-fit:cover;filter:brightness(.88);}
    .hero::before{content:"";position:absolute;inset:0;border-top:2px solid rgba(255,70,85,.95);filter:drop-shadow(0 0 8px rgba(255,70,85,.65));pointer-events:none;}
    .hero-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0),rgba(0,0,0,.6));}
    .hero-text{position:absolute;left:0;right:0;bottom:0;padding:18px 20px;}
    .hero-title{font-weight:800;margin:0 0 4px;}

    /* Cards */
    .card-v{background-color:var(--v-card);border:1px solid var(--v-red);border-radius:12px;box-shadow:0 10px 24px rgba(0,0,0,.25);}
    .card-v h5{color:var(--v-red);}

    /* Inputs — brighter & readable */
    .form-label{color:var(--v-muted);font-weight:600;}
    .form-control,.form-select{
      background-color:var(--v-field);
      color:#fff;
      border:1px solid var(--v-field-border);
    }
    .form-control::placeholder{color:var(--v-field-ph);opacity:1;}
    .form-select:required:invalid{color:var(--v-field-ph);}
    .form-control[disabled]{
      background-color:#0e1a28; /* darker but readable */
      color:#e5ecf6;            /* visible text */
      opacity:1;                /* override bootstrap dimming */
    }
    .form-control:focus,.form-select:focus{
      box-shadow:0 0 0 0.2rem rgba(255,70,85,.25);
      border-color:var(--v-red);
    }

    .btn-primary{background-color:var(--v-red);border-color:var(--v-red);font-weight:700;border-radius:999px;}
    .btn-primary:hover{background-color:#e03e4d;border-color:#e03e4d;}

    .info-pill{
      display:inline-flex;align-items:center;gap:.45rem;background:rgba(255,255,255,.08);
      border:1px solid rgba(255,255,255,.15);padding:.45rem .75rem;border-radius:999px;font-weight:700;
    }

    .total-box{
      background:linear-gradient(90deg, rgba(255,70,85,.12), rgba(255,126,95,.12));
      border:1px solid rgba(255,70,85,.35);
      border-radius:12px;
      padding:.9rem 1rem;
      font-weight:800;
      display:flex;justify-content:space-between;align-items:center;
    }
    .list-unstyled li{margin-bottom:.25rem;color:#cfd8e3;}
  </style>
</head>
<body>
<div class="container">

  <!-- HERO -->
  <section class="hero">
    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($game['name']); ?>">
    <div class="hero-overlay"></div>
    <div class="hero-text">
      <h2 class="hero-title">Complete Your Purchase</h2>
      <div class="mini" style="color:#cfd8e3;"><?php echo htmlspecialchars($game['name']); ?></div>
    </div>
  </section>

  <div class="row g-4">
    <!-- Left: Summary -->
    <div class="col-lg-5">
      <div class="card card-v">
        <div class="card-body">
          <h5 class="card-title"><i class="fa-solid fa-gamepad"></i> <?php echo htmlspecialchars($game['name']); ?></h5>
          <p class="card-text" style="color:#b8c4d9;">
            <?php echo nl2br(htmlspecialchars($game['description'])); ?>
          </p>

          <ul class="list-unstyled">
            <li><strong>Price (each):</strong> <span>৳<?php echo number_format($price_each, 2); ?></span></li>
            <li><strong>Payment:</strong> bKash • Nagad</li>
          </ul>

          <div class="mt-3 total-box">
            <span>Total</span>
            <span class="text-white">৳<?php echo number_format($total_estimate, 2); ?></span>
          </div>

          <div class="mt-3">
            <span class="info-pill text-white"><i class="fa-solid fa-mobile-screen-button"></i> Send Money To</span>
            <span class="info-pill text-white">+8801234567890</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Form -->
    <div class="col-lg-7">
      <div class="card card-v">
        <div class="card-body">
          <h5 class="card-title"><i class="fa-solid fa-wallet"></i> Payment & Details</h5>

          <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_msg); ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" id="quantity" value="<?php echo (int)$qty; ?>" min="1" required class="form-control">
              </div>
              <div class="col-md-6">
                <label class="form-label">Price (each)</label>
                <input type="text" class="form-control" value="৳<?php echo number_format($price_each, 2); ?>" disabled>
              </div>

              <div class="col-md-6">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" name="name" id="name" required class="form-control" placeholder="Enter full name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" required class="form-control" placeholder="name@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
              </div>

              <div class="col-md-6">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select name="payment_method" id="payment_method" required class="form-select">
                  <option value="">Select Payment Method</option>
                  <option value="bKash"  <?php echo (($_POST['payment_method'] ?? '')==='bKash')  ? 'selected' : ''; ?>>bKash</option>
                  <option value="Nagad"  <?php echo (($_POST['payment_method'] ?? '')==='Nagad')  ? 'selected' : ''; ?>>Nagad</option>
                </select>
              </div>

              <div class="col-md-6">
                <label for="transaction_id" class="form-label">Transaction ID</label>
                <input type="text" name="transaction_id" id="transaction_id" required class="form-control" placeholder="e.g., 6G7H8J9K" value="<?php echo htmlspecialchars($_POST['transaction_id'] ?? ''); ?>">
              </div>

              <div class="col-12 d-flex gap-2">
              

                <button type="submit" name="purchase" class="btn btn-primary flex-grow-1">
                  <i class="fa-solid fa-check"></i> Complete Payment
                </button>
              </div>
            </div>
          </form>

          <div class="mt-3" style="color:#b8c4d9;font-size:.95rem;">
            Tip: After sending money, paste the exact <strong>Transaction ID</strong>. Keep your phone handy in case we need quick verification.
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
