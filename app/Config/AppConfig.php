<?php

namespace App\Config;

class AppConfig {
    /**
     * Gera links dinâmicos e absolutos que funcionam em qualquer ambiente
     */
    public static function url(string $path = ''): string {
        $baseUrl = $_ENV['APP_URL'] ?? $_SERVER['APP_URL'] ?? null;
        
        if (!$baseUrl) {
            $baseUrl = 'http://localhost/new-freela';
        }
        
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}
