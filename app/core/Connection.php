<?php

class Connection
{
    private static $instance = null;

    private function __construct(){}

    public static function getInstance()
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=nombredb_primerapellido;charset=utf8',
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die('Error de conexión: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
