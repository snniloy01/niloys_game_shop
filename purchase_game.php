<?php
include 'db.php'; // Database connection
include 'nav.php'; // Navbar

// Check if a game ID and quantity are provided
if (isset($_POST['game_id']) && isset($_POST['quantity'])) {
    $game_id = intval($_POST['game_id']);
    $quantity = intval($_POST['quantity']);

    // Fetch the game price
    $sql = "SELECT price FROM games WHERE id = $game_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $game = $result->fetch_assoc();
        $price = $game['price'];

        // Calculate total price
        $total_price = $price * $quantity;

        // Insert the purchase into the purchases table
        $sql = "INSERT INTO purchases (game_id, quantity, total_price) 
                VALUES ($game_id, $quantity, $total_price)";
        if ($conn->query($sql) === TRUE) {
            echo '<div style="background-color: #0f1923; color: #ffffff; border: 1px solid #ff4655; padding: 15px; margin: 10px 0; border-radius: 5px;">';
            echo '<strong style="color: #ff4655;">Success!</strong> Purchase recorded successfully.';
            echo '</div>';
        } else {
            echo '<div style="background-color: #0f1923; color: #ffffff; border: 1px solid #ff4655; padding: 15px; margin: 10px 0; border-radius: 5px;">';
            echo '<strong style="color: #ff4655;">Error:</strong> ' . $conn->error;
            echo '</div>';
        }
    } else {
        echo '<div style="background-color: #0f1923; color: #ffffff; border: 1px solid #ff4655; padding: 15px; margin: 10px 0; border-radius: 5px;">';
        echo '<strong style="color: #ff4655;">Error:</strong> Game not found!';
        echo '</div>';
    }
} else {
    echo '<div style="background-color: #0f1923; color: #ffffff; border: 1px solid #ff4655; padding: 15px; margin: 10px 0; border-radius: 5px;">';
    echo '<strong style="color: #ff4655;">Warning:</strong> Invalid purchase request.';
    echo '</div>';
}
?>
