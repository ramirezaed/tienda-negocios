# API - Tienda de Negocios

## Descripción

Este proyecto consiste en el desarrollo de una API REST para una tienda de negocios, utilizando Laravel y PHP.

La aplicación esta desarrollada siguiendo una arquitectura MVC, que permite separar las responsabilidades de la aplicación y facilita su mantenimiento y escalabilidad.

Actualmente, el proyecto se encuentra en desarrollo.

## Tecnologías utilizadas

- **PHP 8.4**
- **Laravel**
- **MySQL**
- **Apache**
- **XAMPP**
- **Herd**
- **phpMyAdmin**

# Requisitos

- XAMPP
- Herd
- PHP 8.4
- Composer
- Laravel

# Instalación y configuración

## 1. Instalar XAMPP

Descargar e instalar **XAMPP**.

Una vez instalado, abrir el panel de control de XAMPP y ejecutar los siguientes servicios:

- **Apache**
- **MySQL**

## Ambos servicios deben estar activos para poder ejecutar correctamente la aplicación y conectarse a la base de datos.

## 2. Instalar Herd

Se utiliza **Laravel Herd** para disponer de la versión de PHP necesaria para el proyecto.

Instalar Herd y verificar que PHP esté utilizando la versión **8.4**.

Para comprobar la versión de PHP:

php -v

## 3. Clonar el Repo

git clone "direccion del repo"

ejecutar
composer install

# Configuración del archivo `.env`

Laravel utiliza el archivo `.env` para almacenar configuraciones específicas del entorno, principalmente las relacionadas con la base de datos.

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tienda_negocios
DB_USERNAME=root
DB_PASSWORD=

````

Los valores de `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD` deben coincidir con la configuración de MySQL local.

---

# Crear la base de datos

Con XAMPP ejecutando MySQL, ingresar a:

```text
http://localhost/phpmyadmin
````

Dentro de **phpMyAdmin**:

1. Seleccionar **Nueva**.
2. Crear una nueva base de datos.
3. Utilizar el mismo nombre configurado en `DB_DATABASE`.

# Ejecutar las migraciones

Laravel utiliza migraciones para crear y modificar las tablas de la base de datos.

php artisan migrate

Si el proyecto cuenta con seeders y se desea cargar información inicial:

php artisan db:seed

También se puede ejecutar:

```bash
php artisan migrate --seed
```

---

# Ejecutar el proyecto

php artisan serve

Por defecto, la aplicación estará disponible en:

http://127.0.0.1:8000

# Arquitectura MVC

El proyecto utiliza el patrón **MVC (Model-View-Controller)**.

MVC divide la aplicación en tres componentes principales:

### Model

El **Modelo** representa los datos de la aplicación y se encarga de interactuar con la base de datos.

En Laravel, los modelos normalmente utilizan **Eloquent ORM** para consultar, crear, modificar y eliminar información.

### Controller

El **Controlador** recibe la petición y contiene la lógica necesaria para procesarla.

Su responsabilidad es coordinar las acciones necesarias, por ejemplo:

1. Recibir los datos enviados por el cliente.
2. Validar la información.
3. Utilizar un modelo para consultar o modificar la base de datos.
4. Generar y devolver una respuesta.

### View

La **Vista** representa la información que se devuelve al usuario.

En una aplicación Laravel tradicional puede utilizarse **Blade** para generar HTML.

Sin embargo, en este proyecto se está desarrollando una **API**, por lo que las respuestas normalmente se devuelven en formato **JSON** en lugar de una vista HTML.

---

# Relación entre Modelo y Controlador

La relación entre ambos componentes puede explicarse de manera sencilla:

> **El controlador recibe la petición y utiliza el modelo para acceder a los datos.**

# Flujo de información en Laravel

El flujo general de una petición HTTP en Laravel puede representarse de la siguiente manera:

```text
Cliente
   │
   │ HTTP Request
   ▼
Ruta (Route)
   │
   ▼
Controlador (Controller)
   │
   ▼
Modelo (Model)
   │
   ▼
Base de datos
   │
   ▼
Modelo
   │
   ▼
Controlador
   │
   ▼
Vista / Respuesta JSON
   │
   ▼
