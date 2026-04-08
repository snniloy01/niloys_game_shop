<?php include 'db.php'; ?>
<?php include 'nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About • Niloy's game Shop</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">

  <style>
    :root{
      --valorant-dark:#0f1923;
      --valorant-red:#FF4655;
      --valorant-white:#FFFFFF;
      --muted:#cfd8e3;
    }
    body{background-color:var(--valorant-dark);color:#fff;font-family:"Parkinsans",sans-serif;}
    .container{max-width:1200px;}
    .gradient-bg{
      background:linear-gradient(90deg,#FF4655,#FF7E5F);
      color:#ffffff;padding:1.25rem 1.5rem;border-radius:12px;text-align:center;margin:2rem 0 1.5rem;
    }
    .mini{color:var(--muted);font-size:.95rem}
    .hero{position:relative;border-radius:16px;overflow:hidden;margin-top:1rem;}
    .hero img{width:100%;height:430px;object-fit:cover;filter:brightness(.8) saturate(1.05);}
    .hero-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0),rgba(0,0,0,.75));}
    .hero-text{position:absolute;left:0;right:0;bottom:0;padding:2rem;}
    .hero h1{font-weight:800;letter-spacing:.4px;}
    .badge-soft{display:inline-block;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);padding:.4rem .7rem;border-radius:20px;font-size:.85rem;margin-right:.4rem}
    .card{
      background-color:#10202c;border:1px solid rgba(255,255,255,0.08);
      border-top:3px solid transparent;border-radius:12px;transition:transform .25s ease, border-color .25s ease, box-shadow .25s ease;height:100%;
    }
    .card:hover{transform:translateY(-6px);border-top-color:var(--valorant-red);box-shadow:0 10px 28px rgba(0,0,0,.35);}
    .card img{height:190px;object-fit:cover;border-top-left-radius:12px;border-top-right-radius:12px;}
    .btn-custom,.btn-info{
      background-color:var(--valorant-red);color:#fff;border:none;padding:.6rem 1.2rem;font-weight:700;border-radius:20px;
      transition:transform .2s ease, filter .2s ease;
    }
    .btn-custom:hover,.btn-info:hover{transform:scale(1.05);filter:brightness(1.05);}
    .payment-pill{
      display:inline-flex;align-items:center;gap:.5rem;
      background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);
      padding:.5rem .9rem;border-radius:999px;margin:.25rem .35rem;font-weight:700;
    }
    .feature-icon{
      width:48px;height:48px;display:grid;place-items:center;border-radius:12px;
      background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);margin-right:.6rem;
    }
    .timeline{position:relative;padding-left:1.2rem;}
    .timeline:before{content:"";position:absolute;left:8px;top:0;bottom:0;width:2px;background:rgba(255,255,255,.2);}
    .timeline-item{position:relative;margin-bottom:1.1rem;padding-left:1rem;}
    .timeline-item .dot{position:absolute;left:-2px;top:.35rem;width:.75rem;height:.75rem;border-radius:50%;background:var(--valorant-red);box-shadow:0 0 0 3px rgba(255,70,85,.2);}
    .team-card img{height:220px;width:220px;object-fit:cover;border-radius:50%;margin-bottom:1rem;border:3px solid rgba(255,255,255,.15);}
    @media (max-width: 768px){.hero img{height:300px;}}
    .feature-icon {
  width: 48px;
  height: 48px;
  display: grid;
  place-items: center;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.12);
  margin-right: 0.6rem;
  font-size: 1.2rem;
}

  </style>
</head>
<body>

  <!-- HERO -->
  <div class="container">
    <section class="hero">
      <!-- Pexels hero image (gaming setup) -->
      <img src="https://images.pexels.com/photos/9072302/pexels-photo-9072302.jpeg?auto=compress&cs=tinysrgb&w=1600&h=900" alt="Gaming setup">
      <div class="hero-overlay"></div>
      <div class="hero-text">
        <span class="badge-soft"><i class="fa-solid fa-bolt"></i> Instant Game Top-ups</span>
        <span class="badge-soft"><i class="fa-solid fa-shield-halved"></i> Secure Payments</span>
        <h1 class="mt-3">About Niloy's game Shop</h1>
        <p class="lead mini">
          We’re a Bangladesh-based digital game shop. Buy <strong>Valorant VP</strong>, <strong>Hitman</strong>, <strong>COD: Warzone</strong> and more—pay easily with <strong>bKash</strong>, <strong>Nagad</strong>
        </p>
        <div class="mt-2">
          <a href="index.php#games" class="btn btn-custom me-2"><i class="fa-solid fa-cart-shopping"></i> Browse Games</a>
          <a href="index.php#posts" class="btn btn-info"><i class="fa-solid fa-book-open"></i> Read Updates</a>
        </div>
      </div>
    </section>
  </div>

  <!-- OUR MISSION -->
  <div class="container">
    <div class="gradient-bg">
      <h2><i class="fa-solid fa-flag-checkered"></i> Our Mission</h2>
      <p class="mt-2 mb-0">Deliver fast, safe, and friendly digital purchases for gamers—top-ups like Valorant VP and full games at fair prices with local payment convenience.</p>
    </div>
  </div>

 
