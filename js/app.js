console.log("¡El archivo JS se ha cargado correctamente!");

let productos = [];
let carrito = [];

// Cargar productos al iniciar
document.addEventListener('DOMContentLoaded', () => {
    fetch('api/productos.php')
        .then(res => res.json())
        .then(data => {
            productos = data;
            renderProductos();
        });

    document.getElementById('cart-icon').onclick = () => document.getElementById('cart-modal').style.display = 'block';
    document.getElementById('btn-finalizar').onclick = prepararCheckout;
    document.getElementById('btn-solicitar').onclick = enviarPedido;
});

function renderProductos() {
    const contenedor = document.getElementById('lista-productos');
    contenedor.innerHTML = productos.map(p => `
        <div class="card">
            <img src="${p.imagen}" alt="${p.nombre}">
            <h3>${p.nombre}</h3>
            <p>${p.descripcion}</p>
            <p><strong>$${p.precio}</strong></p>
            <button class="btn-primary" onclick="agregarAlCarrito(${p.id})">Añadir al carrito</button>
        </div>
    `).join('');
}

function agregarAlCarrito(id) {
    const prod = productos.find(p => p.id == id);
    const enCarrito = carrito.find(item => item.id == id);

    if (enCarrito) {
        enCarrito.cantidad++;
    } else {
        carrito.push({...prod, cantidad: 1});
    }
    actualizarInterfazCarrito();
}

function actualizarInterfazCarrito() {
    document.getElementById('cart-count').innerText = carrito.reduce((acc, p) => acc + p.cantidad, 0);
    const listado = document.getElementById('items-carrito');
    listado.innerHTML = carrito.map(p => `<p>${p.nombre} x${p.cantidad} - $${(p.cantidad * p.precio).toFixed(2)}</p>`).join('');
    document.getElementById('total-carrito').innerText = calcularTotal();
}

function calcularTotal() {
    return carrito.reduce((acc, p) => acc + (p.cantidad * p.precio), 0).toFixed(2);
}

function prepararCheckout() {
    if (carrito.length === 0) return alert("El carrito está vacío.");
    
    closeModal();
    document.getElementById('catalogo').classList.add('hidden');
    document.getElementById('checkout').classList.remove('hidden');
    
    const fecha = new Date();
    document.getElementById('fecha-actual').innerText = fecha.toLocaleString();
    document.getElementById('total-checkout').innerText = calcularTotal();

    let html = `<table><tr><th>Producto</th><th>Cant.</th><th>Precio Unit.</th><th>Subtotal</th></tr>`;
    carrito.forEach(p => {
        html += `<tr><td>${p.nombre}</td><td>${p.cantidad}</td><td>$${p.precio}</td><td>$${(p.cantidad * p.precio).toFixed(2)}</td></tr>`;
    });
    html += `</table>`;
    document.getElementById('detalle-checkout').innerHTML = html;
}

function enviarPedido() {
    const data = {
        total: calcularTotal(),
        carrito: carrito
    };

    fetch('api/guardar_pedido.php', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            alert(res.mensaje); // "Pedido finalizado y creado con éxito!"
            carrito = [];
            location.reload();
        }
    });
}

function closeModal() { document.getElementById('cart-modal').style.display = 'none'; }
function toggleCheckout() {
    document.getElementById('catalogo').classList.remove('hidden');
    document.getElementById('checkout').classList.add('hidden');
}