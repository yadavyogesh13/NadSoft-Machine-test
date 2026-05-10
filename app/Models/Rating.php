<?php

class Rating
{
    private $conn;
    private $table_name = "ratings";

    public $id;
    public $business_id;
    public $name;
    public $email;
    public $phone;
    public $rating;
    public $last_query;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function save()
    {
        // Rule 1: If Email OR Phone already exists for that business, update/overwrite
        $query = "SELECT id FROM " . $this->table_name . " 
                  WHERE business_id = :business_id 
                  AND (email = :email OR phone = :phone) 
                  AND deleted_at IS NULL
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":business_id", $this->business_id);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            // Update existing
            return $this->update($row['id']);
        } else {
            // Rule 2: Insert new rating
            return $this->create();
        }
    }

    private function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (business_id, name, email, phone, rating) 
                  VALUES (:business_id, :name, :email, :phone, :rating)";

        $stmt = $this->conn->prepare($query);

        $this->business_id = htmlspecialchars(strip_tags($this->business_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->rating = htmlspecialchars(strip_tags($this->rating));

        $stmt->bindParam(":business_id", $this->business_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":rating", $this->rating);

        $this->last_query = $query;
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function update($id)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, rating = :rating, email = :email, phone = :phone 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->rating = htmlspecialchars(strip_tags($this->rating));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":id", $id);

        $this->last_query = $query;
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
