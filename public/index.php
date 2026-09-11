<?php
// 1. Ativa a exibição de erros total na fase de desenvolvimento local (XAMPP/Linux)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Inicializa o Autoload clássico do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// 🚀 SOLUÇÃO INQUEBRÁVEL PARA LINUX: Carrega o arquivo físico do banco manualmente na inicialização
require_once __DIR__ . '/../app/Core/Database.php';

// 3. Carrega as variáveis de ambiente seguras do arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 4. Importa o arquivo de configurações globais do ecossistema
require_once __DIR__ . '/../app/Config/AppConfig.php';

require_once __DIR__ . '/../app/Controllers/ChatController.php';

// 5. Inicia a sessão global do PHP de forma limpa e segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 6. Importa os Namespaces das classes que serão utilizadas no roteamento
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController; // 👈 ESSENCIAL!
use App\Controllers\ProfessionalController;
use App\Controllers\JobController;
use App\Controllers\BlogController;

// 7. Instancia o roteador inteligente e autônomo
$router = new Router();

// 🚀 ROTAS DE VISUALIZAÇÃO (MÉTODO GET)
$router->get('/', [HomeController::class, 'index']);
$router->get('/teste-banco', [HomeController::class, 'testeBanco']);

$router->get('/login', [AuthController::class, 'mostrarLogin']);
$router->post('/login', [AuthController::class, 'autenticarUsuario']);
$router->get('/cadastrar', [AuthController::class, 'mostrarCadastro']);
$router->post('/cadastrar', [AuthController::class, 'cadastrarUsuario']);
$router->get('/sair', [AuthController::class, 'sair']);

// 🚀 ROTAS DE PROCESSAMENTO DE FORMULÁRIOS (MÉTODO POST)
$router->post('/login', [AuthController::class, 'autenticarUsuario']);
$router->post('/cadastrar', [AuthController::class, 'cadastrarUsuario']);


// 🚀 2. NOVAS ROTAS DO DASHBOARD E ALTERNÂNCIA
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/dashboard/alternar', [DashboardController::class, 'alternarPerfil']);

$router->get('/profissionais', [HomeController::class, 'profissionais']);

$router->get('/profissionais', [ProfessionalController::class, 'index']);
// Altere a linha 58 para apontar para 'filtrar' em vez de 'filtrarApi'
$router->get('/api/profissionais/filtrar', [ProfessionalController::class, 'filtrar']);

$router->get('/profissional/completar-perfil', [ProfessionalController::class, 'mostrarCompletarPerfil']);
$router->post('/profissional/completar-perfil', [ProfessionalController::class, 'salvarPerfilProfessional']);

$router->get('/vagas/publicar', [JobController::class, 'mostrarPublicar']);
$router->post('/vagas/publicar', [JobController::class, 'salvarVaga']);

$router->get('/vagas', [JobController::class, 'listarVagas']);
$router->get('/vagas/detalhes', [JobController::class, 'detalhesVaga']);
$router->post('/vagas/proposta', [JobController::class, 'salvarProposta']);

// 🚀 ROTAS UNIFICADAS DO DASHBOARD CENTRAL
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->post('/dashboard/perfil/atualizar', [DashboardController::class, 'atualizarPerfil']);

// 🗑️ GESTÃO DE VAGAS DO USUÁRIO
$router->get('/vagas/excluir', [JobController::class, 'excluirVaga']);

// 🚀 ROTA DO SISTEMA DE AVALIAÇÕES E RECOMPENSAS
$router->post('/vagas/avaliar', [JobController::class, 'avaliarTrabalho']);

// ... suas outras rotas acima ...

$router->get('/api/profissionais/filtrar', [ProfessionalController::class, 'filtrar']);

// GESTÃO DE AVALIAÇÕES E RECOMPENSAS
$router->post('/vagas/avaliar', [JobController::class, 'avaliarTrabalho']);

// =========================================================================
// ROTAS INSTITUCIONAIS, BLOG E CONTATO
// =========================================================================

// Rota da Home Oficial do seu site (aquela que já estava pronta)
$router->get('/', [HomeController::class, 'index']);

// Rota da Landing Page avulsa de captação (Campanhas de Anúncios)
$router->get('/descubra', [HomeController::class, 'landingPage']);

// Rota para abrir a página do Blog (Notícias)
$router->get('/blog', [BlogController::class, 'index']);

// Rota para abrir a página de Formulário de Contato
$router->get('/contato', [HomeController::class, 'contato']);

// Rota para processar o envio dos dados do formulário de contato (POST)
$router->post('/contato/enviar', [HomeController::class, 'enviarContato']);
// Rota para abrir a página de Formulário de Contato (GET)
$router->get('/contato', [HomeController::class, 'contato']);

// 1. ROTA PADRÃO (Para quando estiver na Hostinger)
$router->post('/contato/enviar', [HomeController::class, 'enviarContato']);


// Rotas institucionais do Rodapé
$router->get('/politica-de-privacidade', [HomeController::class, 'politicaPrivacidade']);
$router->get('/termos-de-uso', [HomeController::class, 'termosDeUso']);

$router->get('/chat', [ChatController::class, 'index']);
// Altere de $router->get para $router->post:
$router->post('/chat/buscar', [ChatController::class, 'buscarMensagens']);

$router->post('/chat/enviar', [ChatController::class, 'enviar']);

$router->post('/chat/usuarios', [ChatController::class, 'listarUsuarios']);

// Deixe sempre o resolve por último
$router->resolve();




