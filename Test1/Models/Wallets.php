<?php

class Wallets
{   
    
    private $walletTable = "wallet";      
    public  $user_id;
    public  $amount;
    public  $tr_type;
    public  $initiatedAt;  
    private $conn;
	
    public function __construct($db)
	{
        $this->conn = $db;
    }

    //function to fund wallet
    //where all necessary data is passed in the request body
    //and the user is authenticated
    function fundWallet()
    {
        $stmt = $this->conn->prepare("
            INSERT INTO ".$this->walletTable."(`user_id`, `amount`, `tr_type`, `initiatedAt`)
            VALUES(?,?,?,?)");
        
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->tr_type = htmlspecialchars(strip_tags($this->tr_type));
        $this->initiatedAt = htmlspecialchars(strip_tags($this->initiatedAt));
        
        
        $stmt->bind_param("ssss", $this->user_id, $this->amount, $this->tr_type, $this->initiatedAt);
        
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }

    //get user wallet history
    //credit and debit transactions that has occurred on account
    //with initiation timestamp
    function walletHistory($user_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->walletTable." WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    //retrieve user balance using sql case
    //sum all credit and debit transactions
    //using the transaction type 'tr_type'
    function getBalance($user_id)
    {
        $balance = '';
        $stmt = $this->conn->prepare(
            "SELECT SUM(CASE WHEN tr_type = 'Credit' THEN amount ELSE - amount END) FROM ".$this->walletTable." WHERE user_id = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        return $balance;
    }
    
}

?>