<?php include 'db.php'; ?>
<?php include 'nav.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Game Store</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">

  <style>
    :root {
      --valorant-dark: #0f1923;
      --valorant-red:  #FF4655;
      --valorant-red-2:#FF7E5F;
      --valorant-white:#FFFFFF;
      --valorant-gray: #333F4B;
      --valorant-light-gray: #EAEAEA;
    }

    body {
      background-color: var(--valorant-dark);
      color: #ffffff;
      font-family: "Parkinsans", sans-serif;
    }

    .container { max-width: 1200px; }

    .gradient-bg {
      background: linear-gradient(90deg, var(--valorant-red), var(--valorant-red-2));
      color: #ffffff;
      padding: 1.5rem;
      border-radius: 10px;
      text-align: center;
      margin-bottom: 2rem;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Carousel */
    .carousel-item img {
      height: 400px;
      width: 100%;
      object-fit: cover;
    }
    .carousel-caption {
      background: rgba(0, 0, 0, 0.5);
      padding: 1rem;
      border-radius: 8px;
    }

    /* Cards */
    .card {
      border: 1px solid var(--valorant-red);
      border-top: 3px solid transparent;
      transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
      margin-bottom: 2rem;
      background-color: var(--valorant-dark);
      color: #ffffff;
    }
    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.2);
      border-top-color: var(--valorant-red);
    }

    .btn-custom, .btn-info {
      background-color: var(--valorant-red);
      color: var(--valorant-white);
      border: none;
      padding: 0.6rem 1.2rem;
      font-size: 0.9rem;
      font-weight: bold;
      transition: background 0.3s ease, transform 0.2s ease;
      border-radius: 20px;
    }
    .btn-custom:hover, .btn-info:hover { background-color: #e03e4d; transform: scale(1.05); }

    .badge-custom {
      background: var(--valorant-red);
      color: var(--valorant-white);
      padding: 0.4rem 0.8rem;
      font-size: 0.8rem;
      border-radius: 20px;
    }

    /* Fix duplicated property here */
    .post-card {
      background-color: #0f1923;
      border: 1px solid var(--valorant-red);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
      border-radius: 10px;
      margin-bottom: 2rem;
      padding: 1.5rem;
    }
    .post-card h5 { color: var(--valorant-red); }
    .post-card p { color: var(--valorant-light-gray); }

    /* HERO — Valorant-styled welcome */
    .hero-wrap {
      margin: 30px auto 10px;
    }
    .hero {
      position: relative;
      border-radius: 18px;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,0.08);
      background:
        radial-gradient(1200px 400px at -10% -20%, rgba(255,70,85,.18), transparent 60%),
        radial-gradient(800px 300px at 120% 120%, rgba(255,126,95,.15), transparent 60%),
        #0c1620;
      padding: 40px 28px;
      isolation: isolate;
    }
    .hero::before {
      /* neon top border */
      content: "";
      position: absolute;
      inset: 0;
      pointer-events: none;
      border-top: 2px solid rgba(255,70,85,.9);
      filter: drop-shadow(0 0 8px rgba(255,70,85,.65));
      opacity: .95;
    }
    .hero-badges .badge-soft {
      display: inline-block;
      background: rgba(255,255,255,0.08);
      border: 1px solid rgba(255,255,255,0.16);
      padding: .45rem .75rem;
      border-radius: 999px;
      margin-right: .5rem;
      font-weight: 700;
      font-size: .9rem;
    }
    .hero h1 {
      font-weight: 800;
      letter-spacing: .4px;
      margin-top: 10px;
      margin-bottom: 10px;
    }
    .hero p.lead {
      color: #cfd8e3;
      margin-bottom: 18px;
    }
    .hero-cta .btn {
      margin-right: 10px;
      margin-top: 6px;
    }

    /* Feature icon (for later reuse) */
    .feature-icon {
      width: 48px; height: 48px;
      display: grid; place-items: center;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.12);
      margin-right: 0.6rem;
      font-size: 1.2rem;
    }

    @media (max-width: 768px) {
      .gradient-bg { font-size: 1.2rem; padding: 1rem; }
      .card-title { font-size: 1rem; }
      .btn-custom { font-size: 0.85rem; }
      .hero { padding: 28px 18px; }
    }
  </style>
