const mongoose = require('mongoose');

const conectarDB = async () => {
  try {
    const uri = process.env.MONGO_URI || 'mongodb://localhost:27017/tokyo_noodles';
    await mongoose.connect(uri);
    console.log('✅ Conectado a MongoDB:', uri);
  } catch (error) {
    console.error('❌ Error al conectar a MongoDB:', error.message);
    process.exit(1);
  }
};

module.exports = conectarDB;
