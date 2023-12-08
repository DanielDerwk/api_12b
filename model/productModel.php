<?php 
require_once "ConDB.php";

class ProductModel{
    static public function createProduct($data){
        $cantName = self::getName($data["product_name"]);
        if($cantName == 0){
            $query = "INSERT INTO products(product_id, product_name, product_unit, product_value, prod_identifier, prod_key, prod_status) 
            VALUES (NULL, :product_name, :product_unit, :product_value, :prod_identifier, :prod_key, :prod_status)";
            $status="0";
            $stament = Conection::connection()->prepare($query);
            $stament->bindParam(":product_name", $data["product_name"], PDO::PARAM_STR);
            $stament->bindParam(":product_unit", $data["product_unit"], PDO::PARAM_INT);
            $stament->bindParam(":product_value", $data["product_value"], PDO::PARAM_INT);
            $stament->bindParam(":prod_identifier", $data["prod_identifier"], PDO::PARAM_STR);
            $stament->bindParam(":prod_key", $data["prod_key"], PDO::PARAM_STR);
            $stament->bindParam(":prod_status", $status, PDO::PARAM_INT);
            $message = $stament->execute() ? "ok" : Conection::connection() ->errorInfo();
            $stament -> closeCursor();
            $stament = null;
            $query = "";
        }
        else{
            $message = "Producto ya esta registrado";
        }
        return $message;
    }
    //Verificar si ya existe ese producto
    static private function getName($name){
        $query = "";
        $query = "SELECT product_name FROM products WHERE product_name = '$name';";
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->rowCount();
        return $result;
    }
    //Traer todos los usuarios o uno en especifico
    static function getProduct($id){
        $query = "";
        $id = is_numeric($id) ? $id : 0;
        $query = "SELECT product_id, product_name, product_unit, product_value FROM products";
        $query.=($id > 0) ? " WHERE products.product_id = '$id' AND " : "";
        $query.=($id > 0) ? " prod_status='1';" : " WHERE prod_status = '1';"; 
        $stament = Conection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Actualizar producto
    static public function updateProduct($data){
        $query = "";
        $query = "UPDATE products SET product_name = :product_name, product_value = :product_value, product_unit = :product_unit WHERE product_id = :product_id";
        $stament = Conection::connection()->prepare($query);
        $stament->bindParam(":product_name", $data["product_name"], PDO::PARAM_STR);
        $stament->bindParam(":product_value", $data["product_value"], PDO::PARAM_STR);
        $stament->bindParam(":product_unit", $data["product_unit"], PDO::PARAM_STR);
        $stament->bindParam(":product_id", $data["product_id"], PDO::PARAM_INT);
        $message = $stament->execute() ? "ok" : Conection::connection() ->errorInfo();
        $stament -> closeCursor();
        $stament = null;
        $query = "";
        return $message;
    }
    //Borrar producto
    static public function deleteProduct($id){
        $query="";
        $query = "DELETE FROM products WHERE product_id = :product_id";
        $stament = Conection::connection()->prepare($query);
        $stament->bindParam(":product_id", $id["product_id"], PDO::PARAM_INT);
        $message = $stament->execute() ? "Productos eliminado correctamente" : Conection::connection()->errorInfo();
        $stament->closeCursor();
        $stament = null;
        $query = "";
        return $message;
    }
    //Activar producto
    public static function activateProduct($id) {
        $query = "UPDATE products SET prod_status = '1' WHERE product_id = :product_id";
        $stament = Conection::connection()->prepare($query);
        $stament->bindParam(":product_id", $id["product_id"], PDO::PARAM_INT);
        $message = $stament->execute() ? "Producto activado correctamente" : Conection::connection()->errorInfo();
        $stament->closeCursor();
        $stament = null;
        $query = "";
        return $message;
    }



}

?>
