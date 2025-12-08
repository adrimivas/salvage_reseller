<?php
require_once __DIR__ . '/auth.php';

$isLoggedIn = is_logged_in();
?>

<div class="homepage-welcome-message">
    <div><h1>Welcome to JunkWeb!</h1>
  <p>Your one-stop destination for all things junk.</p></div>
  <div>
    <img class="home-page-img" src="assets/images/image.png" alt="car">
  </div>
</div>
<div class="search-bars-container">
  <form id='make-search' method="POST">
    <!-- Replace these fields with ones that match your chosen table -->
    <label >Make:</label>
    <input type="make" name="make" id="make" ><br>
    

    <label>Car Or Part:</label>
    <select name="product_type" id='item_search' method ="POST">
      <option value="0">Car</option>
      <option value="1">Parts</option>
    </select>
    <button type = "submit" class="Submit-button"> submit</button>
  </form> 
</div>
  <div id="results"></div>
  

  <script>
    console.log("printint");
    // 1️⃣ grab the elements we care about
    const form = document.getElementById('make-search');
    const make = document.getElementById('make');
    const productTypeValue = document.getElementById('item_search');
    const resultsDiv = document.getElementById('results');

    // 2️⃣ intercept the form submit
    form.addEventListener('submit', function(event) {
      event.preventDefault(); // stops the normal full-page reload

      const term = make.value.trim();
      const prod_type = productTypeValue.value.trim();

      // optional: don't bother querying if it's too short
      // if (term.length < 2) {
      //   resultsDiv.textContent = 'Please enter at least 2 characters.';
      //   return;
      // }

      // 3️⃣ make the request
      const formData = new FormData();
      formData.append('make', term);
      formData.append('product_type', prod_type);

      fetch('fetch.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())      // 4️⃣ parse the JSON from PHP
        .then(data => {
          // 5️⃣ render results
          renderResults(data);
        })
        .catch(error => {
          resultsDiv.textContent = 'Error: ' + error;
        });
    });

    // helper function to build the results list
    function renderResults(cars) {
      resultsDiv.innerHTML = ''; // clear old results

      if (!cars || cars.length === 0) {
        resultsDiv.textContent = 'No results found.';
        return;
      }

      cars.forEach(car => {
        if (car.product_type==='1'){
          const card = document.createElement('div');
          card.className = 'item-card';
          card.innerHTML = `
            <div class="item-info">
              <h3 class="item-title">${car.model} — ${car.item_name}</h3>
              <p class="item-details">
                <span class="item-condition">Condition: ${car.item_condition}</span> ·
                <span class="item-condition">Qantity: ${car.quantity}</span> · 
                <span class="item-price"> Price : $${car.price}</span>
              </p>
             ${car.quantity > 0 ? `<button type="button" id="${car.item_id}" class="add-to-cart-btn">Add to Cart</button>` : 'Out Of Stock'}
            </div>
          `;
          document.getElementById('results').appendChild(card);
        }
        else{
          const card = document.createElement('div');
          card.className = 'item-card';
          card.innerHTML = `
            <div class="item-info">
              <h3 class="item-title">${car.model}</h3>
              <p class="item-details">
                <span class="item-condition">Condition: ${car.item_condition}</span> ·
                <span class="item-condition">Qantity: ${car.quantity}</span> · 
                <span class="item-price"> Price: $${car.price}</span>
              </p>
              ${car.quantity > 0 ? `<button type="button" id="${car.item_id}" class="add-to-cart-btn">Add to Cart</button>` : 'Out Of Stock'}
            </div>
          `;
          document.getElementById('results').appendChild(card);
        }
      });
    
    //ADD  TO CART LISTNER

    const add_to_cart_btns = document.getElementsByClassName('add-to-cart-btn');
    for (let btn of add_to_cart_btns){
      btn.addEventListener("click", handleButtonClick);
    }
    }
  const IS_LOGGED_IN = <?= $isLoggedIn ? 'true' : 'false' ?>;  
  function handleButtonClick(e){
    e.preventDefault();
    if (!IS_LOGGED_IN){
      alert("Please log in to add items to your cart.");
      return;
    }
    const btn = e.currentTarget;           // the clicked button
    const id  = btn.id;                    // should be a number/string
    const fd  = new FormData();
    fd.append('item_id', id);
    fetch('addToCart.php', {
        method: 'POST',
        body: fd,
      }).then(r => r.json())
    .then(data => console.log('add->', data))
    .catch(err => console.error(err));
    }


</script>
