<!DOCTYPE html>
<html>
  <head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="css/login_css.css">
  </head>
  <body>
    <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once 'includes/db.php';

        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);

        header("Location: login.php");
        exit;
      }
    ?>

    <h1>Sign Up</h1>
    <form action="" method="post">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required><br>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required><br>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br>

      <input type="submit" value="Submit">
      <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>

    </form>
  </body>
</html>
