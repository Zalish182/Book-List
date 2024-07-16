<?php
session_start();

if (isset($_SESSION["user"])) {
  header("Location: index.php");
  die();
}

if (isset($_POST["login"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];  // We'll include the password field now

  // **Required:** Validate and sanitize user input
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // **Choose one approach:**
  // Option 1: Simplified Login (insecure)
  if (!empty($email)) {  // Check if email is entered (insecure)
    $_SESSION["user"] = "yes";
    header("Location: index.php");  // Redirect to index.php
    die();
  } else {
    echo "<div class='alert alert-danger'>Please enter your email</div>";
  }

  // Option 2: Secure Login (recommended)
  // **Assuming secure password hashing during registration:**
  require_once "database.php"; // Include connection to database
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $sql);
  $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

  if ($user) {
    if (password_verify($password, $user["password"])) {  // Verify password
      $_SESSION["user"] = "yes";
      header("Location: index.php");  // Redirect to index.php
      die();
    } else {
      echo "<div class='alert alert-danger'>Password does not match</div>";
    }
  } else {
    echo "<div class='alert alert-danger'>Email does not match</div>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">  </head>
<body>
  <div class="container">  <h1>Login</h1>
    <form action="login.php" method="post">
      <div class="form-group">
        <input type="email" placeholder="Enter Email:" name="email" class="form-control" required>
      </div>
      <div class="form-group">
        <input type="password" placeholder="Enter Password:" name="password" class="form-control" <?php echo (isset($_POST["login"]) && empty($_POST["password"])) ? 'required' : ''; ?>>  </div>
      <div class="form-btn">
        <input type="submit" value="Login" name="login" class="btn btn-primary">
      </div>
    </form>
  </div>
</body>
</html>
