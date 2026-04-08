<?php
// Protect the page - restrict access to logged-in admins
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

include 'db.php';
include 'nav.php';

// --------------------
// Handle deletion
// --------------------
if (isset($_GET['delete'])) {
    $game_id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM games WHERE id = ?";
    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param('i', $game_id);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success mt-3">Game deleted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger mt-3">Error: ' . $conn->error . '</div>';
        }
        $stmt->close();
    }
}

// --------------------
// Handle editing
// --------------------
$edit_game = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $sql = "SELECT * FROM games WHERE id = $edit_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $edit_game = $result->fetch_assoc();
    }
}

// Handle update
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $_POST['price'];

    $sql = "UPDATE games SET name='$name', description='$description', price='$price' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo '<div class="alert alert-success mt-3">Game updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger mt-3">Error updating: ' . $conn->error . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">
    <title>Manage Games</title>
    <style>
        :root {
            --valorant-red: #FF4655;
            --valorant-light-gray: #F4F4F4;
        }
        body {
            background-color: #0f1923;
            color: #ffffff;
            font-family: "Parkinsans", sans-serif;
        }
        .container { max-width: 900px; }
        input, textarea { background-color: #0f1923; color:#fff; }
        .form-container {
            background-color: #0f1923;
            border: 1px solid var(--valorant-red);
            padding: 3rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .form-container h2 { color: var(--valorant-red); }
        .btn-primary { background-color: var(--valorant-red); border: none; }
        .btn-primary:hover { background-color: #e03e4d; }
        .game-list { margin-top: 2rem; }
        .game-card {
            background-color: #0f1923;
            border: 1px solid var(--valorant-light-gray);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        .game-card h4 { color: var(--valorant-red); }
        .game-card .price { font-weight: bold; color: #ffffff; }
        .game-card i { color: var(--valorant-red); }
        .game-card p { color: #ffffff; }
    </style>
</head>
<body>
    <div class="container mt-5">

        <!-- Add or Edit Form -->
        <div class="form-container">
            <?php if ($edit_game): ?>
                <h2><i class="fas fa-edit"></i> Edit Game</h2>
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $edit_game['id']; ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Game Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo htmlspecialchars($edit_game['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($edit_game['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (৳)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" 
                               value="<?php echo htmlspecialchars($edit_game['price']); ?>" required>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Game
                    </button>
                    <a href="add_game.php" class="btn btn-secondary">Cancel</a>
                </form>
            <?php else: ?>
                <h2><i class="fas fa-gamepad"></i> Add New Game</h2>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Game Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (৳)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Game
                    </button>
                </form>

                <?php
                if (isset($_POST['submit'])) {
                    $name = $conn->real_escape_string($_POST['name']);
                    $description = $conn->real_escape_string($_POST['description']);
                    $price = $_POST['price'];

                    $sql = "INSERT INTO games (name, description, price) VALUES ('$name', '$description', '$price')";
                    if ($conn->query($sql) === TRUE) {
                        echo '<div class="alert alert-success mt-3">Game added successfully!</div>';
                    } else {
                        echo '<div class="alert alert-danger mt-3">Error: ' . $conn->error . '</div>';
                    }
                }
                ?>
            <?php endif; ?>
        </div>

        <!-- Game List -->
        <div class="game-list">
            <h3><i class="fas fa-list"></i> Recently Added Games</h3>
            <?php
            $sql = "SELECT * FROM games ORDER BY id DESC LIMIT 5";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($game = $result->fetch_assoc()) {
                    ?>
                    <div class="game-card">
                        <h4><i class="fas fa-gamepad"></i> <?php echo htmlspecialchars($game['name']); ?></h4>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($game['description']); ?></p>
                        <p class="price">৳<?php echo number_format($game['price'], 2); ?></p>
                        <!-- Edit and Delete Buttons -->
                        <a href="?edit=<?php echo $game['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="?delete=<?php echo $game['id']; ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this game?')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="alert alert-info">No games added yet.</div>';
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
