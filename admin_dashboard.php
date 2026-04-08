<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

include 'db.php';

/* ------------------------------
   Handle toggle "Delivered" (AJAX/POST) BEFORE any HTML output
--------------------------------*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_delivered'])) {
    $purchase_id = intval($_POST['purchase_id'] ?? 0);
    $delivered   = isset($_POST['delivered']) && $_POST['delivered'] == '1' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE purchases SET delivered = ? WHERE id = ?");
    $stmt->bind_param("ii", $delivered, $purchase_id);
    $ok = $stmt->execute();
    $stmt->close();

    // If AJAX: return JSON and exit with no extra output
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if ($isAjax) {
        if (function_exists('ob_get_length') && ob_get_length()) { ob_clean(); }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => $ok ? 1 : 0]);
        exit;
    }

    // Non-AJAX fallback
    header('Location: admin_dashboard.php');
    exit;
}

/* ------------------------------
   Handle delete (GET)
--------------------------------*/
if (isset($_GET['delete'])) {
    $purchase_id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM purchases WHERE id = $purchase_id";
    if ($conn->query($delete_sql) === TRUE) {
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $delete_error = true;
    }
}

/* ------------------------------
   Handle search
--------------------------------*/
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}

/* ------------------------------
   Totals
--------------------------------*/
$sql = "SELECT SUM(total_price) AS total_earnings FROM purchases";
$result = $conn->query($sql);
$total_earnings = $result->fetch_assoc()['total_earnings'] ?? 0;

$sql = "SELECT SUM(quantity) AS total_items_sold FROM purchases";
$result = $conn->query($sql);
$total_items_sold = $result->fetch_assoc()['total_items_sold'] ?? 0;

/* ------------------------------
   Purchase history (with delivered)
--------------------------------*/
$sql = "SELECT p.id, g.name AS game_name, p.quantity, p.total_price, p.purchased_at, 
               p.name AS buyer_name, p.email, p.payment_method, p.transaction_id, p.delivered
        FROM purchases p 
        JOIN games g ON p.game_id = g.id 
        WHERE g.name LIKE '%$search_query%' 
        ORDER BY p.purchased_at DESC";
$purchase_history = $conn->query($sql);
?>

