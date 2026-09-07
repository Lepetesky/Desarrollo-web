const Producto = require('../../Models/Producto');

const productoResolvers = {
  Query: {
    productos: async () => {
      return await Producto.find().sort({ createdAt: -1 });
    },
    producto: async (_, { id }) => {
      return await Producto.findById(id);
    },
    productosDestacados: async () => {
      return await Producto.find({ destacado: true, disponible: true });
    },
    productosPorCategoria: async (_, { categoria }) => {
      return await Producto.find({ categoria });
    }
  },

  Mutation: {
    crearProducto: async (_, { input }) => {
      const nuevoProducto = new Producto(input);
      return await nuevoProducto.save();
    },
    actualizarProducto: async (_, { id, input }) => {
      return await Producto.findByIdAndUpdate(id, input, {
        new: true,
        runValidators: true
      });
    },
    eliminarProducto: async (_, { id }) => {
      const resultado = await Producto.findByIdAndDelete(id);
      return resultado !== null;
    }
  }
};

module.exports = productoResolvers;
