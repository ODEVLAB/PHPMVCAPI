<?php

class Users
{   
    
    private $usersTable = "users";      
    public $id;
    public $name;
    public $email;
    public $age;
    public $designation;
	public $password;   
    public $createdAt; 
	public $updatedAt; 
    private $conn;
	
	//constructor
	//initialize connection
    public function __construct($db)
	{
        $this->conn = $db;
    }	
	
	//function to create a new user
	//where all necessary data is passed in the request body

	function create()
	{
		
		$stmt = $this->conn->prepare("
			INSERT INTO ".$this->usersTable."(`name`, `email`, `age`, `designation`, `password`, `createdAt`, `updatedAt`)
			VALUES(?,?,?,?,?,?,?)");
		
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->age = htmlspecialchars(strip_tags($this->age));
		$this->designation = htmlspecialchars(strip_tags($this->designation));
		$this->password = htmlspecialchars(strip_tags($this->password));
		$this->createdAt = htmlspecialchars(strip_tags($this->createdAt));
		$this->updatedAt = htmlspecialchars(strip_tags($this->updatedAt));
		
		
		$stmt->bind_param("ssiisss", $this->name, $this->email, $this->age, $this->designation, $this->password, $this->createdAt, $this->updatedAt);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
	
	//function to get all /user details
	function read()
	{	
		if($this->id) {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->usersTable." WHERE id = ?");
			$stmt->bind_param("i", $this->id);					
		} else {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->usersTable);		
		}		
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}

	//function to update user details
	function update()
	{
	 
		$stmt = $this->conn->prepare("
			UPDATE ".$this->usersTable." 
			SET name= ?, email = ?, age = ?, designation = ?, updatedAt = ?
			WHERE id = ?");
	 
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->age = htmlspecialchars(strip_tags($this->age));
		$this->designation = htmlspecialchars(strip_tags($this->designation));
		$this->updatedAt = htmlspecialchars(strip_tags($this->updatedAt));
	 
		$stmt->bind_param("ssiiss", $this->name, $this->email, $this->age, $this->designation, $this->updatedAt, $this->id);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	//function to delete user details
	function delete()
	{
		
		$stmt = $this->conn->prepare("
			DELETE FROM ".$this->usersTable." 
			WHERE id = ?");
			
		$this->id = htmlspecialchars(strip_tags($this->id));
	 
		$stmt->bind_param("i", $this->id);
	 
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}

	//function to update user password
	function updatePassword()
	{
		$stmt = $this->conn->prepare("
			UPDATE ".$this->usersTable."
			SET password = ?
			WHERE id = ?");
			
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->password = htmlspecialchars(strip_tags($this->password));
	 
		$stmt->bind_param("si", $this->password, $this->id);
	 
		if($stmt->execute()){
			return true;
		}
	 
		return false;		
	}

	//function to retrieve user password
	function getPassword()
	{
		$stmt = $this->conn->prepare("
			SELECT password FROM ".$this->usersTable."
			WHERE id = ?");
			
		$this->id = htmlspecialchars(strip_tags($this->id));
	 
		$stmt->bind_param("i", $this->id);
	 
		if($stmt->execute()){
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			return $row['password'];
		}
	 
		return false;		
	}
}
?>