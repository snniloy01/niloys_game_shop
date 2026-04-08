<?php
// Protect the page - restrict access to logged-in admins
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

include 'db.php';
include 'nav.php';

$flash = "";

/* ---------------------------
   DELETE (GET)
----------------------------*/
if (isset($_GET['delete'])) {
    $post_id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM posts WHERE id = ?";
    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param('i', $post_id);
        if ($stmt->execute()) {
            $flash = '<div class="alert alert-success mt-3">Post deleted successfully!</div>';
        } else {
            $flash = '<div class="alert alert-danger mt-3">Error deleting: ' . htmlspecialchars($conn->error) . '</div>';
        }
        $stmt->close();
    }
}

/* ---------------------------
   CREATE (POST)
----------------------------*/
if (isset($_POST['submit'])) {
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $flash = '<div class="alert alert-danger mt-3">Please fill in both Title and Content.</div>';
    } else {
        $sql = "INSERT INTO posts (title, content) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ss', $title, $content);
            if ($stmt->execute()) {
                $flash = '<div class="alert alert-success mt-3">Post added successfully!</div>';
            } else {
                $flash = '<div class="alert alert-danger mt-3">Error: ' . htmlspecialchars($conn->error) . '</div>';
            }
            $stmt->close();
        }
    }
}

/* ---------------------------
   EDIT state (GET) -> fetch to prefill
----------------------------*/
$edit_post = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $edit_post = $res->fetch_assoc();
    }
    $stmt->close();
}

/* ---------------------------
   UPDATE (POST)
----------------------------*/
if (isset($_POST['update'])) {
    $id      = (int)($_POST['id'] ?? 0);
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($id <= 0 || $title === '' || $content === '') {
        $flash = '<div class="alert alert-danger mt-3">Missing required fields for update.</div>';
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param('ssi', $title, $content, $id);
        if ($stmt->execute()) {
            $flash = '<div class="alert alert-success mt-3">Post updated successfully!</div>';
            // Clear edit state after success
            $edit_post = null;
        } else {
            $flash = '<div class="alert alert-danger mt-3">Error updating: ' . htmlspecialchars($conn->error) . '</div>';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add / Edit Post</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">

  <style>
    :root {
      --valorant-red: #FF4655;
      --valorant-white: #FFFFFF;
      --valorant-light-gray: #F4F4F4;
      --bg-dark: #0f1923;
    }
    body { background-color: var(--bg-dark); color: #ffffff; font-family: "Parkinsans", sans-serif; }
    .container { max-width: 900px; }
    .form-container {
      background-color: var(--bg-dark);
      border : 1px solid var(--valorant-red);
      padding: 3rem;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }
    .form-container h2 { color: var(--valorant-red); }
    .btn-primary { background-color: var(--valorant-red); border: none; }
    .btn-primary:hover { background-color: #e03e4d; }
    .post-card {
      background-color: var(--bg-dark);
      border: 1px solid var(--valorant-red);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
      border-radius: 10px;
      margin-bottom: 1rem;
    }
    .post-card h4 { color: var(--valorant-red); }
    .post-card i { color: var(--valorant-red); }
    .form-control, textarea {
      background-color: #132130;
      color: #fff;
      border: 1px solid rgba(255,70,85,.6);
    }
    .form-control:focus, textarea:focus {
      border-color: var(--valorant-red);
      box-shadow: 0 0 0 0.2rem rgba(255,70,85,.25);
    }
  </style>
</head>
<body>
  <div class="container mt-5">

    <?php if (!empty($flash)) echo $flash; ?>

    <!-- Add or Edit Form -->
    <div class="form-container">
      <?php if ($edit_post): ?>
        <h2><i class="fas fa-edit"></i> Edit Post</h2>
        <form method="POST" action="">
          <input type="hidden" name="id" value="<?php echo (int)$edit_post['id']; ?>">
          <div class="mb-3">
            <label for="title" class="form-label">Post Title</label>
            <input type="text" class="form-control" id="title" name="title"
                   value="<?php echo htmlspecialchars($edit_post['title']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required><?php
              echo htmlspecialchars($edit_post['content']);
            ?></textarea>
          </div>
          <button type="submit" name="update" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Post
          </button>
          <a href="add_post.php" class="btn btn-secondary">Cancel</a>
        </form>
      <?php else: ?>
        <h2><i class="fas fa-plus-circle"></i> Add New Post</h2>
        <form method="POST" action="">
          <div class="mb-3">
            <label for="title" class="form-label">Post Title</label>
            <input type="text" class="form-control" id="title" name="title"
                   value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
          </div>
          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required><?php
              echo htmlspecialchars($_POST['content'] ?? '');
            ?></textarea>
          </div>
          <button type="submit" name="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Post
          </button>
        </form>
      <?php endif; ?>
    </div>

    <!-- Display Added Posts -->
    <div class="post-list">
      <h3><i class="fas fa-list"></i> Recently Added Posts</h3>
      <?php
      $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT 5";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0):
        while ($post = $result->fetch_assoc()):
      ?>
          <div class="post-card">
            <h4><i class="fas fa-newspaper"></i> <?php echo htmlspecialchars($post['title']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <div class="d-flex gap-2">
              <a href="?edit=<?php echo (int)$post['id']; ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
              </a>
              <a href="?delete=<?php echo (int)$post['id']; ?>" class="btn btn-danger btn-sm"
                 onclick="return confirm('Are you sure you want to delete this post?')">
                <i class="fas fa-trash-alt"></i> Delete
              </a>
            </div>
          </div>
      <?php
        endwhile;
      else:
        echo '<div class="alert alert-info">No posts added yet.</div>';
      endif;
      ?>
    </div>
  </div>

  <?php include 'footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
