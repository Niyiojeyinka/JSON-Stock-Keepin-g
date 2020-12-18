<?php
require_once "Database.php";
class Controller {
    public $db;

    public function __construct()
    {
        $this->db= new Database('database.json');
    }
    public function response($data,$status)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function products()
    {

        return  $this->response($this->db->all_records,200);
    }
    public function updateProduct($index)
    {
        $prevData=  $this->db->getEntryByIndex($index);
        //get prev data
        $data =[
            "productName"=>$_POST['productname'],
            "quantity" => $_POST['quantity'],
            "price"=>$_POST['price'],
            "updated_at"=>time(),
            "created_at"=> $prevData['created_at'],
            "totalValue"=>$_POST['quantity']*$_POST['price'],
        ];
        $this->db->updateByIndex($index,$data);
         return $this->response(["message"=>"Product updated successfully"],200);

    }
    public function saveProduct()
    {
        $data =[
            "productName"=>$_POST['productname'],
            "quantity" => $_POST['quantity'],
            "price"=>$_POST['price'],
            "created_at"=>time(),
            "updated_at"=>time(),
            "totalValue"=>$_POST['quantity']*$_POST['price'],
        ];
        $this->db->insert($data);
        return $this->response(["message"=>"Product entered successfully"],201);
    }
    public function deleteProduct($index)
        {
            
            $this->db->deleteByIndex($index);
        return $this->response(["message"=>"Product deleted successfully"],200);

        }
}