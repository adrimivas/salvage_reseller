<?php
// navbar.php — reusable navbar fragment
// Expects $active = 'login' | 'register' | 'profile' | 'logout' (optional)

function navClass(string $key, ?string $active): string {
  // adds a class when the link is the current page
  return 'nav-bar-link' . ($active === $key ? ' is-active' : '');
}
?>

<nav class="nav-bar">
  <div class="nav-bar-links-container">
    <a class="<?= navClass('login', $active ?? null) ?>"    href="login.php">Login</a>
    <a class="<?= navClass('register', $active ?? null) ?>" href="/register.php">Register</a>
    <a class="<?= navClass('profile', $active ?? null) ?>"  href="profile.php">Profile</a>
    <a class="<?= navClass('Home', $active ?? null) ?>"   href="index.php">Home</a>
  </div>

  <div class="nav-bar-icons-container">
    <a href="cartDisplay.php" class="nav-icon" title="Cart">
      <lottie-player
        src="assets/icons/cart.json"
        background="transparent"
        speed="1"
        hover
        class="icon-lottie"></lottie-player>
    </a>
    <a href="#" class="nav-icon" title="Profile">
      <lottie-player
        src="assets/icons/profileIcon.json"
        background="transparent"
        speed="1"
        hover
        class="icon-lottie"></lottie-player>
    </a>
  </div>
</nav>
