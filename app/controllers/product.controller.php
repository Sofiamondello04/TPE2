<?php
require_once './app/models/product.model.php'; 
require_once './app/views/product.view.php';
require_once './app/models/brand.model.php';
require_once './app/views/brand.view.php';
require_once './app/helpers/auth.helper.php';

class ProductController {
    private $modelProducts; 
    private $viewProducts; 
    private $modelBrands;
    private $viewBrands;
    private $products;
    private $brands;
    private $authHelper;
    

    public function __construct() {
        $this->modelProducts = new ProductModel();
        $this->viewProducts = new ProductView();
        $this->modelBrands = new BrandModel();
        $this->viewBrands = new BrandView();

        $this->products = $this->modelProducts->getAllProducts();
        $this->brands = $this->modelBrands->getAllBrands(); 

        $this->authHelper = new AuthHelper ();
    }

    public function showProducts() { 
        session_start();
        $this->viewProducts->assign($this->products, $this->brands);
        $this->viewProducts->showProducts($this->products);
    }

    function goAddProduct() {  
        $this->authHelper->checkLoggedIn();
        $this->viewProducts->showFormAddProduct($this->brands);
    }


    function filterProducts($id_marca){
        session_start();
        $this->viewProducts->assign($this->products, $this->brands); // es para que el header conozca 
        $productosMarca = $this->modelProducts->getProductsOfBrand($id_marca);
        $this->viewProducts-> showProductsOfBrand($productosMarca);
    }

    function addProduct() { 
        $this->authHelper->checkLoggedIn();
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $talle = $_POST['talle'];
            $id_marca = $_POST['id_marca']; 
            if (!empty($nombre && $precio && $talle && $id_marca)) {
                $this->modelProducts->insertProduct($nombre, $precio, $talle, $id_marca);
                header("Location: " . BASE_URL); 
            }
            else {
                echo ('Faltan completar datos');
            }        
    }

    function goViewProduct($id) {
        session_start();
        $this->viewProducts->assign($this->products, $this->brands);
        $producto= $this->modelProducts->getProduct($id);
        $this->viewProducts->viewProduct($producto);
    }
   
    function deleteProduct($id) {
        $this->authHelper->checkLoggedIn();
        $this->modelProducts->deleteProductById($id);
        header("Location: " . BASE_URL);
    }

    function goEditProduct($id) {
        $this->authHelper->checkLoggedIn();
        $producto= $this->modelProducts->getProduct($id);
        $this->viewProducts-> showFormEdit($id, $producto, $this->brands);  
    }

    function editProduct() {
        $this->authHelper->checkLoggedIn();
        $productoE = new stdClass();
        $productoE->id = $_POST['id'];
        $productoE->nombreE = $_POST['nombreE'];
        $productoE->precioE = $_POST['precioE'];
        $productoE->talleE= $_POST['talleE'];
        $productoE->id_marcaE = $_POST['id_marcaE'];
        if (!empty($productoE->nombreE && $productoE->precioE && $productoE->talleE && $productoE->id_marcaE)) {
            $this->modelProducts->updateP($productoE);
            header("Location: " . BASE_URL);
        }
        else {
            echo ('Faltan completar datos');
        }  
    }




    public function showBrands() {
        session_start();
        $this->viewBrands->showBrands($this->brands);
    }
    
    function goAddBrand() {       
        $this->authHelper->checkLoggedIn();
        $this->viewBrands->showFormAddBrand();
    }
   
    function addBrand() {
        $this->authHelper->checkLoggedIn();
        $nombre_marca = $_POST['nombre_marca'];
        if (!empty ($nombre_marca)) {
            $this->modelBrands->insertBrand($nombre_marca);     
            header("Location: " . BASE_URL);
        }
        else {
            echo ('Falta completar el nombre');
        }
         
    }
   
    function deleteBrand($id) {
        $this->authHelper->checkLoggedIn();
        $this->viewBrands->assign($this->products, $this->brands);

        try {
            $this->modelBrands->deleteBrandById($id);
            header("Location: " . BASE_URL);
        }

        catch (Exception $e) {
            $this->viewBrands->error();
            
        }
        
        
    }

    function goEditBrand($id) {
        $this->authHelper->checkLoggedIn();
        $marca= $this->modelBrands->getBrand($id);
        $this->viewBrands-> showFormEditBrand($id, $marca); 
    }

    function editBrand() {
        $this->authHelper->checkLoggedIn();
        $marcaE = new stdClass();
        $marcaE->id = $_POST['id'];
        $marcaE->nombre_marcaE = $_POST['nombre_marcaE']; 
        if (!empty ($marcaE->nombre_marcaE)) {
            $this->modelBrands->updateB($marcaE);
            header("Location: " . BASE_URL);
        }
        else {
            echo ('Falta completar el nombre');
        }

    }
       
}



