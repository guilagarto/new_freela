<?php

namespace App\Core;

class Database {
    private static ?\PDO $instance = null;

    // Construtor privado impede instanciação externa
    private function __construct() {}

    /**
     * Retorna a instância única da conexão com o banco de dados (Singleton)
     */
    public static function getInstance(): \PDO {
        if (self::$instance === null) {
            try {
                // Captura do $_ENV ou getenv para compatibilidade total local/web
                $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? '127.0.0.1';
                $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '3306';
                $dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? '';
                $username = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
                $password = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';

                if (empty($dbname)) {
                    http_response_code(500);
                    die("<h1>🚫 Erro Crítico: O nome do banco de dados está vazio no arquivo .env</h1>");
                }

                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

                $options = [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$instance = new \PDO($dsn, $username, $password, $options);

            } catch (\PDOException $e) { // 🚀 A MÁGICA: Barra invertida captura a exceção global do PDO
                http_response_code(500);
                echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
                echo "<h1 style='color:#dc3545;'>🚫 Falha de Conexão com o Banco de Dados</h1>";
                echo "<p style='color:#475569;'>Mensagem do Servidor: <strong>" . $e->getMessage() . "</strong></p>";
                echo "<p style='color:#64748b;'>Verifique se as credenciais do seu arquivo <strong>.env</strong> estão corretas.</p>";
                echo "</div>";
                exit; // Interrompe o script de forma absoluta, impedindo o retorno de 'null'
            }
        }

        return self::$instance;
    }

    private function __clone() {}
}
