<?php
require_once './app/models/product.model.php'; 
require_once './app/views/api-view.php';



class ApiProductController {
    private $modelProducts; 
    private $view;
    private $data;
  
    public function __construct() {
        $this->modelProducts = new ProductModel();
        $this->view = new ApiView();
        $this->data = file_get_contents("php://input");
    
    }

    private function getData() {
        return json_decode($this->data);
    }

    function getProducts($params = null) { 
        
        //Obtengo todos los productos con los parametros de ordenado, paginado y filtrado.

        // Parámetros de ordenado
        $orderBy = $_GET['orderBy'] ?? "nombre";
        $orderMode = $_GET['orderMode'] ?? "asc";
        
        // Parámetros de paginado
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 50); // si no me indican, por defecto muestro 50 registros

        // Parámetros de filtrado
        $filterBy = $_GET['filterBy'] ?? null;
        $equalTo = $_GET['equalTo'] ?? null;

        //Obtiene los nombres de las columnas de la tabla productos y los almacena en el arreglo $columns.
        $columns = $this->getHeaderColumns();

        // Verifica si los parámetros de ordenado son válidos
        //in_array comprueba si el valor existe en el arreglo
        // strtolower convierte strings a minuscula
        if (($orderBy == 'nombre_marca' || in_array(strtolower($orderBy), $columns)) && (strtolower($orderMode == "asc") || strtolower($orderMode == "desc"))){

            //Asigna un valor $order para pasar al modelo en funcion del campo por el que se quiere ordenar
            if ($orderBy == 'nombre_marca') {
                $order = 'marca.nombre_marca';
            }
            else {
                $order = 'producto.'.$orderBy;
            }
            // Verifica si los parámetros de paginado son válidos
            if((is_numeric($page) && $page>0) && (is_numeric($limit) && $limit>0)){

                //Calcula cuál es el primer elemento a mostrar del paginado y lo almacena en $startAt
                $startAt = ($page*$limit)-$limit;

                // Verifica si existen los parámetros de filtrado
                if ($filterBy!=null && $equalTo!=null){

                    //Verifica que el campo $filterBy exista en la tabla (comparando con $columns)
                    if ($filterBy == 'nombre_marca' || in_array(strtolower($filterBy), $columns)){

                        //Asigna un valor $filter para pasar al modelo en funcion del campo por el que se quiere ordenar
                        if ($filterBy == 'nombre_marca') {
                            $filter = 'marca.nombre_marca';
                        }
                        else {
                            $filter = 'producto'.$filterBy;
                        }
                        
                        //Obtiene todos los productos del modelo y pasa los parametros de ordenamiento, paginado y filtrado.
                        $response = $this->modelProducts->getAllWithFilter($order, $orderMode, $limit, $startAt, $filter, $equalTo);

                        //Verifica si la consulta se realizó correctamente
                        if($response){

                            //Verifica si el resultado de la consulta está vacío.
                            if (empty($response)) {
                                $this->view->response("La consulta realizada no arrojó resultados", 204);
                            }
                            else {

                                //Envía el/los producto/s a la vista para ser mostrado/s.
                                $this->view->response($response, 200);
                            }
                        }
                        else {

                            //Informa error interno de servidor
                            $response = $this->view->response("No se pudo realizar la consulta especificada.", 500);
                        }                            
                    }
                    else {

                        //Informa error de parámetro no válido
                        $response = $this->view->response("Parámetro de filtrado no válido.", 400);
                    }                        
                }
                else {

                   //Obtiene todos los productos del modelo y pasa los parametros de ordenamiento y paginado.
                   $response = $this->modelProducts->getAllProducts($order, $orderMode, $limit, $startAt);
                    //var_dump($result);
                    $this->view->response($response,200);
                }
                
            }  
            else {
                //Informa error de parámetro no válido
                $response = $this->view->response("Parámetro de paginado no válido.", 400);       
            }
        }
        else {

            //Informa error de parámetro no válido
            $response = $this->view->response("Parámetro de ordenamiento no válido", 400);
        }
    }

    //Método que devuelve un arreglo con los nombres de las columnas de una tabla
    function getHeaderColumns($params = null) {

        //Se define un arreglo vacío para almacenar los nombres de las columnas.
        $columns = [];

        // Obtiene toda la información de las columnas de la tabla. Devuelve un arreglo de objetos con toda la info
        $result = $this->modelProducts->getColumns();

        //Recorre el arreglo y por cada elemento, extrae el nombre de la columna y lo agrega al arreglo $columns.
        foreach ($result as $column) {
            array_push($columns, $column->Field);
        }
        return $columns;
    }



    function getProduct($params = null) {
        // obtengo el id del arreglo params
        $id = $params[':ID'];
        $producto= $this->modelProducts->getProduct($id);

        // si no existe devuelvo 404

        if($producto) {
            $this->view->response($producto);
        }

        else {
            $this->view->response("El producto con el id=$id no existe", 404);
        }


        
    }

    function addProduct($params = null) { //cambiarla por add comentario
         //Se obtiene el JSON con los datos a insertar.
         $producto = $this->getData();

          //Verifica que el JSON ingresado no contenga campos vacíos. 
        if (empty($producto->nombre) || empty($producto->precio) || empty($producto->talle) || empty($producto->id_marca)) {
            $this->view->response("Complete todos los datos", 400);
            
        }
        else {
            //Inserta el JSON en la base de datos y almacena en $id el Id del producto insertado.
            $id = $this->modelProducts->insertProduct($producto->nombre, $producto->precio, $producto->talle, $producto->id_marca);
            //Obtiene nuevamente el producto que acaba de insertar, para mostrarlo en la vista.
            $producto = $this->modelProducts->getProduct($id);
            $this->view->response($producto, 201);
        }        
    }

    function deleteProduct($params = null) {
        $id = $params[':ID'];
        $product = $this->modelProducts->getProduct($id);

        if ($product) {
            $this->modelProducts->deleteProductById($id);
            $this->view->response("El producto con el id=$id fue borrado con exito", 200);
        }
        else {
            $this->view->response("El comentario con el id=$id no existe", 404);
        }
    }

    

 
}



