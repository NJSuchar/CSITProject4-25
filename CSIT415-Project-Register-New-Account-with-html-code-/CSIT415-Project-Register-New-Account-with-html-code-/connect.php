<?php

/**
 * Database connection handler class.
 * Use this class to connect to and disconnect from the database.
 */
class Dbh {
     public $servername = "localhost:3306";
     public $username = "root";
     public $password = "password";
     public $dbname = "libman";
     public $conn;

    /**
     * Establishes a connection to the database.
     * 
     * @return mysqli The database connection object.
     * @throws Exception If the connection fails.
     */
    public function connectDB(): mysqli {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

    /**
     * Closes the database connection.
     */
    public function disconnectDB(): void {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

?>
