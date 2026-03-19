<?php

require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/data.php';

function getAllUsers(string $dataFile) : array {
    $data = findAllUsers($dataFile);
    return ['users' => $data['users']];
}

function createUser(string $dataFile, ?array $input): array{
    if(!is_array($input)){
        return ['status' => 400, 'error' => 'Invalid JSON body'];
    }

    $error = validateRequiredFields($input, ['name', 'age', 'email']);
    if ($error) {
        return ['status' => 400, 'error' => $error];
    }

    $error = validateUserFields($input);
    if ($error) {
        return ['status' => 400, 'error' => $error];
    }

    $user = insertUser($dataFile,[
        'name' => $input['name'],
        'age' => $input['age'],
        'email' => $input['email']
    ]);

    return ['status' => 201, 'user' => $user];
}

function editUser(string $dataFile, ?int $id, ?array $input, bool $partial = false){
    if ($id === null) {
        return ['status' => 400, 'error' => 'User ID is required'];
    }
    if(!is_array($input)){
        return ['status' => 400, 'error' => 'Invalid JSON body'];
    }

    if (!$partial) {
        $error = validateRequiredFields($input, ['name', 'age', 'email']);
        if ($error) {
            return ['error' => $error, 'status' => 400];
        }
    }

    $error = validateUserFields($input);
    if ($error) {
        return ['status' => 400, 'error' => $error];
    }

    $allowed = ['name', 'age', 'email'];
    $fields = array_intersect_key($input, array_flip($allowed));

    if(isset($fields['name'])){
        $fields['name'] = trim($fields['name']);
    }
    if(isset($fields['age'])){
        $fields['age'] = trim($fields['age']);
    }
    
    $user = updateUser($dataFile, $id, $fields);

    if ($user === null) {
        return ['status' => 404, 'error' => 'User not found'];
    }
    
    return ['status' => 200, 'user' => $user];
}

function removeUser(string $dataFile, ?int $id){
    if ($id === null) {
        return ['status' => 400, 'error' => 'User ID is required'];
    }

    $user = deleteUser($dataFile, $id);

    if ($user === null) {
        return ['status' => 404, 'error' => 'User not found'];
    }
    
    return ['data' => ['deleted' => $user], 'status' => 200];
}

