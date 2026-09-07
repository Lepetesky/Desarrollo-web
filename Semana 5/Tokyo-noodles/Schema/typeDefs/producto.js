const { gql } = require('apollo-server-express');

const productoTypeDefs = gql`
  type Producto {
    id: ID!
    nombre: String!
    descripcion: String
    precio: Float!
    categoria: String
    imagen: String
    destacado: Boolean
    disponible: Boolean
    createdAt: String
    updatedAt: String
  }

  input ProductoInput {
    nombre: String!
    descripcion: String
    precio: Float!
    categoria: String
    imagen: String
    destacado: Boolean
    disponible: Boolean
  }

  input ProductoUpdateInput {
    nombre: String
    descripcion: String
    precio: Float
    categoria: String
    imagen: String
    destacado: Boolean
    disponible: Boolean
  }

  type Query {
    productos: [Producto]
    producto(id: ID!): Producto
    productosDestacados: [Producto]
    productosPorCategoria(categoria: String!): [Producto]
  }

  type Mutation {
    crearProducto(input: ProductoInput!): Producto
    actualizarProducto(id: ID!, input: ProductoUpdateInput!): Producto
    eliminarProducto(id: ID!): Boolean
  }
`;

module.exports = productoTypeDefs;
