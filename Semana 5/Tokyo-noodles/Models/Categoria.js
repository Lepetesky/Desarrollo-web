const mongoose = require('mongoose');

const categoriaSchema = new mongoose.Schema(
  {
    nombre: {
      type: String,
      required: [true, 'El nombre de la categoría es obligatorio'],
      trim: true,
      unique: true
    },
    descripcion: {
      type: String,
      trim: true
    },
    imagen: {
      type: String,
      default: ''
    },
    orden: {
      type: Number,
      default: 0
    },
    activa: {
      type: Boolean,
      default: true
    }
  },
  { timestamps: true }
);

module.exports = mongoose.model('Categoria', categoriaSchema);
