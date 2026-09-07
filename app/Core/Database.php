<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    // Construtor privado impede que a classe seja instanciada com "new Database()" de fora
    private function __construct() {}

    /**
     * Retorna a instância única da conexão com o banco de dados (Design Pattern Singleton)
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                // Captura as variáveis de ambiente carregadas pelo Dotenv
                $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                $port = $_ENV['DB_PORT'] ?? '3306';
                $dbname = $_ENV['DB_NAME'] ?? 'freela_db'; // 👈 Corrigido de 'freela db' para 'freela_db'

                $username = $_ENV['DB_USER'] ?? 'root';
                $password = $_ENV['DB_PASS'] ?? '';

                // Configura o DSN do PDO
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

                // Opções recomendadas para segurança e tratamento de erros profissionais
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Transforma erros do MySQL em exceções PHP capturáveis
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna dados do banco como arrays associativos por padrão
                    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desativa a emulação para segurança real contra SQL Injection
                ];

                self::$instance = new PDO($dsn, $username, $password, $options);

            } catch (PDOException $e) {
                // Em produção, salve isso em um arquivo de log. Em desenvolvimento, exibe na tela.
                http_response_code(500);
                die("<h1>🚫 Erro Crítico de Conexão com o Banco de Dados</h1><p>{$e->getMessage()}</p>");
            }
        }

        return self::$instance;
    }

    // Impede a clonagem da classe por segurança
    private function __clone() {}
}
