// Registro de usuario para el prototipo de Tokyo Noodles.
// Como todavia no usamos una base de datos, guardamos los datos en localStorage.
document.addEventListener('DOMContentLoaded', function () {
  var form = document.querySelector('#register-form');
  if (!form) return;

  var savedPanel = document.querySelector('.account-saved');
  var message = document.querySelector('.register-message');
  var deleteButton = document.querySelector('.account-delete');
  var checkoutReturn = new URLSearchParams(window.location.search).get('checkout') === '1';

  function readUser() {
    try {
      return JSON.parse(localStorage.getItem('tokyoNoodlesUser'));
    } catch (error) {
      return null;
    }
  }

  // Mostramos el formulario o el resumen dependiendo de si ya existe una cuenta.
  function updateAccountView() {
    var user = readUser();
    form.hidden = Boolean(user);
    savedPanel.hidden = !user;

    if (user) {
      document.querySelector('.saved-user-name').textContent = user.name;
      document.querySelector('.saved-user-email').textContent = user.email;
    }
  }

  form.addEventListener('submit', function (event) {
    event.preventDefault();

    var data = new FormData(form);
    var password = String(data.get('password'));
    var confirmation = String(data.get('passwordConfirm'));

    if (password !== confirmation) {
      message.textContent = 'Las contraseñas no coinciden.';
      message.className = 'register-message error';
      return;
    }

    // Por ser un ejercicio frontend no guardamos la contraseña.
    // En un proyecto real este proceso se haria en un servidor de forma segura.
    var user = {
      name: String(data.get('name')).trim(),
      email: String(data.get('email')).trim(),
      phone: String(data.get('phone')).trim(),
      address: String(data.get('address')).trim()
    };

    try {
      localStorage.setItem('tokyoNoodlesUser', JSON.stringify(user));
    } catch (error) {
      message.textContent = 'No fue posible guardar el registro en este navegador.';
      message.className = 'register-message error';
      return;
    }

    message.textContent = 'Registro completado correctamente.';
    message.className = 'register-message success';
    updateAccountView();

    // Si el usuario venia desde el carrito, regresamos y lo abrimos de nuevo.
    if (checkoutReturn) {
      localStorage.setItem('tokyoNoodlesOpenCart', '1');
      window.setTimeout(function () {
        window.location.href = 'productos.html';
      }, 700);
    }
  });

  deleteButton.addEventListener('click', function () {
    localStorage.removeItem('tokyoNoodlesUser');
    form.reset();
    message.textContent = '';
    updateAccountView();
  });

  updateAccountView();
});

