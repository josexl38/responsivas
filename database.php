<?php
// Clase para manejo optimizado de base de datos
class Database {
    private static $instance = null;
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;
    
    private function __construct() {
        include 'config.php';
        $this->host = $dbhost;
        $this->username = $dbuser;
        $this->password = $dbpass;
        $this->database = $dbname;
        
        $this->connect();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect() {
        try {
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->connection->connect_error) {
                throw new Exception("Error de conexión: " . $this->connection->connect_error);
            }
            
            // Configurar charset
            $this->connection->set_charset("utf8");
            
            // Configurar modo SQL estricto
            $this->connection->query("SET sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
            
        } catch (Exception $e) {
            ErrorHandler::logCustom('ERROR', 'Error de conexión a base de datos: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function getConnection() {
        // Verificar si la conexión sigue activa
        if (!$this->connection->ping()) {
            $this->connect();
        }
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        try {
            $conn = $this->getConnection();
            
            if (!empty($params)) {
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Error preparando consulta: " . $conn->error);
                }
                
                if (!empty($params)) {
                    $types = str_repeat('s', count($params));
                    $stmt->bind_param($types, ...$params);
                }
                
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                
                return $result;
            } else {
                $result = $conn->query($sql);
                if (!$result) {
                    throw new Exception("Error en consulta: " . $conn->error);
                }
                return $result;
            }
            
        } catch (Exception $e) {
            ErrorHandler::logCustom('ERROR', 'Error en consulta SQL: ' . $e->getMessage(), [
                'sql' => $sql,
                'params' => $params
            ]);
            throw $e;
        }
    }
    
    public function insert($table, $data) {
        try {
            $columns = implode(',', array_keys($data));
            $placeholders = str_repeat('?,', count($data) - 1) . '?';
            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
            
            $stmt = $this->getConnection()->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando inserción: " . $this->getConnection()->error);
            }
            
            $types = str_repeat('s', count($data));
            $stmt->bind_param($types, ...array_values($data));
            
            $result = $stmt->execute();
            $insertId = $this->getConnection()->insert_id;
            $stmt->close();
            
            if ($result) {
                ErrorHandler::logCustom('INFO', "Registro insertado en {$table}", ['id' => $insertId]);
                return $insertId;
            } else {
                throw new Exception("Error ejecutando inserción");
            }
            
        } catch (Exception $e) {
            ErrorHandler::logCustom('ERROR', 'Error en inserción: ' . $e->getMessage(), [
                'table' => $table,
                'data' => $data
            ]);
            throw $e;
        }
    }
    
    public function update($table, $data, $where, $whereParams = []) {
        try {
            $setParts = [];
            foreach (array_keys($data) as $column) {
                $setParts[] = "{$column} = ?";
            }
            $setClause = implode(', ', $setParts);
            
            $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
            
            $stmt = $this->getConnection()->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando actualización: " . $this->getConnection()->error);
            }
            
            $allParams = array_merge(array_values($data), $whereParams);
            $types = str_repeat('s', count($allParams));
            $stmt->bind_param($types, ...$allParams);
            
            $result = $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            
            if ($result) {
                ErrorHandler::logCustom('INFO', "Registro actualizado en {$table}", ['affected_rows' => $affectedRows]);
                return $affectedRows;
            } else {
                throw new Exception("Error ejecutando actualización");
            }
            
        } catch (Exception $e) {
            ErrorHandler::logCustom('ERROR', 'Error en actualización: ' . $e->getMessage(), [
                'table' => $table,
                'data' => $data,
                'where' => $where
            ]);
            throw $e;
        }
    }
    
    public function delete($table, $where, $whereParams = []) {
        try {
            $sql = "DELETE FROM {$table} WHERE {$where}";
            
            $stmt = $this->getConnection()->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando eliminación: " . $this->getConnection()->error);
            }
            
            if (!empty($whereParams)) {
                $types = str_repeat('s', count($whereParams));
                $stmt->bind_param($types, ...$whereParams);
            }
            
            $result = $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            
            if ($result) {
                ErrorHandler::logCustom('INFO', "Registro eliminado de {$table}", ['affected_rows' => $affectedRows]);
                return $affectedRows;
            } else {
                throw new Exception("Error ejecutando eliminación");
            }
            
        } catch (Exception $e) {
            ErrorHandler::logCustom('ERROR', 'Error en eliminación: ' . $e->getMessage(), [
                'table' => $table,
                'where' => $where
            ]);
            throw $e;
        }
    }
    
    public function beginTransaction() {
        $this->getConnection()->autocommit(false);
        ErrorHandler::logCustom('INFO', 'Transacción iniciada');
    }
    
    public function commit() {
        $this->getConnection()->commit();
        $this->getConnection()->autocommit(true);
        ErrorHandler::logCustom('INFO', 'Transacción confirmada');
    }
    
    public function rollback() {
        $this->getConnection()->rollback();
        $this->getConnection()->autocommit(true);
        ErrorHandler::logCustom('INFO', 'Transacción revertida');
    }
    
    public function escape($string) {
        return $this->getConnection()->real_escape_string($string);
    }
    
    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>