</head>
<body>

  <!-- Carousel -->
  <div id="carouselExample" class="carousel slide mb-5" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="https://i.ytimg.com/vi/AcADYKT4O78/maxresdefault.jpg" class="d-block w-100" alt="Game 1">
        <div class="carousel-caption d-none d-md-block">
          <h2><i class="fas fa-gamepad"></i> Niloy's game shop</h2>
          <p>Discover the best games and exclusive deals!</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="https://static1.squarespace.com/static/5ccb12039b8fe875b08fae37/t/5f6274abd7434f2d80fc01a9/1600287921434/hitherto-board-games.jpg?format=1500w" class="d-block w-100" alt="Game 1">
        <div class="carousel-caption d-none d-md-block">
          <h2><i class="fas fa-gamepad"></i> Game Store</h2>
          <p>Discover the best games and exclusive deals!</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="https://cdn.sanity.io/images/dsfx7636/news_live/a9118d1f77e9f5fd0c292a0c7c4fb860e25af23e-1920x1080.jpg" class="d-block w-100" alt="Game 2">
        <div class="carousel-caption d-none d-md-block">
          <h2><i class="fas fa-newspaper"></i> Exciting Posts</h2>
          <p>Stay updated with the latest gaming news and reviews.</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- Welcome / Hero -->
  <div class="container hero-wrap" id="welcome">
    <section class="hero">
      <div class="hero-badges">
        <span class="badge-soft"><i class="fa-solid fa-bolt"></i> Fast Delivery</span>
        <span class="badge-soft"><i class="fa-solid fa-shield-halved"></i> Secure Payments</span>
        <span class="badge-soft"><i class="fa-solid fa-gamepad"></i> Curated Titles</span>
      </div>
      <h1>Welcome to <span style="color:var(--valorant-red)">Niloys game Shop</span></h1>
      <p class="lead">
        Your Bangladesh-based digital game shop. Top-up <strong>Valorant VP</strong> and buy games like
        <strong>COD: Warzone</strong>, <strong>Hitman</strong>, <strong>GTA</strong> & more.
        Pay with <strong>bKash</strong>, <strong>Nagad</strong>, or <strong>Rocket</strong>.
      </p>
      <div class="hero-cta">
        <a href="#games" class="btn btn-custom"><i class="fa-solid fa-cart-shopping"></i> Start Shopping</a>
        <a href="#posts" class="btn btn-info"><i class="fa-solid fa-book-open"></i> Read Updates</a>
      </div>
    </section>
  </div>

  <div class="container" id="games">
    <!-- Games Section -->
    <div class="gradient-bg">
      <h2><i class="fas fa-gamepad"></i> Available Games</h2>
    </div>

    <div class="row">
      <?php
      $sql = "SELECT * FROM games";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          // Determine the image URL based on the game name
          $image_url = 'https://via.placeholder.com/350x200';
          if (stripos($row['name'], 'Valorant') !== false) {
            $image_url = 'https://i.ytimg.com/vi/xi10SuaE49I/maxresdefault.jpg';
          } elseif (stripos($row['name'], 'GTA 5') !== false || stripos($row['name'], 'GTA') !== false) {
            $image_url = 'https://sm.pcmag.com/pcmag_me/news/s/sony-confi/sony-confirms-gta-v-is-4k60fps-on-ps5_mvu1.jpg';
          } elseif (stripos($row['name'], 'COD Warzone') !== false || stripos($row['name'], 'COD') !== false) {
            $image_url = 'https://wallpapers.com/images/featured/call-of-duty-warzone-4k-kzetz7h7t75073ye.jpg';
          } elseif (stripos($row['name'], 'NFSMW') !== false || stripos($row['name'], 'Need for Speed') !== false) {
            $image_url = 'https://c4.wallpaperflare.com/wallpaper/551/273/298/bmw-m3-gtr-need-for-speed-most-wanted-need-for-speed-most-wanted-2012-video-game-car-street-racing-hd-wallpaper-preview.jpg';
          }

          echo '
            <div class="col-md-4">
              <div class="card mb-4">
                <img src="' . $image_url . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">
                <div class="card-body text-center">
                  <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                  <p class="card-text">' . htmlspecialchars($row['description']) . '</p>
                  <p class="card-text"><strong>৳' . number_format($row['price'], 2) . '</strong></p>
                  <a href="purchase.php?id=' . intval($row['id']) . '" class="btn btn-custom"><i class="fas fa-cart-plus"></i> Buy Now</a>
                </div>
              </div>
            </div>';
        }
      } else {
        echo '<p>No games available.</p>';
      }
      ?>
    </div>

    <!-- Posts Section -->
    <div class="gradient-bg" id="posts">
      <h2><i class="fas fa-newspaper"></i> Latest Posts</h2>
    </div>

    <div class="row">
      <?php
      $sql = "SELECT * FROM posts ORDER BY created_at DESC";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '
            <div class="col-md-4">
              <div class="post-card">
                <div class="card-body text-center">
                  <h5 class="card-title"><i class="fas fa-pen"></i> ' . htmlspecialchars($row['title']) . '</h5>
                  <p class="card-text">' . htmlspecialchars(substr($row['content'], 0, 100)) . '...</p>
                  <p class="card-text"><small><i class="fas fa-calendar-alt"></i> ' . htmlspecialchars($row['created_at']) . '</small></p>
                  <a href="#" class="btn btn-info"><i class="fas fa-book-open"></i> Read More</a>
                </div>
              </div>
            </div>';
        }
      } else {
        echo '<p>No posts available.</p>';
      }
      ?>
    </div>
  </div>

  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>
