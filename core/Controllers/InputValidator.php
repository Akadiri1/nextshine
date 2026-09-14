<?php

class InputValidator {
    private $errors = [];
    private $messages = []; // Store custom messages for each field

    // Adds custom error message with optional dynamic field name
    public function withMessage($field, $message) {
        $this->messages[$field] = $message;
        return $this;
    }

    public function securePassword($fieldValue, $field) {
        //password must contain at least one uppercase letter, one lowercase letter, one number, special character, and be at least 8 characters long
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/", $fieldValue)) {
            $this->addError($field, $this->messages[$field] ?? "$field must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long");
        }
        return $this;
    }

    // Check if field is not empty
    public function notEmpty($fieldValue, $field) {
        if (empty(trim($fieldValue))) {
            $this->addError($field, $this->messages[$field] ?? "$field cannot be empty");
        }
        return $this;
    }

    // Check if field is a number
    public function isNumber($fieldValue, $field) {
        if (!is_numeric($fieldValue)) {
            $this->addError($field, $this->messages[$field] ?? "$field must be a number");
        }
        return $this;
    }

    // Check if field is a valid email
    public function isEmail($fieldValue, $field) {
        if (!filter_var($fieldValue, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $this->messages[$field] ?? "$field must be a valid email");
        }
        return $this;
    }

    // Check if field has a minimum length
    public function minLength($fieldValue, $minLength, $field) {
        if (strlen($fieldValue) < $minLength) {
            $this->addError($field, $this->messages[$field] ?? "$field must be at least $minLength characters long");
        }
        return $this;
    }

    // Check if field has a maximum length
    public function maxLength($fieldValue, $maxLength, $field) {
        if (strlen($fieldValue) > $maxLength) {
            $this->addError($field, $this->messages[$field] ?? "$field cannot exceed $maxLength characters");
        }
        return $this;
    }

    // Check if field is a valid date (YYYY-MM-DD)
    public function isDate($fieldValue, $field) {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fieldValue)) {
            $this->addError($field, $this->messages[$field] ?? "$field must be a valid date (YYYY-MM-DD)");
        }
        return $this;
    }

    // Check if field value is within a specific set of values
    public function isIn($fieldValue, array $values, $field) {
        if (!in_array($fieldValue, $values)) {
            $this->addError($field, $this->messages[$field] ?? "$field must be one of the following values: " . implode(", ", $values));
        }
        return $this;
    }

    // Check if a field passes a custom validation function
    public function customValidation($fieldValue, callable $validationFn, $field) {
        if (!$validationFn($fieldValue)) {
            $this->addError($field, $this->messages[$field] ?? "$field failed custom validation");
        }
        return $this;
    }

    // Add error to the errors list
    private function addError($field, $errorMessage) {
        $this->errors[$field][] = $errorMessage;
    }

    // Get all errors
    public function getErrors() {
        return $this->errors;
    }

    // Check if the validation has passed
    public function isValid() {
        return empty($this->errors);
    }

    public function hasErrors() {
        return !$this->isValid();
    }
}
