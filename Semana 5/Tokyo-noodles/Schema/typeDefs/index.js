const productoTypeDefs = require('./producto');       // RENATO
const categoriaTypeDefs = require('./categoria');      // NICOLAS

// 👉 Si se agrega una nueva entidad (ej: pedido.js, cliente.js),
// impórtala aquí y agrégala al array de abajo.
// Ejemplo:
// const pedidoTypeDefs = require('./pedido');

const typeDefs = [
  productoTypeDefs,
  categoriaTypeDefs
  // pedidoTypeDefs,
];

module.exports = typeDefs;
