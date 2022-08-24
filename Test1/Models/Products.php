<?php

class Products
{   
    
    private $productsTable = "products";      
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;   
    public $createdAt; 
	public $updatedAt; 
    private $conn;
	
	//constructor
	//initialize connection
    public function __construct($db)
	{
        $this->conn = $db;
    }	
	
	//function to get product(s) details
	function read()
	{	
		if($this->id) {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->productsTable." WHERE id = ?");
			$stmt->bind_param("i", $this->id);					
		} else {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->productsTable);		
		}		
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
	//function to create a new product
	function create()
	{
		
		$stmt = $this->conn->prepare("
			INSERT INTO ".$this->productsTable."(`name`, `description`, `price`, `category_id`, `createdAt`, `updatedAt`)
			VALUES(?,?,?,?,?,?)");
		
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->price = htmlspecialchars(strip_tags($this->price));
		$this->category_id = htmlspecialchars(strip_tags($this->category_id));
		$this->createdAt = htmlspecialchars(strip_tags($this->createdAt));
		$this->updatedAt = htmlspecialchars(strip_tags($this->updatedAt));
		
		
		$stmt->bind_param("ssiiss", $this->name, $this->description, $this->price, $this->category_id, $this->createdAt, $this->updatedAt);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
	
	//function to update a product
	function update()
	{
	 
		$stmt = $this->conn->prepare("
			UPDATE ".$this->productsTable." 
			SET name= ?, description = ?, price = ?, category_id = ?, updatedAt = ?
			WHERE id = ?");
	 
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->price = htmlspecialchars(strip_tags($this->price));
		$this->category_id = htmlspecialchars(strip_tags($this->category_id));
		$this->updatedAt = htmlspecialchars(strip_tags($this->updatedAt));
	 
		$stmt->bind_param("ssiiss", $this->name, $this->description, $this->price, $this->category_id, $this->updatedAt, $this->id);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	//function to delete a product
	function delete()
	{
		
		$stmt = $this->conn->prepare("
			DELETE FROM ".$this->productsTable." 
			WHERE id = ?");
			
		$this->id = htmlspecialchars(strip_tags($this->id));
	 
		$stmt->bind_param("i", $this->id);
	 
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
}
?>