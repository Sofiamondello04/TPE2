<?php

class ProductModel {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_pototito;charset=utf8', 'root', 'root');
    }

    public function getAllProducts($order, $orderMode, $limit, $startAt) {

        // modifique el INNER JOIN
        $query = $this->db->prepare("SELECT producto.*, marca.nombre_marca FROM producto 
        INNER JOIN marca ON producto.id_marca = marca.id_m
        ORDER BY $order $orderMode
        LIMIT $limit
        OFFSET $startAt");
        $query->execute();
        $productos = $query->fetchAll(PDO::FETCH_OBJ); 
        return $productos;
    }

    public function getAllWithParameters($order, $orderMode, $limit, $startAt, $filterBy, $equalTo){
        $query = $this->db->prepare("SELECT producto.*, marca.nombre_marca FROM producto
        INNER JOIN marca ON producto.id_marca = marca.id_m
        WHERE $filterBy = ?
        ORDER BY $order $orderMode
        LIMIT $limit OFFSET $startAt");
        $query->execute([$equalTo]);
        $productos = $query->fetchAll(PDO::FETCH_OBJ);
        return $productos;
    }

    public function getColumns(){
        $query = $this->db->prepare('DESCRIBE producto');
        $query->execute();
        $columns = $query->fetchAll(PDO::FETCH_OBJ);
        return $columns;
    }

    public function insertProduct($nombre, $precio, $talle, $id_marca) {
        $query = $this->db->prepare("INSERT INTO producto (nombre, precio, talle, id_marca) VALUES (?, ?, ?, ?)");
        $query->execute([$nombre, $precio, $talle, $id_marca]);

        return $this->db->lastInsertId();
    }

    function getProduct($id) {
        $query= $this->db->prepare("SELECT producto.*, marca.nombre_marca FROM producto
        INNER JOIN marca ON producto.id_marca = marca.id_m WHERE id= ?");
        $query-> execute([$id]);
        $product= $query->fetch(PDO::FETCH_OBJ);
        return $product;
    }

    function getProductsOfBrand($id_marca) {
        $query= $this->db->prepare("SELECT * FROM producto INNER JOIN marca ON producto.id_marca = marca.id_m WHERE id_marca= ?");
        $query-> execute([$id_marca]);
        $products= $query->fetchAll(PDO::FETCH_OBJ);
        return $products;
    }

    public function updateP($productoE) { 
        $query = $this->db->prepare('UPDATE producto SET nombre= ?, talle= ?, precio= ?,  id_marca= ? WHERE id = ?');  
        $query->execute([$productoE->nombreE, $productoE->talleE, $productoE->precioE,  $productoE->id_marcaE, $productoE->id]);      
    }

}
