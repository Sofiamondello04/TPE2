API REST para el recurso de productos

Una API REST sencilla para manejar un CRUD de productos de Pototito

Catalogo de indumentaria  de bebes renderizada desde el servidor.

Importar la base de datos desde PHPMyAdmin database/db_pototito.php


Se detallan los endpoints de cada accion:

getProducts-> http://localhost/TPE-ENTREGA-2/api/products -> metodo GET
getProduct-> http://localhost/TPE-ENTREGA-2/api/products/:id -> metodo GET
addProduct-> http://localhost/TPE-ENTREGA-2/api/products -> metodo POST

ejemplo body en formato JSON:
{
    "nombre": "Babero bandana",
    "talle": "M",
    "precio": "680",
    "id_marca": "7",
    "id_m": "7",
    "nombre_marca": "Mimo & Co."
}


aaaaaa



deleteProduct-> http://localhost/TPE-ENTREGA-2/api/products:id -> metodo DELETE