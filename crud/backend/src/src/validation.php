<?php

function validateRequiredFields(array $input, array $required): ?string {
    $missing = [];

    foreach($required as $field) {
        if (!isset($input[$field])) {
            $missing[] = $field;
        }
    }

    if(!empty($missing)){
        return 'Missing required fields: ' . implode(', ', $missing);   
    }
    return null;
}

function validateUserFields(array $input): ?string {
    if(isset($input['name'])){
        $name = trim($input['name']);
        if ($name === '') return 'Name cannot be empty';
        if(strlen($name) > 100) return 'Name cannot exceed 100 characters';
    }

    if(isset($input['age'])){
        $age = $input['age'];
        if (!is_numeric($age)) return 'Age must be a number';
        if ($age < 0 || $age > 150) return 'Age must be a valid number between 0 and 150';
    }

    if(isset($input['email'])){  
        $email = trim($input['email']);
        if ($email === '') return 'Email cannot be empty';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return 'Invalid email format';
    }
    return null;
}