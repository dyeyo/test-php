<?php

require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    public function index()
    {
        $users = User::getAll();
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function create()
    {
        $data = [
            'name' => $_POST['name'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'birthday' => $_POST['birthday'],
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'state' => 1    
        ];

        $user = new User($data);
        $result = $user->save();

        header('Content-Type: application/json');
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'User created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to create user']);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        header('Content-Type: application/json');
        
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    }

    public function edit($id)
    {
        $data = [
            'name' => $_POST['name'],
            'lastname' => $_POST['lastname'],
            'gender' => $_POST['gender'],
            'birthday' => $_POST['birthday'],
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'state' => $_POST['state'],
            'id' => $id 
        ];
    
        $user = new User();
        $user->setData($data);
    
        if ($user->update($data)) {  
            header('Content-Type: application/json');
            echo json_encode(['message' => 'User updated successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found or update failed']);
        }
    }

    public function delete($id)
    {
        $result = User::delete($id);

        header('Content-Type: application/json');
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'User deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to delete user']);
        }
    }

    
}
