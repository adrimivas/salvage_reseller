
<?php
// cart.php — normal page
require_once __DIR__ . '/main.php';

?>

<main class="container">
  <h1>Your Cart</h1>
  <div id="cart-results"></div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const box = document.getElementById('cart-results');

  fetch('getCart.php', { credentials: 'same-origin' })
    .then(r => r.json())
    .then(({ok, data, error}) => {
      if (!ok) { box.textContent = error || 'Failed to load cart.'; return; }
      if (!data || data.length === 0) { box.textContent = 'Your cart is empty.'; return; }

      box.innerHTML = '';
      data.forEach(item => {
        if (item.product_type==='1'){
          const card = document.createElement('div');
          card.className = 'item-card';
          card.innerHTML = `
            <div class="item-info">
              <h3 class="item-title">${item.model} — ${item.item_name}</h3>
              <p class="item-details">
                <span class="item-price">$${item.price}</span>
              </p>
              <button type="button" id ="${item.item_id}" class="add-to-cart-btn">Remove</button>
            </div>
          `;
          box.appendChild(card);;
        }
        else{
          const card = document.createElement('div');
          card.className = 'item-card';
          card.innerHTML = `
            <div class="item-info">
              <h3 class="item-title">${item.model}</h3>
              <p class="item-details">
                <span class="item-price">$${item.price}</span>
              </p>
              <button type="button" id ="${item.item_id}" class="add-to-cart-btn">Remove</button>
            </div>
          `;
          box.appendChild(card);;
        }});
})
    .catch(err => { box.textContent = 'Error loading cart.'; console.error(err); });
});
</script>


