<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/login_css.css">
  </head>
  
  <body>
    <?php
      session_start();

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        require_once 'includes/db.php';


        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);


        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
        
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['name'] = $user['name'];

          
          header("Location: timeline.php");
          exit;
        } else {
          $error_message = "Invalid email or password.";
        }
      }
    ?>

    <h1>Login</h1>
    <?php if (isset($error_message)): ?>
      <p class="error-message" ><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="" method="post">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required><br>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br>

      <input type="submit" value="Submit">
      <p class="signup-text">Don't have an account yet? <a href="signup.php">Sign up here</a> to get started.</p>

    </form>
  </body>
</html>
