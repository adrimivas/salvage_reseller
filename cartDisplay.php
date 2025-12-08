
<?php
// cart.php — normal page
require_once __DIR__ . '/main.php';

?>

<main class="container">
  <h1>Your Cart</h1>
  <div id="cart-results"></div>
  <button onClick="checkOutItems()" class="check-out">CheckOut</button>
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
        if (item.product_type == 1 && item.quantity>0){
          const card = document.createElement('div');
          card.className = 'item-card';
          card.innerHTML = `
            <div class="item-info">
              <h3 class="item-title">${item.model} — ${item.item_name}</h3>
              <p class="item-details">
                <span class="item-price">$${item.price}</span>
              </p>
              <p class="item-details" >
                <span class="item-price">Qauntity: ${item.quantity}</span>
              </p>
              <button type="button" id ="${item.item_id}" class="add-to-cart-btn">Remove</button>
            </div>
          `;
          box.appendChild(card);;
        }
        else if (item.quantity>0){
          const card = document.createElement('div');
          card.className = 'item-card';
          card.innerHTML = `
            <div class="item-info">
              <h3 class="item-title">${item.model}</h3>
              <p class="item-details">
                <span class="item-price">$${item.price}</span>
              </p>
              <p class="item-details">
                <span class="item-price">Quantity: ${item.quantity}</span>
              </p>
              <button type="button" id ="${item.item_id}" class="add-to-cart-btn">Remove</button>
            </div>
          `;
          box.appendChild(card);;
        }});
        const add_to_cart_btns = document.getElementsByClassName('add-to-cart-btn');
        for (let btn of add_to_cart_btns){
        btn.addEventListener("click", handleButtonClick);
        }


})
    .catch(err => { box.textContent = 'Error loading cart.'; console.error(err); });
});


  function handleButtonClick(e){
    window.location.reload();
    const btn = e.currentTarget;           // the clicked button
    const id  = btn.id;                    // should be a number/string
    const fd  = new FormData();
    fd.append('item_id', id);
    fetch('removeFromCart.php', {
        method: 'POST',
        body: fd,
      })
    }

  function checkOutItems(){
    window.location.reload();
    const userId = localStorage.getItem('user_id'); // or however you store it
    fetch('checkOut.php', {
      method: 'POST',
    }).then(res => res.json())
  .then(data => {
    console.log(data);
    alert(data.message);
  })
  .catch(err => console.error(err));
    
  }
  
</script>