<!-- WHAT WE SELL -->
<div class="container">
  <div class="row g-4">
    <!-- Valorant VP -->
    <div class="col-md-4">
      <div class="card">
        <img src="https://images.pexels.com/photos/9072306/pexels-photo-9072306.jpeg?auto=compress&cs=tinysrgb&w=1200&h=800" alt="Valorant VP">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <div class="feature-icon">
              <i class="fa-solid fa-fire-flame-curved text-danger"></i>
            </div>
            <h5 class="m-0 text-white">Valorant VP Top-up</h5>
          </div>
          <p class="mini">Quick VP credits for your Riot account. Local payments, smooth processing.</p>
        </div>
      </div>
    </div>
    <!-- COD / Hitman -->
    <div class="col-md-4">
      <div class="card">
        <img src="https://images.pexels.com/photos/9071746/pexels-photo-9071746.jpeg?auto=compress&cs=tinysrgb&w=1200&h=800" alt="Action shooter">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <div class="feature-icon">
              <i class="fa-solid fa-crosshairs text-white"></i>
            </div>
            <h5 class="m-0 text-white">COD: Warzone & Hitman</h5>
          </div>
          <p class="mini">Buy popular titles and add-ons with verified keys and clear instructions.</p>
        </div>
      </div>
    </div>
    <!-- Other Games -->
    <div class="col-md-4">
      <div class="card">
        <img src="https://images.pexels.com/photos/3945655/pexels-photo-3945655.jpeg?auto=compress&cs=tinysrgb&w=1200&h=800" alt="Console gamer">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <div class="feature-icon">
              <i class="fa-solid fa-gamepad text-white"></i>
            </div>
            <h5 class="m-0 text-white">More Games & Add-ons</h5>
          </div>
          <p class="mini">GTA, NFS, and other favorites—curated list updated regularly.</p>
        </div>
      </div>
    </div>
  </div>
</div>


  <!-- PAYMENT METHODS -->
  <div class="container mt-5">
    <div class="gradient-bg">
      <h2><i class="fa-solid fa-wallet"></i> Payment Methods</h2>
      <p class="mt-2 mb-0">Pay seamlessly with trusted Bangladeshi gateways.</p>
    </div>
    <div class="text-center">
      <span class="payment-pill"><i class="fa-solid fa-mobile-screen-button"></i> bKash</span>
      <span class="payment-pill"><i class="fa-solid fa-mobile-screen"></i> Nagad</span>
    </div>
    <p class="mini text-center mt-2">After payment, keep the transaction ID handy for faster confirmation.</p>
  </div>

  <!-- HOW TO BUY -->
  <div class="container mt-4">
    <div class="gradient-bg">
      <h2><i class="fa-solid fa-route"></i> How to Purchase</h2>
    </div>

    <div class="row g-4 align-items-center">
      <div class="col-md-6">
        <div class="timeline">
          <div class="timeline-item">
            <span class="dot"></span>
            <h6>1) Choose Your Game/Top-up</h6>
            <p class="mini">Open a product card (e.g., Valorant VP) and click <em>Buy Now</em>.</p>
          </div>
          <div class="timeline-item">
            <span class="dot"></span>
            <h6>2) Pay with bKash / Nagad </h6>
            <p class="mini">Send the amount and note the transaction ID.</p>
          </div>
          <div class="timeline-item">
            <span class="dot"></span>
            <h6>3) Submit Details</h6>
            <p class="mini">Provide your Riot ID / game account email as required for fulfillment.</p>
          </div>
          <div class="timeline-item">
            <span class="dot"></span>
            <h6>4) Get Delivery</h6>
            <p class="mini">We verify and deliver VP/keys promptly, with status shown in your order.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <!-- Pexels supporting image -->
        <img class="img-fluid rounded" style="border:1px solid rgba(255,255,255,0.12)"
             src="https://images.pexels.com/photos/9072675/pexels-photo-9072675.jpeg?auto=compress&cs=tinysrgb&w=1200&h=800"
             alt="Payment and purchase flow">
      </div>
    </div>
  </div>

  <!-- TRUST / GUARANTEES -->
  <div class="container mt-4">
    <div class="row g-4 text-center">
      <div class="col-md-4">
        <div class="feature-icon mx-auto mb-2"><i class="fa-solid fa-shield"></i></div>
        <h6>Secure & Verified</h6>
        <p class="mini">Legit keys, safe top-ups, and account data kept private.</p>
      </div>
      <div class="col-md-4">
        <div class="feature-icon mx-auto mb-2"><i class="fa-solid fa-clock"></i></div>
        <h6>Fast Fulfillment</h6>
        <p class="mini">Most orders processed quickly after confirmation.</p>
      </div>
      <div class="col-md-4">
        <div class="feature-icon mx-auto mb-2"><i class="fa-solid fa-headset"></i></div>
        <h6>Friendly Support</h6>
        <p class="mini">Need help? Reach out with your order ID or transaction ID.</p>
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="index.php#games" class="btn btn-custom"><i class="fa-solid fa-cart-shopping"></i> Start Shopping</a>
      <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
        <a href="add_game.php" class="btn btn-info ms-2"><i class="fa-solid fa-plus"></i> Add New Product</a>
      <?php endif; ?>
    </div>
  </div>

 

  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
