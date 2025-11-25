<?php 
require_once __DIR__ . '/loginLogic.php';
require_once __DIR__ . '/head.php'; // opens <html><head> etc.


$include_auth_css = false; // tell head.php to add auth.css
$page_title = 'Login • JUNKIES';
?>

<main class="login-form">
  <div class="form">
    <h1 class="form-heading">Login</h1>

    <?php if ($login_success): ?>
      <div class="success">✅ Login successful (using hashed password).</div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="errors">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" >

      <label class="field">
        <span class="label">Email</span>
        <input type="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>" class="input">
      </label>

      <label class="field">
        <span class="label">Password</span>
        <input type="password" name="password" required class="input">
      </label>

      <button class="submit-button" type="submit">Sign in</button>
      <a href="employeeLogin.php" class="submit-button">Employee? Click Here</a>

    </form>
  </div>
        </main>