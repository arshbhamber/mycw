<?php
 
/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 */
class DbHandler {
 
    private $conn;
 
    function __construct() {
        require_once 'DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
 
    
    public function addItem($name,$price){
        
        $stmt = $this->conn->prepare("INSERT INTO food_items(name,price) values(?, ?)");
        $stmt->bind_param("si", $name, $price);
        $result = $stmt->execute();
        $stmt->close();
        
         if ($result) {
               
                return 1;
            } else {
               
                return 0;
            }
        
        
    }
    
    
    public function getItems(){
        
          $stmt = $this->conn->prepare("SELECT * from food_items;");
       
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        } else {
            return NULL;
        }
        
        
    }
    
}

?>