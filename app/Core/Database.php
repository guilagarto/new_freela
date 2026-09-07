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
           // Procure o bloco try { ... } dentro do seu Database.php e substitua as variáveis por estas:
try {
    // Busca direto do superglobal $_ENV ou do getenv() nativo do Linux
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? '127.0.0.1';
    $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '3306';
    $dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? ''; // Remove o fallback rígido
    $username = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
    $password = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '';

    // Se por acaso as variáveis vierem vazias, interrompe com aviso claro antes de quebrar o PDO
    if (empty($dbname)) {
        die("<h1>🚫 Erro Crítico: O nome do banco de dados está vázio no arquivo .env</h1>");
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";


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
