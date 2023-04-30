<?php
require_once 'includes/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $post_id = $_POST['post_id'];
  $content = $_POST['content'];
  
  $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
  $stmt->execute([$post_id]);
  $post = $stmt->fetch();
  echo "here;";
  echo $post;

  
  if ($post && $post['user_id'] === $_SESSION['user_id']) {
    $stmt = $pdo->prepare('UPDATE posts SET content = ? WHERE id = ?');
    $stmt->execute([$content, $post_id]);
    
    header('Location: timeline.php');
    exit();
  }
}

if (isset($_GET['post_id'])) {
  $post_id = $_GET['post_id'];
  

  $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
  $stmt->execute([$post_id]);
  $post = $stmt->fetch();

  if (!$post || $post['user_id'] !== $_SESSION['user_id']) {
    header('Location: timeline.php');


    exit();
  }
} else {
  header('Location: timeline.php');

  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Post</title>
  <link rel="stylesheet" type="text/css" href="css/timeline_css.css">
  
</head>
<body>
<div class="menu">
  <ul>
    <li><a href="./timeline.php">Home</a></li>
    <li><a href="./profile.php">Profile</a></li>
    <li><a href="./setting.php">Settings</a></li>
    <li><a href="./logout.php" >Logout</a></li>
  </ul>
</div>


<div class="container">
  <h2 class="title">Edit Post</h2>

  <div class="post-form">
    <form method="post">
      <textarea name="content" class="post-input"><?php echo $post['content']; ?></textarea>
      <br>
      <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
      <input type="submit" value="Save Changes" class="post-button">
    </form>
  </div>
</div>

</body>
</html>
