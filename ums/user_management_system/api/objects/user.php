<?php
/**
 * @category     User Management System
 * @author       Prashant
 * @createdOn    27 Mar 2019
 * @description  All user actions
 */

class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $name;
    public $surname;
    public $email;
    public $group_id;
    public $created;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read users
    function read(){
        // select all query
        $query = "SELECT
                    g.name as group_name, u.id, u.name, u.surname, u.email, u.group_id, u.created
                FROM
                    " . $this->table_name . " u
                    LEFT JOIN
                        groups g
                            ON u.group_id = g.id
                ORDER BY
                    u.created DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // create user
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, surname=:surname, email=:email, group_id=:group_id, created=:created";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->surname=htmlspecialchars(strip_tags($this->surname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->group_id=htmlspecialchars(strip_tags($this->group_id));
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":group_id", $this->group_id);
        $stmt->bindParam(":created", $this->created);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
         
    }

    // used when filling up the update user form
    function readOne(){
     
        // query to read single record
        $query = "SELECT
                    g.name as group_name, u.id, u.name, u.surname, u.email, u.group_id, u.created
                FROM
                    " . $this->table_name . " u
                    LEFT JOIN
                        groups g
                            ON u.group_id = g.id
                WHERE
                    u.id = ?
                LIMIT
                    0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of user to be updated
        $stmt->bindParam(1, $this->id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->name = $row['name'];
        $this->surname = $row['surname'];
        $this->email = $row['email'];
        $this->group_id = $row['group_id'];
        $this->created = $row['created'];
     
    }

    // update the user
    function update(){
     
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name = :name,
                    surname = :surname,
                    group_id = :group_id
                WHERE
                    id = :id";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->surname=htmlspecialchars(strip_tags($this->surname));
        $this->group_id=htmlspecialchars(strip_tags($this->group_id));
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':surname', $this->surname);
        $stmt->bindParam(':group_id', $this->group_id);
        $stmt->bindParam(':id', $this->id);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }

    // delete the user
    function delete(){
     
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
         
    }

    // search users
    function search($keywords){
        // select all query
        $query = "SELECT
                    g.name as group_name, u.id, u.name, u.surname, u.email, u.group_id, u.created
                FROM
                    " . $this->table_name . " u
                    LEFT JOIN
                        groups g
                            ON u.group_id = g.id
                WHERE
                    u.email LIKE ? OR u.name LIKE ? OR u.surname LIKE ?
                ORDER BY
                    u.created DESC";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
     
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
}