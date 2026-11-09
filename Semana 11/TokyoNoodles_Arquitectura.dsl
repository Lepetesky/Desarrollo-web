workspace "Tokyo Noodles" "Arquitectura del sistema web" {

    model {
        cliente = person "Cliente del Restaurante" "Usuario que revisa el menú e interactúa con el sistema."
        admin = person "Administrador" "Personal del restaurante que gestiona el menú."
        
        tokyoNoodles = softwareSystem "Tokyo Noodles" "Plataforma web del restaurante." {
            
            frontend = container "Frontend Web" "Interfaz de usuario." "HTML/CSS/JS" "WebBrowser"
            
            db = container "Base de Datos" "Almacena los datos de productos y categorías." "MongoDB Atlas" "Database"
            
            backend = container "Backend API" "Lógica de negocio y acceso a datos." "Node.js" {
                # TODO: Persona 2 debe agregar aquí los componentes (GraphQL y Resolvers)
            }
            
            # Relaciones de contenedores y usuarios
            cliente -> frontend "Revisa el menú"
            admin -> frontend "Gestiona el menú"
            frontend -> backend "Hace peticiones (HTTPS)"
        }
    }

    views {
        systemContext tokyoNoodles "Contexto" "Diagrama de Contexto" {
            include *
            autoLayout
        }
        
        container tokyoNoodles "Contenedores" "Diagrama de Contenedores" {
            include *
            autoLayout
        }
        
        styles {
            element "Person" {
                background #08427b
                color #ffffff
                shape Person
            }
            element "Software System" {
                background #1168bd
                color #ffffff
            }
            element "Container" {
                background #438dd5
                color #ffffff
            }
            element "Database" {
                shape Cylinder
            }
            element "WebBrowser" {
                shape WebBrowser
            }
        }
    }
}