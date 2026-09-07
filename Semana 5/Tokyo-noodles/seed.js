require('dotenv').config();
const conectarDB = require('./Config/db');
const Producto = require('./models/Producto');
const Categoria = require('./models/Categoria');

const categoriasIniciales = [
  { nombre: 'Ramen', descripcion: 'Sopas de fideos japonesas', orden: 1, activa: true },
  { nombre: 'Gyoza', descripcion: 'Empanaditas japonesas', orden: 2, activa: true }
];

const productosIniciales = [
  {
    nombre: 'Ramen Único',
    descripcion: 'Nuestro ramen insignia con huevo, alga nori y fideos hechos a mano.',
    precio: 8900,
    categoria: 'Ramen',
    imagen: '',
    destacado: true,
    disponible: true
  },
  {
    nombre: 'Gyozas Delicioso',
    descripcion: 'Empanaditas japonesas rellenas, doradas por fuera y jugosas por dentro.',
    precio: 5500,
    categoria: 'Gyoza',
    imagen: '',
    destacado: true,
    disponible: true
  }
];

async function poblarBaseDeDatos() {
  await conectarDB();

  await Categoria.deleteMany({});
  await Producto.deleteMany({});
  console.log(' Colecciones limpiadas.');

  const categoriasCreadas = await Categoria.insertMany(categoriasIniciales);
  console.log(` ${categoriasCreadas.length} categorías insertadas:`);
  categoriasCreadas.forEach((c) => console.log(`   - ${c.nombre}`));

  const creados = await Producto.insertMany(productosIniciales);
  console.log(` ${creados.length} productos insertados:`);
  creados.forEach((p) => console.log(`   - ${p.nombre} ($${p.precio})`));

  process.exit(0);
}

poblarBaseDeDatos().catch((err) => {
  console.error(' Error al poblar la base de datos:', err);
  process.exit(1);
});
