<?php
require_once 'includes/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$stmt = $pdo->query('SELECT posts.*, users.name FROM posts LEFT JOIN users ON posts.user_id = users.id ORDER BY created_at DESC');
$posts = $stmt->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];
    
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();
    
    if ($post) {
      switch ($action) {
        case 'like':
          $stmt = $pdo->prepare('UPDATE posts SET likes = likes + 1 WHERE id = ?');
          $stmt->execute([$post_id]);
          break;
        case 'dislike':
          $stmt = $pdo->prepare('UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?');
          $stmt->execute([$post_id]);
          break;
        case 'delete':
          if ($post['user_id'] === $_SESSION['user_id']) {
            $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
            $stmt->execute([$post_id]);
          }
          break;
        case 'report':
                  $stmt = $pdo->prepare("
                      UPDATE posts
                      SET reports = CASE 
                          WHEN reports < 3 THEN reports + 1
                          ELSE reports
                      END
                      WHERE id = :post_id;
                      
                      DELETE FROM posts
                      WHERE id = :post_id AND reports > 2;
                  ");

                  // bind the post_id parameter
                  $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

                  // execute the statement
                  $stmt->execute();
      }
    }
    header('Location: profile.php');
    exit();
  }
  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $content = $_POST['content'];

  if (!empty($content)) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare('INSERT INTO posts (user_id, content) VALUES (?, ?)');
    $stmt->execute([$user_id, $content]);
    header('Location: profile.php');
    exit();
  }
}
?>
<!-- <!DOCTYPE html> -->
<html>
<head>
  <title>Timeline Page</title>
  <link rel="stylesheet" type="text/css" href="css/timeline_css.css">
</head>
<body>
<div class="menu">
  <ul>
    <li><a href="./timeline.php">Home</a></li>
    <li><a href="#">Profile</a></li>
    <li><a href="./setting.php">Settings</a></li>
    <li><a href="./logout.php" >Logout</a></li>
  </ul>
</div>


  <div class="container">
    <h2 class="title">Welcome , <?php echo $_SESSION['name']; ?>!</h2>

    <div class="post-form">
      <h2>Add a new post:</h2>
      <form method="post">
        <textarea name="content" class="post-input"></textarea>
        <br>
        <input type="submit" value="Post" class="post-button">
      </form>
    </div>

    <hr>

    <h2 class="subtitle">Recent Posts:</h2>
    <?php foreach ($posts as $post): ?>
        <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
      <div class="post">
        <p class="post-meta">Posted by<span style="color:black; "> <?php echo $post['name']; ?></span> on <?php echo $post['created_at']; ?></p>
        <p class="post-content"><?php echo $post['content']; ?></p>
        <p class="post-likes">Likes: <?php echo $post['likes']; ?> | Dislikes: <?php echo $post['dislikes']; ?></p>
       
        
          <div class="post-actions">
            <form method="post" class="delete-form">
              <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
              <input type="hidden" name="action" value="delete">
              <button type="submit" class="delete-button">Delete</button>
            </form>
            
            <a href="postedit.php?post_id=<?php echo $post["id"]; ?>" class="edit-button">Edit Post</a>
        

          </div>
        <?php endif; ?>
      </div>
      <hr>
    <?php endforeach; ?>
  </div>
</body>
</html>
