const productoResolvers = require('./producto');       // RENATO
const categoriaResolvers = require('./categoria');      // NICOLAS

// 👉 Si se agrega una nueva entidad, importa sus resolvers aquí
// y combínalos abajo (cuidado con no repetir nombres de Query/Mutation).
// Ejemplo:
// const pedidoResolvers = require('./pedido');

const resolvers = {
  Query: {
    ...productoResolvers.Query,
    ...categoriaResolvers.Query
    // ...pedidoResolvers.Query,
  },
  Mutation: {
    ...productoResolvers.Mutation,
    ...categoriaResolvers.Mutation
    // ...pedidoResolvers.Mutation,
  }
};

module.exports = resolvers;
