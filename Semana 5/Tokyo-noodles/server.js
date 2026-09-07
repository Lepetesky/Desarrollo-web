require('dotenv').config();
const express = require('express');
const cors = require('cors');
const { ApolloServer } = require('apollo-server-express');

const conectarDB = require('./Config/db');
const typeDefs = require('./Schema/typeDefs');
const resolvers = require('./Schema/resolvers');

async function iniciarServidor() {
  const app = express();
  app.use(cors());

  // Conexión a la base de datos
  await conectarDB();

  // Servidor Apollo (GraphQL)
  const server = new ApolloServer({
    typeDefs,
    resolvers
  });

  await server.start();
  server.applyMiddleware({ app, path: '/graphql' });

  const PORT = process.env.PORT || 4000;
  app.listen(PORT, () => {
    console.log(`🚀 Tokyo Noodles API lista en http://localhost:${PORT}${server.graphqlPath}`);
  });
}

iniciarServidor();
