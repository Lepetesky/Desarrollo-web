const Categoria = require('../../models/Categoria');

const categoriaResolvers = {
  Query: {
    categorias: async () => {
      return await Categoria.find().sort({ orden: 1 });
    },
    categoria: async (_, { id }) => {
      return await Categoria.findById(id);
    },
    categoriasActivas: async () => {
      return await Categoria.find({ activa: true }).sort({ orden: 1 });
    }
  },

  Mutation: {
    crearCategoria: async (_, { input }) => {
      const nuevaCategoria = new Categoria(input);
      return await nuevaCategoria.save();
    },
    actualizarCategoria: async (_, { id, input }) => {
      return await Categoria.findByIdAndUpdate(id, input, {
        new: true,
        runValidators: true
      });
    },
    eliminarCategoria: async (_, { id }) => {
      const resultado = await Categoria.findByIdAndDelete(id);
      return resultado !== null;
    }
  }
};

module.exports = categoriaResolvers;
