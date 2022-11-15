API REST que permite manejar un CRUD de productos de indumentaria para bebes de Pototitos

Importar la base de datos desde PHPMyAdmin database/db_pototito.php

Se detallan los endpoints de cada servicio:

1- Obtener todos los productos:

URL : http://localhost/TPE2/api/products

Descripcion: Este servicio permite listar todos los productos.
Se pueden listar sin parametros, lo que devolverá los primeros 50 registros en la primer pagina, o con los siguientes parametros:
orderBy: permite ordenar los productos por el campo especificado.
orderMode: permite ordenar los registros definidos en orderBy de manera ascendente o ascedente. Para ellos indicar "asc" o "desc"

Request: Metodo GET. Copiar el endpoint en postman y clickear "send".

Response: (ejemplo)
{
    "id": "42",
    "nombre": "Piluso",
    "talle": "S",
    "precio": "730",
    "id_marca": "21",
    "nombre_marca": "Blonde"
}

Mensajes y Codigos de respuesta:
200: JSON con los datos de la request
204: "La consulta realizada no arrojó resultados."
400: "Parámetro de ordenado/ paginado/ filtrado no válido."
500: "No se pudo realizar la consulta especificada."

----------------------------------------------------------------------------------------------------------------------------------
2- Obtener un producto dado su id:

URL : http://localhost/TPE2/api/products/:ID

Descripcion: Este servicio permite listar un producto, dado su id.

Request: Metodo GET. Copiar el endpoint en con el numero de id deseado en postman y clickear "send".

Response: (ejemplo)
{
    "id": "42",
    "nombre": "Piluso",
    "talle": "S",
    "precio": "730",
    "id_marca": "21",
    "nombre_marca": "Blonde"
}

Mensajes y Codigos de respuesta:
200: JSON con los datos de la request
404: "El producto con el id=$id no existe".

----------------------------------------------------------------------------------------------------------------------------------
3- Agregar un producto:

URL : http://localhost/TPE2/api/products

Descripcion: Este servicio permite agregar un producto deseado a la base de datos de Pototitos.

Request: Metodo POST. Copiar el endpoint en postman y escribir en el body el JSON a enviar. Luego clickear "send".

Body de la request: (ejemplo)
{
    "nombre": "Piluso api ",
    "talle": "M",
    "precio": "890",
    "id_marca": "7"
}

Response: (ejemplo)
{   "id" : "numero asignad de id",
    "nombre": "Piluso api ",
    "talle": "M",
    "precio": "890",
    "id_marca": "7",
     "id_m": "7"
    "nombre_marca": "Mimo & Co."
}

Mensajes y Codigos de respuesta:
201: JSON completo
400: "Complete todos los datos"



