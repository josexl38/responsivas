<?php
// Sistema de manejo de errores y logging
class ErrorHandler {
    private static $logFile = 'logs/system_errors.log';
    private static $maxLogSize = 5242880; // 5MB
    
    public static function init() {
        // Crear directorio de logs si no existe
        if (!file_exists('logs')) {
            mkdir('logs', 0755, true);
        }
        
        // Configurar manejo de errores personalizado
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleFatalError']);
    }
    
    public static function handleError($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        
        $errorTypes = [
            E_ERROR => 'ERROR',
            E_WARNING => 'WARNING',
            E_PARSE => 'PARSE ERROR',
            E_NOTICE => 'NOTICE',
            E_CORE_ERROR => 'CORE ERROR',
            E_CORE_WARNING => 'CORE WARNING',
            E_COMPILE_ERROR => 'COMPILE ERROR',
            E_COMPILE_WARNING => 'COMPILE WARNING',
            E_USER_ERROR => 'USER ERROR',
            E_USER_WARNING => 'USER WARNING',
            E_USER_NOTICE => 'USER NOTICE',
            E_STRICT => 'STRICT NOTICE',
            E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
            E_DEPRECATED => 'DEPRECATED',
            E_USER_DEPRECATED => 'USER DEPRECATED'
        ];
        
        $errorType = isset($errorTypes[$severity]) ? $errorTypes[$severity] : 'UNKNOWN';
        
        self::logError($errorType, $message, $file, $line);
        
        // No mostrar errores en producción
        return true;
    }
    
    public static function handleException($exception) {
        self::logError(
            'EXCEPTION',
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
    }
    
    public static function handleFatalError() {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            self::logError('FATAL ERROR', $error['message'], $error['file'], $error['line']);
        }
    }
    
    private static function logError($type, $message, $file, $line, $trace = null) {
        $timestamp = date('Y-m-d H:i:s');
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $uri = $_SERVER['REQUEST_URI'] ?? 'Unknown';
        
        $logEntry = "[{$timestamp}] {$type}: {$message} in {$file} on line {$line}\n";
        $logEntry .= "IP: {$ip} | URI: {$uri} | User-Agent: {$userAgent}\n";
        
        if ($trace) {
            $logEntry .= "Stack Trace:\n{$trace}\n";
        }
        
        $logEntry .= str_repeat('-', 80) . "\n";
        
        // Rotar log si es muy grande
        if (file_exists(self::$logFile) && filesize(self::$logFile) > self::$maxLogSize) {
            self::rotateLog();
        }
        
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    private static function rotateLog() {
        $backupFile = 'logs/system_errors_' . date('Y-m-d_H-i-s') . '.log';
        rename(self::$logFile, $backupFile);
        
        // Mantener solo los últimos 5 archivos de backup
        $logFiles = glob('logs/system_errors_*.log');
        if (count($logFiles) > 5) {
            usort($logFiles, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            for ($i = 0; $i < count($logFiles) - 5; $i++) {
                unlink($logFiles[$i]);
            }
        }
    }
    
    public static function logCustom($level, $message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        
        $logEntry = "[{$timestamp}] {$level}: {$message}";
        if ($contextStr) {
            $logEntry .= " | Context: {$contextStr}";
        }
        $logEntry .= "\n";
        
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
}

// Inicializar el sistema de manejo de errores
ErrorHandler::init();
?>