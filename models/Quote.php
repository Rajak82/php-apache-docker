<?php 
    class DBQuote {
    // DB stuff
    private $conn;
    private $table = 'quotes';

    // Post Properties
    public $id;
    public $quote;
    public $author_id;
    public $author_name;
    public $category_id;
    public $category_name;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get Quotes
    public function read() {
      
      // Create query
      if (isset($this->author_id) && isset($this->category_id)){
        $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                                FROM $this->table p
                                LEFT JOIN categories c ON p.category_id = c.id
                                LEFT JOIN authors a ON p.author_id = a.id
                                WHERE p.author_id = ? AND p.category_id = ?";

                  // Prepare statement
                  $stmt = $this->conn->prepare($query);
                  // Bind ID
                  $stmt->bindParam(1, $this->author_id);
                  $stmt->bindParam(2, $this->category_id);
      }else if (isset($this->author_id)){
        $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                                FROM $this->table p
                                LEFT JOIN categories c ON p.category_id = c.id
                                LEFT JOIN authors a ON p.author_id = a.id
                                WHERE p.author_id = ?";
                  // Prepare statement
                  $stmt = $this->conn->prepare($query);

                  // Bind ID
                  $stmt->bindParam(1, $this->author_id);
      }
      else if(isset($this->category_id)){
        $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                                FROM $this->table p
                                LEFT JOIN categories c ON p.category_id = c.id
                                LEFT JOIN authors a ON p.author_id = a.id
                                WHERE p.category_id = ?";
                  // Prepare statement
                  $stmt = $this->conn->prepare($query);

                  // Bind ID
                  $stmt->bindParam(1, $this->category_id);
      } else{
        $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                                FROM $this->table p
                                LEFT JOIN categories c ON p.category_id = c.id
                                LEFT JOIN authors a ON p.author_id = a.id
                                ORDER BY p.id";


                  // Prepare statement
                  $stmt = $this->conn->prepare($query);
      }

      // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Single Post
    public function read_single() {
          // Create query
            $query = "SELECT c.category as category_name, a.author as author_name, p.id, p.category_id, p.quote, p.author_id
                                    FROM $this->table p
                                    LEFT JOIN categories c ON p.category_id = c.id
                                    LEFT JOIN authors a ON p.author_id = a.id
                                    WHERE p.id = ?
                                    LIMIT 1";
          // Prepare statement
            $stmt = $this->conn->prepare($query);

          // Bind ID
            $stmt->bindParam(1, $this->id);


          // Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row){
          // Set properties
            $this->id = $row['id'];
            $this->quote = $row['quote'];
            $this->author_name = $row['author_name'];
            $this->category_name = $row['category_name'];
            return true;
          }else{
            return false;
          }
        }
    // Create Post
    public function create() {
          // Create query
            $query = "INSERT INTO $this->table (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";

          // Prepare statement
            $stmt = $this->conn->prepare($query);

          // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

          // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

          // Execute query
            if($stmt->execute()) {
            return true;
        }

      // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Update Post
    public function update() {
          // Create query
            $query = "UPDATE $this->table 
                                SET quote = :quote, author_id = :author_id, category_id = :category_id
                                WHERE id = :id";

          // Prepare statement
            $stmt = $this->conn->prepare($query);

          // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);

          // Execute query
            if($stmt->execute()) {
            return true;
            }

          // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
    }

    // Delete Post
    public function delete() {
          // Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

          // Prepare statement
            $stmt = $this->conn->prepare($query);

          // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

          // Bind data
            $stmt->bindParam(':id', $this->id);

          // Execute query
            if($stmt->execute()) {
            return true;
            }

          // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false;
        }
    
    }