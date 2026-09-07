const { gql } = require('apollo-server-express');

const categoriaTypeDefs = gql`
  type Categoria {
    id: ID!
    nombre: String!
    descripcion: String
    imagen: String
    orden: Int
    activa: Boolean
    createdAt: String
    updatedAt: String
  }

  input CategoriaInput {
    nombre: String!
    descripcion: String
    imagen: String
    orden: Int
    activa: Boolean
  }

  input CategoriaUpdateInput {
    nombre: String
    descripcion: String
    imagen: String
    orden: Int
    activa: Boolean
  }

  type Query {
    categorias: [Categoria]
    categoria(id: ID!): Categoria
    categoriasActivas: [Categoria]
  }

  type Mutation {
    crearCategoria(input: CategoriaInput!): Categoria
    actualizarCategoria(id: ID!, input: CategoriaUpdateInput!): Categoria
    eliminarCategoria(id: ID!): Boolean
  }
`;

module.exports = categoriaTypeDefs;
