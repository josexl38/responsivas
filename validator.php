<?php
// Clase para validación avanzada de datos
class Validator {
    private $errors = [];
    private $data = [];
    
    public function __construct($data = []) {
        $this->data = $data;
        $this->errors = [];
    }
    
    public function validate($field, $rules) {
        $value = isset($this->data[$field]) ? $this->data[$field] : null;
        
        foreach ($rules as $rule) {
            $this->applyRule($field, $value, $rule);
        }
        
        return $this;
    }
    
    private function applyRule($field, $value, $rule) {
        $parts = explode(':', $rule);
        $ruleName = $parts[0];
        $parameter = isset($parts[1]) ? $parts[1] : null;
        
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "El campo {$field} es requerido");
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "El campo {$field} debe ser un email válido");
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "El campo {$field} debe ser numérico");
                }
                break;
                
            case 'min_length':
                if (!empty($value) && strlen($value) < $parameter) {
                    $this->addError($field, "El campo {$field} debe tener al menos {$parameter} caracteres");
                }
                break;
                
            case 'max_length':
                if (!empty($value) && strlen($value) > $parameter) {
                    $this->addError($field, "El campo {$field} no puede tener más de {$parameter} caracteres");
                }
                break;
                
            case 'nomina':
                if (!empty($value) && (!is_numeric($value) || strlen($value) < 4)) {
                    $this->addError($field, "La nómina debe contener solo números y mínimo 4 dígitos");
                }
                break;
                
            case 'unique':
                if (!empty($value)) {
                    $parts = explode(',', $parameter);
                    $table = $parts[0];
                    $column = isset($parts[1]) ? $parts[1] : $field;
                    $excludeId = isset($parts[2]) ? $parts[2] : null;
                    
                    if ($this->checkUnique($table, $column, $value, $excludeId)) {
                        $this->addError($field, "El valor del campo {$field} ya existe");
                    }
                }
                break;
                
            case 'exists':
                if (!empty($value)) {
                    $parts = explode(',', $parameter);
                    $table = $parts[0];
                    $column = isset($parts[1]) ? $parts[1] : $field;
                    
                    if (!$this->checkExists($table, $column, $value)) {
                        $this->addError($field, "El valor del campo {$field} no existe");
                    }
                }
                break;
        }
    }
    
    private function checkUnique($table, $column, $value, $excludeId = null) {
        try {
            $db = Database::getInstance();
            $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
            $params = [$value];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $result = $db->query($sql, $params);
            $row = $result->fetch_assoc();
            
            return $row['count'] > 0;
        } catch (Exception $e) {
            ErrorHandler::logCustom('ERROR', 'Error verificando unicidad: ' . $e->getMessage());
            return false;
        }
    }
    
    private function checkExists($table, $column, $value) {
        try {
            $db = Database::getInstance();
            $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
            $result = $db->query($sql, [$value]);
            $row = $result->fetch_assoc();
            
            return $row['count'] > 0;
        } catch (Exception $e) {
            ErrorHandler::logCustom('ERROR', 'Error verificando existencia: ' . $e->getMessage());
            return false;
        }
    }
    
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
    
    public function fails() {
        return !empty($this->errors);
    }
    
    public function passes() {
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    public function getFirstError($field = null) {
        if ($field) {
            return isset($this->errors[$field]) ? $this->errors[$field][0] : null;
        }
        
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0];
        }
        
        return null;
    }
    
    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        
        // Limpiar caracteres peligrosos
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        
        return $data;
    }
    
    public static function sanitizeForDatabase($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeForDatabase'], $data);
        }
        
        $db = Database::getInstance();
        return $db->escape($data);
    }
}
?>