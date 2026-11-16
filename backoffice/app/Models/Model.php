<?php

namespace App\Models;

use PDO;

/**
 * Classe de base des modèles : connexion PDO partagée.
 * Les paramètres de connexion sont dans config/database.php.
 */
abstract class Model
{
    private static ?PDO $pdo = null;

    protected static function db(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../../config/database.php';
            self::$pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                $config['user'],
                $config['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        }
        return self::$pdo;
    }
}
