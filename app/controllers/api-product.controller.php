<?php
require_once './app/models/product.model.php'; 
require_once './app/views/api-view.php';



class ApiProductController {
    private $modelProducts; 
    private $view;
    private $body;
  
    public function __construct() {
        $this->modelProducts = new ProductModel();
        $this->modelBrands = new BrandModel();
        $this->view = new ApiView();
        $this->brands = $this->modelBrands->getAllBrands(); // ver si lo necesito
    
    }

    public function getProducts($params = null) { 
        $productos = $this->modelProducts->getAllProducts();
        $this->view->response($productos);
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
         $body = $this->getBody();

          //Verifica que el JSON ingresado no contenga campos vacíos. 
        if (empty($body->nombre) || empty($body->precio) || empty($body->talle) || empty($body->id_marca)) {
            $this->view->response("Complete todos los datos", 400);
            
        }
        else {
            //Inserta el JSON en la base de datos y almacena en $id el Id del producto insertado.
            $id = $this->modelProducts->insertProduct($body);
            //Obtiene nuevamente el producto que acaba de insertar, para mostrarlo en la vista.
            $producto = $this->modelProducts->getProduct($id);
            $this->view->response($task, 201);
        }        
    }

    function deleteProduct($params = null) {
        $id = $params[':ID'];
        $product = $this->modelProducts->getProduct($id);

        if ($product) {
            $this->modelProducts->deleteProduct($id);
            $this->view->response("El producto con el id=$id fue borrado con exito", 200);
        }
        else {
            $this->view->response("El comentario con el id=$id no existe", 404);
        }
    }

    private function getBody() {
        $this->body = file_get_contents("php://input"); // lee el body del request
        return json_decode($this->data);
    }

 
}



