<?php
require_once 'includes/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (!empty($name) && !empty($email) && !empty($password)) {
    $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?');
    $stmt->execute([$name, $email, $password, $_SESSION['user_id']]);
    $_SESSION['name'] = $name; // Update session variable with new name
    header('Location: profile.php');
    exit();
  } else {
    $error = 'Please fill out all fields.';
  }
}

$stmt = $pdo->prepare('SELECT name, email FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Profile</title>
  <link rel="stylesheet" type="text/css" href="css/setting_css.css">
</head>
<body>
<div class="menu">
  <ul>
    <li><a href="./timeline.php">Home</a></li>
    <li><a href="./profile.php">Profile</a></li>
    <li><a href="#">Settings</a></li>
    <li><a href="./logout.php" >Logout</a></li>
  </ul>
</div>

<div class="container">
  <h2 class="title">Edit Profile</h2>
  <?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
  <?php endif; ?>
  <form method="post">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?php echo $user['name']; ?>">
    <br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>">
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password">
    <br>
    <input type="submit" value="Save Changes" class="save-button">
  </form>
</div>

</body>
</html>
