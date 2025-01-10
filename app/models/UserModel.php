<?php

require_once __DIR__ . '/../core/Connection.php';

class User
{
    private $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public static function getAll()
    {
        $db = Connection::getInstance();
        $stmt = $db->query('SELECT id, name, lastname, gender, birthday, state FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $db = Connection::getInstance();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);  
    }

    public function save()
    {
        $db = Connection::getInstance();
        $stmt = $db->prepare('INSERT INTO users (name, lastname, gender, birthday, password, state) VALUES (?, ?, ?, ?,?, ?)');
        $success = $stmt->execute([
            $this->data['name'],
            $this->data['lastname'],
            $this->data['gender'],
            $this->data['birthday'],
            $this->data['password'],
            $this->data['state']
        ]);
        if ($success) {
            try {
                $stmt = $db->prepare('CALL Backuop()');
                $stmt->execute();
                return true;
            } catch (\Exception $e) {
                error_log("Error al ejecutar el procedimiento: " . $e->getMessage());
                return false;
            }
        }
        return false;
    
    }

    public function update($data)
    {
        $db = Connection::getInstance();
        $stmt = $db->prepare('UPDATE users SET name = ?, lastname = ?, gender = ?, birthday = ?, password = ?, state = ? WHERE id = ?');
    
        return $stmt->execute([
            $data['name'],        
            $data['lastname'],
            $data['gender'],
            $data['birthday'],
            $data['password'],
            $data['state'],
            $data['id']          
        ]);
    }
    
    public static function delete($id)
    {
        $db = Connection::getInstance();
        $stmt = $db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }


    public function setData($data)
    {
        $this->data = $data;
    }
}
