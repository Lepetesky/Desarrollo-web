
// El carrito completo vive en este arreglo. Cada elemento es un objeto
// { nombre, cantidad, valor, subtotal } — parecido a como el profe armaba
// objetos nuevos combinando datos con el operador spread.
let carrito = [];


document.addEventListener('DOMContentLoaded', () => {

    let tarjetas = document.querySelectorAll('.pv-card');

    tarjetas.forEach((tarjeta) => {
        actualizarTextoStock(tarjeta);
    });
});

function actualizarTextoStock(tarjeta) {
    let stockDisponible = parseInt(tarjeta.dataset.stock);
    let textoStock = tarjeta.querySelector('.pv-stock-numero');
    let inputCantidad = tarjeta.querySelector('.pv-cantidad-input');
    let boton = tarjeta.querySelector('.btn-pv');

    if (textoStock) {
        textoStock.innerText = stockDisponible;
    }
    if (inputCantidad) {
        inputCantidad.setAttribute('max', stockDisponible);
    }

    if (stockDisponible <= 0 && boton) {
        boton.disabled = true;
        boton.innerText = 'Sin stock';
    }
}


function agregarDesdeTarjeta(boton) {
    try {
        let tarjeta = boton.closest('.pv-card');
        let nombreProducto = tarjeta.querySelector('h3').innerText;
        let inputCantidad = tarjeta.querySelector('.pv-cantidad-input');

        let cantidad = parseInt(inputCantidad.value);
        let stockDisponible = parseInt(tarjeta.dataset.stock);
        let valorUnitario = parseInt(tarjeta.dataset.valor);


        if (typeof cantidad !== 'number' || isNaN(cantidad) || cantidad < 1) {
            alert('Ingresa una cantidad válida (mínimo 1).');
            return;
        }


        if (cantidad > stockDisponible) {
            alert('Solo quedan ' + stockDisponible + ' unidades de ' + nombreProducto + '.');
            return;
        }


        let subtotal = cantidad * valorUnitario;
        let item = { nombre: nombreProducto, cantidad: cantidad, valor: valorUnitario, subtotal: subtotal };

        carrito.push(item);

  
        let nuevoStock = stockDisponible - cantidad;
        tarjeta.dataset.stock = nuevoStock;
        actualizarTextoStock(tarjeta);

        inputCantidad.value = 1;

        pintarCarrito();

    } catch (error) {

        console.log('Error al agregar producto: ' + error);
        alert('Ocurrió un problema al agregar el producto. Revisa la consola.');
    }
}


function pintarCarrito() {
    let lista = document.getElementById('listaCarrito');
    let totalTexto = document.getElementById('totalCarrito');

    lista.innerHTML = '';

    carrito.forEach((item) => {
        let li = document.createElement('li');
        li.innerText = item.cantidad + ' x ' + item.nombre + ' — $' + item.subtotal.toLocaleString('es-CL');
        lista.appendChild(li);
    });


    let total = carrito.reduce((acumulado, item) => acumulado + item.subtotal, 0);
    totalTexto.innerText = '$' + total.toLocaleString('es-CL');
}