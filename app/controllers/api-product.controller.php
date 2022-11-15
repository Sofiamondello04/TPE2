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
        $orderBy = $_GET['orderBy'] ?? "nombre";
        $orderMode = $_GET['orderMode'] ?? "asc";
  
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 50); // si no me indican, por defecto muestro 50 registros

        $filterBy = $_GET['filterBy'] ?? null;
        $equalTo = $_GET['equalTo'] ?? null;
        
        $columns = $this->getHeaderColumns();//Nombres de los campos de la tabla producto y los guardo en el arreglo $columns.

        // Verifica si los parámetros de ordenado son válidos
        // in_array comprueba si el valor existe en el arreglo
        // strtolower convierte strings a minuscula
        if (($orderBy == 'nombre_marca' || in_array(strtolower($orderBy), $columns)) && (strtolower($orderMode == "asc") || strtolower($orderMode == "desc"))){
            if ($orderBy == 'nombre_marca') {
                $order = 'marca.nombre_marca';
            }
            else {
                $order = 'producto.'.$orderBy;
            }
            if((is_numeric($page) && $page>0) &&  (is_numeric($limit) && $limit>0)){
                $startAt = ($page*$limit)-$limit;
                if ($filterBy!=null && $equalTo!=null){
                    if ($filterBy == 'nombre_marca' || in_array(strtolower($filterBy), $columns)){
                        if ($filterBy == 'nombre_marca') {
                            $filter = 'marca.nombre_marca';
                        }
                        else {
                            $filter = $filterBy;
                        }
                        $response = $this->modelProducts->getAllWithParameters($order, $orderMode, $limit, $startAt, $filter, $equalTo);
                        if(isset($response)){
                            if (empty($response)) {
                                $response= $this->view->response("La consulta realizada no arrojó resultados.", 204);
                            }
                            else {
                                $this->view->response($response, 200);
                            }
                        }
                        else {
                            $response = $this->view->response("No se pudo realizar la consulta especificada.", 500);
                        }                            
                    }
                    else {
                        $response = $this->view->response("Parámetro de filtrado no válido.", 400);
                    }                        
                }
                else {
                    $response = $this->modelProducts->getAllProducts($order, $orderMode, $limit, $startAt);
                    $this->view->response($response,200);
                }          
            }  
            else {
                $response = $this->view->response("Parámetro de paginado no válido.", 400);       
            }
        }
        else {
            $response = $this->view->response("Parámetro de ordenamiento no válido", 400);
        }
    }

    function getHeaderColumns($params = null) {
        $columns = [];
        $result = $this->modelProducts->getColumns();
        foreach ($result as $column) {
            array_push($columns, $column->Field);
        }
        return $columns;
    }

    function getProduct($params = null) {
        $id = $params[':ID'];
        $producto= $this->modelProducts->getProduct($id);
        if($producto) {
            $this->view->response($producto, 200);
        }
        else {
            $this->view->response("El producto con el id=$id no existe", 404);
        }      
    }

    function addProduct($params = null) { 
         $producto = $this->getData(); 
        if (empty($producto->nombre) || empty($producto->precio) || empty($producto->talle) || empty($producto->id_marca)) {
            $this->view->response("Complete todos los datos", 400);          
        }
        else {
            $id = $this->modelProducts->insertProduct($producto->nombre, $producto->precio, $producto->talle, $producto->id_marca);
            $producto = $this->modelProducts->getProduct($id);
            $this->view->response($producto, 201);
        }        
    } 
}



