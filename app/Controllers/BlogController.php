<?php

namespace App\Controllers;

class BlogController {

    /**
     * Renderiza a listagem de artigos do Blog
     */
    public function index(): void {
        // Carrega a visualização do blog que você já tem criada na pasta Views
        require_once __DIR__ . '/../Views/blog.php';
    }
}
