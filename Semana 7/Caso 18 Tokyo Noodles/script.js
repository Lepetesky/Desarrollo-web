// Tokyo Noodles 2026: navegacion movil y carrito de compra demostrativo.
document.addEventListener('DOMContentLoaded', function () {
  // Primero controlamos el menu hamburguesa que se usa en pantallas pequeñas.
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.querySelector('.main-nav');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var abierto = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', abierto ? 'true' : 'false');
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth > 620) {
        nav.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // Luego hacemos que las imagenes destacadas cambien de forma automatica.
  var carousel = document.querySelector('.dish-carousel');
  if (carousel) {
    var slides = Array.from(carousel.querySelectorAll('.dish-slide'));
    var dotsContainer = carousel.querySelector('.carousel-dots');
    var currentSlide = 0;
    var carouselTimer;

    slides.forEach(function (_, index) {
      var dot = document.createElement('button');
      dot.type = 'button';
      dot.setAttribute('aria-label', 'Mostrar plato ' + (index + 1));
      dot.addEventListener('click', function () { showSlide(index); restartCarousel(); });
      dotsContainer.appendChild(dot);
    });

    function showSlide(index) {
      currentSlide = (index + slides.length) % slides.length;
      slides.forEach(function (slide, slideIndex) {
        slide.classList.toggle('active', slideIndex === currentSlide);
      });
      dotsContainer.querySelectorAll('button').forEach(function (dot, dotIndex) {
        dot.classList.toggle('active', dotIndex === currentSlide);
        dot.setAttribute('aria-current', dotIndex === currentSlide ? 'true' : 'false');
      });
    }

    function restartCarousel() {
      window.clearInterval(carouselTimer);
      carouselTimer = window.setInterval(function () { showSlide(currentSlide + 1); }, 4200);
    }

    carousel.querySelector('.carousel-prev').addEventListener('click', function () { showSlide(currentSlide - 1); restartCarousel(); });
    carousel.querySelector('.carousel-next').addEventListener('click', function () { showSlide(currentSlide + 1); restartCarousel(); });
    carousel.addEventListener('mouseenter', function () { window.clearInterval(carouselTimer); });
    carousel.addEventListener('mouseleave', restartCarousel);
    carousel.addEventListener('focusin', function () { window.clearInterval(carouselTimer); });
    carousel.addEventListener('focusout', restartCarousel);
    showSlide(0);
    restartCarousel();
  }

  // Desde aqui comienza la logica del carrito de la pagina de productos.
  // Si la pagina no tiene carrito, terminamos esta parte del script.
  var panel = document.querySelector('.cart-panel');
  if (!panel) return;

  var openButton = document.querySelector('.cart-open');
  var closeButton = document.querySelector('.cart-close');
  var backdrop = document.querySelector('.cart-backdrop');
  var itemsContainer = document.querySelector('.cart-items');
  var emptyMessage = document.querySelector('.cart-empty');
  var countElement = document.querySelector('.cart-count');
  var subtotalElement = document.querySelector('.cart-subtotal');
  var discountRow = document.querySelector('.discount-total');
  var discountElement = document.querySelector('.cart-discount');
  var totalElement = document.querySelector('.cart-total');
  var discountInput = document.querySelector('#discount-code');
  var discountButton = document.querySelector('.discount-apply');
  var discountMessage = document.querySelector('.discount-message');
  var checkoutButton = document.querySelector('.checkout-button');
  var checkoutSuccess = document.querySelector('.checkout-success');
  var accountMessage = document.querySelector('.cart-account-message');
  var cart = loadCart();
  var discountRate = 0;

  function loadCart() {
    try {
      return JSON.parse(localStorage.getItem('tokyoNoodlesCart')) || [];
    } catch (error) {
      return [];
    }
  }

  function saveCart() {
    try {
      localStorage.setItem('tokyoNoodlesCart', JSON.stringify(cart));
    } catch (error) {
      // El carrito sigue funcionando aunque el navegador bloquee el almacenamiento.
    }
  }

  // Revisamos si existe una cuenta creada desde registro.html.
  function loadUser() {
    try {
      return JSON.parse(localStorage.getItem('tokyoNoodlesUser'));
    } catch (error) {
      return null;
    }
  }

  function renderUserState() {
    var user = loadUser();
    accountMessage.textContent = '';
    if (user) {
      accountMessage.append('Pedido para ');
      var name = document.createElement('strong');
      name.textContent = user.name;
      var accountLink = document.createElement('a');
      accountLink.href = 'registro.html';
      accountLink.textContent = 'Ver cuenta';
      accountMessage.append(name, ' · ', accountLink);
    } else {
      accountMessage.append('Para comprar debes ');
      var registerLink = document.createElement('a');
      registerLink.href = 'registro.html?checkout=1';
      registerLink.textContent = 'crear una cuenta';
      accountMessage.append(registerLink, '.');
    }
  }

  function money(value) {
    return '$' + Math.round(value).toLocaleString('es-CL');
  }

  function openCart() {
    panel.classList.add('open');
    panel.setAttribute('aria-hidden', 'false');
    openButton.setAttribute('aria-expanded', 'true');
    backdrop.hidden = false;
    document.body.classList.add('cart-visible');
    closeButton.focus();
  }

  function closeCart() {
    panel.classList.remove('open');
    panel.setAttribute('aria-hidden', 'true');
    openButton.setAttribute('aria-expanded', 'false');
    backdrop.hidden = true;
    document.body.classList.remove('cart-visible');
    openButton.focus();
  }

  // Esta funcion vuelve a dibujar los productos y recalcula los totales.
  function renderCart() {
    itemsContainer.innerHTML = '';
    var itemCount = 0;
    var subtotal = 0;

    cart.forEach(function (item) {
      itemCount += item.quantity;
      subtotal += item.price * item.quantity;

      var row = document.createElement('div');
      row.className = 'cart-item';
      row.innerHTML =
        '<div class="cart-item-info"><strong>' + item.name + '</strong><span>' + money(item.price) + ' c/u</span></div>' +
        '<div class="quantity-control" aria-label="Cantidad de ' + item.name + '">' +
          '<button type="button" data-action="decrease" data-id="' + item.id + '" aria-label="Quitar uno">−</button>' +
          '<span>' + item.quantity + '</span>' +
          '<button type="button" data-action="increase" data-id="' + item.id + '" aria-label="Agregar uno">+</button>' +
        '</div>' +
        '<strong class="cart-item-total">' + money(item.price * item.quantity) + '</strong>' +
        '<button class="cart-remove" type="button" data-action="remove" data-id="' + item.id + '" aria-label="Eliminar ' + item.name + '">×</button>';
      itemsContainer.appendChild(row);
    });

    var discount = subtotal * discountRate;
    var total = subtotal - discount;
    countElement.textContent = itemCount;
    subtotalElement.textContent = money(subtotal);
    discountElement.textContent = '-' + money(discount);
    totalElement.textContent = money(total);
    discountRow.hidden = discountRate === 0 || subtotal === 0;
    emptyMessage.hidden = cart.length > 0;
    checkoutButton.disabled = cart.length === 0;
    saveCart();
  }

  // Conectamos todos los botones de productos sin repetir el mismo codigo.
  document.querySelectorAll('.add-cart').forEach(function (button) {
    button.addEventListener('click', function () {
      var id = button.dataset.id;
      var existing = cart.find(function (item) { return item.id === id; });
      if (existing) {
        existing.quantity += 1;
      } else {
        cart.push({
          id: id,
          name: button.dataset.name,
          price: Number(button.dataset.price),
          quantity: 1
        });
      }
      renderCart();
      button.textContent = 'Agregado ✓';
      window.setTimeout(function () { button.textContent = 'Agregar al carrito'; }, 900);
      openCart();
    });
  });

  itemsContainer.addEventListener('click', function (event) {
    var button = event.target.closest('button[data-action]');
    if (!button) return;
    var item = cart.find(function (entry) { return entry.id === button.dataset.id; });
    if (!item) return;

    if (button.dataset.action === 'increase') item.quantity += 1;
    if (button.dataset.action === 'decrease') item.quantity -= 1;
    if (button.dataset.action === 'remove' || item.quantity <= 0) {
      cart = cart.filter(function (entry) { return entry.id !== item.id; });
    }
    renderCart();
  });

  // Comprobamos el codigo de prueba y calculamos el descuento.
  discountButton.addEventListener('click', function () {
    var code = discountInput.value.trim().toUpperCase();
    if (code === 'TOKYO10') {
      discountRate = 0.10;
      discountMessage.textContent = 'Codigo aplicado: 10% de descuento.';
      discountMessage.className = 'discount-message success';
    } else {
      discountRate = 0;
      discountMessage.textContent = code ? 'El codigo ingresado no es valido.' : 'Escribe un codigo de descuento.';
      discountMessage.className = 'discount-message error';
    }
    renderCart();
  });

  checkoutButton.addEventListener('click', function () {
    if (!cart.length) return;
    var user = loadUser();

    // Si no hay registro, enviamos a la pagina de cuenta y conservamos el carrito.
    if (!user) {
      window.location.href = 'registro.html?checkout=1';
      return;
    }

    var payment = document.querySelector('input[name="payment"]:checked').value;
    checkoutSuccess.textContent = 'Pedido de ' + user.name + ' preparado para comprar con ' + payment + '. Esta demostracion no realiza cobros reales.';
  });

  openButton.addEventListener('click', openCart);
  closeButton.addEventListener('click', closeCart);
  backdrop.addEventListener('click', closeCart);
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && panel.classList.contains('open')) closeCart();
  });

  renderCart();
  renderUserState();

  // Despues del registro volvemos a abrir el carrito de forma automatica.
  if (localStorage.getItem('tokyoNoodlesOpenCart') === '1') {
    localStorage.removeItem('tokyoNoodlesOpenCart');
    openCart();
  }
});
