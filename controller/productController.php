<?php
class ProductController{
    private $_method;
    private $_complement;
    private $_data;

    function __construct($_method, $_complement, $_data){
        $this->_method = $_method;
        $this->_complement = $_complement  == null ? 0 : $_complement;
        $this->_data = $_data != 0 ? $_data : "";
    }

    public function index(){
        switch($this->_method){
            case "GET":
                if($this->_complement == 0){
                    $product = ProductModel::getProduct(0);
                    $json = $product;
                    echo json_encode($json, true);
                    return;
                }
                else{
                    $product = ProductModel::getProduct($this->_complement);
                    $json = $product;
                    echo json_encode($json, true);
                    return;
                }
            case "POST":
                $createProduct = ProductModel::createProduct($this->generateSalting());
                $json = array(
                    "response" => $createProduct
                );
                
                echo json_encode($json, true);
                return;
            case "PUT":
                $updateProduct = ProductModel::updateProduct($this->generateSalting());
                $json = array(
                    "response" => $updateProduct
                );
                echo json_encode($json, true);
                return;
                
            case "DELETE":
                $deleteProduct = ProductModel::deleteProduct($this->_data);
                $json = array(
                    "response" => $deleteProduct
                );
                echo json_encode($json, true);
                return;
            case 'PATCH':
                $activateProduct = ProductModel::activateProduct($this->_data);
                $json = array(
                    "response" => $activateProduct
                );
                echo json_encode($json, true);
                return;
            default:
                $json = array(
                    "ruta:" => "not found"
                );
                echo json_encode($json, true);
                return;
        }
    }

    private function generateSalting(){
        //$trimmedData se usa para limpiar los datos
        $trimmedData = "";
        if(($this->_data != "") || (!empty($this->_data))){
            //arra_map se utiliza para pasar el objeto tipo JSON a arreglo
            $trimmedData = array_map('trim', $this->_data);
            //Generando el salting para credenciales
            $identifier = str_replace("$", "ue3", crypt($trimmedData['product_name'], 'ue56'));
            $key = str_replace("$", "2023", crypt($trimmedData['product_name'], '56ue'));
            $trimmedData['prod_identifier'] = $identifier;
            $trimmedData['prod_key'] = $key;
            return $trimmedData;
        }
    }
    
}
?>