<?php 

    class Database {

    private $conn;
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    private function loadEnv() {
        // Assuming your .env file path is /etc/secrets/.env (remember security risks)
        $envFilePath = '/etc/secrets/.env';
        $lines = file($envFilePath);
    
        foreach ($lines as $line) {
          $line = trim($line);
          if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            putenv("$key=$value");
          }
        }
      }
    
    public function __construct() {
        $this->loadEnv();  // Call the function to load environment variables
        $this->username = getenv('USERNAME');
        $this->password = getenv('PASSWORD');
        $this->dbname = getenv('DBNAME');
        $this->host = getenv('HOST');
        $this->port = getenv('PORT');
    }

    public function connect(){
        if ($this->conn){
            return $this->conn;
        } else {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";

            try {        
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo 'connection successful ';
                return $this->conn;
            } catch (PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
                }
            }
        }
    }

    $test = new Database;
    var_dump($test->connect());