<?php include 'nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet" />

    <style>
        :root { --valorant-red:#FF4655; --dark-bg:#121212; --white:#FFFFFF; }
        body { background-color:#0f1923; color:#ffffff; font-family:"Parkinsans",sans-serif; }
        .dashboard-header{ background:#0f1923; border:1px solid var(--valorant-red); color:var(--white);
            border-radius:10px; padding:2rem; text-align:center; margin-bottom:2rem; box-shadow:0 4px 10px rgba(0,0,0,.1);}
        .dashboard-header h2{ color:var(--valorant-red); }
        .card-summary{ background:#0f1923; border:1px solid var(--valorant-red); color:var(--white);
            box-shadow:0 8px 20px rgba(0,0,0,.1); transition:transform .3s; border-radius:15px;}
        .card-summary:hover{ transform:translateY(-5px); }
        .btn-primary{ background:var(--valorant-red); border:none; }
        .btn-primary:hover{ background:#e03e4d; }
        .btn-delete{ background:var(--valorant-red); color:var(--white); border:none; transition:transform .2s, background .3s;}
        .btn-delete:hover{ background:#e03e4d; transform:scale(1.05); }
        .search-box input{ background:var(--dark-bg); color:var(--white); border:1px solid #333; }
        .search-box button{ background:var(--valorant-red); color:var(--white); border:none; }
        table{ background:var(--dark-bg); color:var(--white); }
        .purchase-history th{ background:var(--valorant-red); color:var(--white); }
        .purchase-history td{ text-align:center; background:#0f1923; color:var(--white); }
        .in{ color:var(--valorant-red); }
        .badge-status{ font-weight:700; }
        .form-check-input{ cursor:pointer; }
        .form-check-input:checked{ background-color:var(--valorant-red); border-color:var(--valorant-red); }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
            <p>Manage purchases, view analytics, and add new content.</p>
        </div>

        <?php if (!empty($delete_error)): ?>
          <div class="alert alert-danger text-center">Error deleting purchase.</div>
        <?php endif; ?>

        <div class="row text-center mb-5">
            <div class="col-md-6">
                <div class="card card-summary p-3">
                    <h5><i class="fas fa-bangladeshi-taka-sign in"></i> Total Earnings</h5>
                    <p class="fs-4 fw-bold in">৳<?php echo number_format($total_earnings, 2); ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-summary p-3">
                    <h5><i class="fas fa-shopping-cart in"></i> Total Items Sold</h5>
                    <p class="fs-4 fw-bold in"><?php echo $total_items_sold; ?></p>
                </div>
            </div>
        </div>

        <form method="GET" action="" class="search-box mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by game name" value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>

        <h3><i class="fas fa-history"></i> Purchase History</h3>
        <table class="table table-striped purchase-history">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Game</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Buyer Name</th>
                    <th>Email</th>
                    <th>Payment Method</th>
                    <th>Transaction ID</th>
                    <th>Purchased At</th>
                    <th>Status</th>
                    <th>Delivered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($purchase_history && $purchase_history->num_rows > 0): ?>
                    <?php while ($row = $purchase_history->fetch_assoc()): 
                        $isDelivered = (int)$row['delivered'] === 1;
                    ?>
                        <tr id="row-<?php echo $row['id']; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['game_name']); ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>৳<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                            <td><?php echo $row['purchased_at']; ?></td>

                            <td>
                                <?php if ($isDelivered): ?>
                                  <span class="badge bg-success badge-status">Delivered</span>
                                <?php else: ?>
                                  <span class="badge bg-warning text-dark badge-status">Pending</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <form class="delivered-form d-inline" method="POST" action="">
                                    <input type="hidden" name="toggle_delivered" value="1">
                                    <input type="hidden" name="purchase_id" value="<?php echo $row['id']; ?>">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input delivered-checkbox" type="checkbox" 
                                               name="delivered" value="1"
                                               <?php echo $isDelivered ? 'checked' : ''; ?>
                                               data-id="<?php echo $row['id']; ?>">
                                    </div>
                                </form>
                            </td>

                            <td>
                                <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>" 
                                   class="btn btn-delete btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this purchase?')">
                                   <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="12">No purchases found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      // AJAX toggle for delivered checkbox
      document.querySelectorAll('.delivered-checkbox').forEach(cb => {
        cb.addEventListener('change', async (e) => {
          const checkbox = e.target;
          const rowId = checkbox.dataset.id;

          const fd = new FormData();
          fd.append('toggle_delivered', '1');
          fd.append('purchase_id', rowId);
          fd.append('delivered', checkbox.checked ? '1' : '0');

          const url = window.location.pathname + window.location.search;

          try {
            const res = await fetch(url, {
              method: 'POST',
              headers: { 'X-Requested-With': 'XMLHttpRequest' },
              body: fd
            });

            if (!res.ok) { throw new Error('Non-200'); }

            const data = await res.json();

            const row = document.getElementById('row-' + rowId);
            const badgeCell = row.querySelector('td:nth-child(10)'); // Status column

            if (data && data.success) {
              badgeCell.innerHTML = checkbox.checked
                ? '<span class="badge bg-success badge-status">Delivered</span>'
                : '<span class="badge bg-warning text-dark badge-status">Pending</span>';
            } else {
              checkbox.checked = !checkbox.checked;
              alert('Failed to update delivery status. Please try again.');
            }
          } catch (err) {
            checkbox.checked = !checkbox.checked;
            alert('Network error while updating status.');
          }
        });
      });
    </script>
</body>
</html>