Cliente
```

## 1. Petición HTTP

El cliente realiza una petición al servidor.

## 2. Ruta

Laravel recibe la petición y busca una ruta que coincida con el método HTTP y la URL.

## 3. Controlador

El controlador recibe la petición y ejecuta la lógica correspondiente.

## 4. Modelo

El modelo utiliza Eloquent para comunicarse con la base de datos.

## 5. Vista o respuesta

En una aplicación MVC tradicional, el controlador puede enviar los datos a una **Vista Blade**:

# Resumen

El proyecto consiste en una **API para una tienda de negocios desarrollada con Laravel**, utilizando PHP 8.4, MySQL y una arquitectura MVC.

El flujo principal de Laravel puede resumirse como:

```text
Petición HTTP
      ↓
    Ruta
      ↓
 Controlador
      ↓
    Modelo
      ↓
  Base de datos
      ↓
    Modelo
      ↓
 Controlador
      ↓
 Respuesta JSON
      ↓
    Cliente
```

La separación de responsabilidades permite que los **modelos se encarguen de los datos**, los **controladores gestionen las peticiones y la lógica de la aplicación**, y las **vistas o respuestas JSON presenten la información al cliente**.

## Endpoints de la API

La API está organizada en seis grupos principales de rutas, todas bajo el prefijo /api/V1. A continuación se detallan los endpoints disponibles:

## Usuarios (/api/V1/users)

### Método Endpoint Controlador Función Descripción

GET /api/V1/users UserController@index Listar usuarios Obtiene todos los usuarios registrados

POST /api/V1/users UserController@store Registrar usuario Crea un nuevo usuario en el sistema

GET /api/V1/users/{id} UserController@show Ver usuario Obtiene los datos de un usuario específico

PUT /api/V1/users/{id} UserController@update Actualizar usuario Modifica los datos de un usuario existente

DELETE /api/V1/users/{id} UserController@destroy Eliminar usuario Elimina un usuario del sistema

## Categorías (/api/V1/categories)

### Método Endpoint Controlador Función Descripción

GET /api/V1/categories CategoryController@index Listar categorías Obtiene todas las categorías disponibles

POST /api/V1/categories CategoryController@store Crear categoría Registra una nueva categoría

GET /api/V1/categories/{id} CategoryController@show Ver categoría Obtiene los datos de una categoría específica

PUT /api/V1/categories/{id} CategoryController@update Actualizar categoría Modifica los datos de una categoría existente

DELETE /api/V1/categories/{id} CategoryController@destroy Eliminar categoría Elimina una categoría del sistema

## Productos (/api/V1/products)

### Método Endpoint Controlador Función Descripción

GET /api/V1/products ProductController@index Listar productos Obtiene todos los productos disponibles

POST /api/V1/products ProductController@store Crear producto Registra un nuevo producto

GET /api/V1/products/{id} ProductController@show Ver producto Obtiene los datos de un producto específico

PUT /api/V1/products/{id} ProductController@update Actualizar producto Modifica los datos de un producto existente

DELETE /api/V1/products/{id} ProductController@destroy Eliminar producto Elimina un producto del sistema

## Carrito (/api/V1/cart)

### Método Endpoint Controlador Función Descripción

GET /api/V1/cart CartController@index Ver carrito Muestra el contenido actual del carrito

POST /api/V1/cart/add CartController@addProduct Agregar producto Añade un producto al carrito

POST /api/V1/cart/remove CartController@removeProduct Quitar producto Elimina un producto específico del carrito

POST /api/V1/cart/clear CartController@clear Vaciar carrito Elimina todos los productos del carrito

DELETE /api/V1/cart CartController@destroy Eliminar carrito Elimina completamente el carrito

## Resumen (/api/V1/summary)

### Método Endpoint Controlador Función Descripción

GET /api/V1/summary CheckoutController@summary Obtener resumen Obtiene el resumen del carrito, incluyendo subtotal, impuestos, costo de envío y total

## Checkout (/api/V1/checkout)

### Método Endpoint Controlador Función Descripción

POST /api/V1/checkout CheckoutController@checkout Procesar checkout Procesa la compra y genera la orden correspondiente

### Método Endpoint Controlador Función Descripción

POST /api/V1/login AuthController@login Iniciar sesión Autentica al usuario y devuelve un token JWT

POST /api/V1/register AuthController@register Registrar usuario Registra un nuevo usuario en el sistema

GET /api/V1/profile AuthController@profile Ver perfil Obtiene los datos del usuario autenticado mediante JWT

# Documentación de Pruebas Unitarias - ResumenCarritoTest

## Descripción General

Este conjunto de pruebas unitarias está diseñado para validar el correcto funcionamiento del módulo de carrito de compras en la aplicación. Las pruebas cubren las funcionalidades principales del carrito, incluyendo:

Cálculo de resumen de compra con envío

Agregar y eliminar productos

Manejo de inventario (stock)

Limpieza y eliminación del carrito

## Estructura de las Pruebas

### Configuración Inicial

Las pruebas utilizan la trait RefreshDatabase para asegurar que la base de datos se reinicie en cada prueba, manteniendo un estado limpio y consistente.

### Datos de Prueba

La función DatosSimulados() prepara el entorno de prueba creando:

Un usuario de prueba (ID: 1)

Una categoría de prueba

Dos productos con precios y stock específicos:

Producto 1: $1,500.00 (stock: 10)

Producto 2: $2,500.00 (stock: 5)
Casos de Prueba

1. Cálculo de Carrito con Envío (test_calcular_carrito_con_envio)
   Objetivo: Verificar que el cálculo del resumen del carrito sea correcto incluyendo impuestos y costo de envío.

Escenario:

Se agrega un producto con precio de $20,000 (cantidad: 1)

Se calcula el subtotal, impuestos (21%), costo de envío ($5,000) y total

Resultado Esperado:
[
"subtotal" => 20000,
"tax" => 4200,
"shipping_cost" => 5000,
"total" => 29200
]

2. Agregar Producto con Cantidad Igual al Stock (test_agregar_producto_con_cantidad_igual_al_stock)
   Objetivo: Validar que se pueda agregar un producto al carrito cuando la cantidad solicitada es igual al stock disponible.

Escenario:

Se agrega el Producto 1 (stock: 10) con cantidad 10

Se verifica la cantidad, subtotal y actualización del stock

Validaciones:

Cantidad en carrito: 10
Subtotal: $15,000.00
Stock actualizado: 0
Total del carrito: $15,000.00

Agregar Producto con Cantidad Mayor al Stock (test_agregar_producto_con_cantidad_mayor_al_stock)
Objetivo: Asegurar que el sistema rechace solicitudes que excedan el stock disponible.

Escenario:

Se intenta agregar el Producto 1 (stock: 10) con cantidad 11

El sistema debe lanzar una excepción

Validación:

Se lanza InsufficientStockException

4. Quitar Producto del Carrito (test_quitar_producto_del_carrito)
   Objetivo: Verificar que al eliminar un producto del carrito, se actualice correctamente el stock y los totales.

Escenario:

Se agregan 5 unidades del Producto 1

Se eliminan las 5 unidades

Validaciones:

Carrito queda vacío

Stock restaurado a 10

Total del carrito: 0 5. Eliminar Carrito (test_eliminar_carrito_vacia_los_items_y_restaura_el_stock)
Objetivo: Comprobar que al eliminar completamente el carrito, se restaure el stock de todos los productos.

Escenario:

Se agregan 4 unidades del Producto 1 (stock: 10 → 6)

Se agregan 2 unidades del Producto 2 (stock: 5 → 3)

Se elimina el carrito

Validaciones:

El carrito no existe en la base de datos
Stock Producto 1: 10
Stock Producto 2: 5

6. Limpiar Carrito (test_limpiar_carrito_restaurar_stock_items)
   Objetivo: Verificar que al limpiar el carrito (vaciar pero mantener la instancia), se restaure el stock.

Escenario:

Se agregan 4 unidades del Producto 1

Se agregan 2 unidades del Producto 2

Se limpia el carrito

Validaciones:

El carrito existe pero está vacío

Total del carrito: 0

Stock restaurado a valores originales

Cómo Ejecutar las Pruebas
Prerrequisitos
PHP instalado

Composer instalado

Base de datos configurada

Framework Laravel instalado

# Ejecutar Todas las Pruebas

php artisan test --filter=ResumenCarritoTest

Ejecutar Pruebas Específicas

# Ejecutar solo este archivo de pruebas

php artisan test --filter ResumenCarritoTest

# Ejecutar una prueba específica

php artisan test --filter test_calcular_carrito_con_envio

# Ejecutar con cobertura de código (si está configurado)

php artisan test --coverage
