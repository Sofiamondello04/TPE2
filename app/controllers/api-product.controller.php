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



