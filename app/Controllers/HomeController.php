<?php

namespace App\Controllers;

// Importações oficiais limpas via Autoload PSR-4
use App\Config\AppConfig;
use App\Core\Database;

class HomeController {

    /**
     * Renderiza a página inicial do ecossistema
     */
       /**
     * Renderiza a página inicial pública e dinâmica do ecossistema
     */
        /**
     * Renderiza a página inicial pública e dinâmica do ecossistema
     */
    public function index(): void {
        $titulo = "8ou80 | Encontre Profissionais Liberais e Vagas Freelancer";
        
        try {
            $db = \App\Core\Database::getInstance();
            
            // 📊 1. Busca os contadores reais
            $stmtVagas = $db->query("SELECT COUNT(*) as total FROM jobs WHERE status = 'aberto'");
            $totalVagas = $stmtVagas->fetch()['total'] ?? 0;

            $stmtFreelas = $db->query("SELECT COUNT(*) as total FROM professional_profiles");
            $totalFreelas = $stmtFreelas->fetch()['total'] ?? 0;

            // 🧰 2. BUSCA AS CATEGORIAS REAIS DO BANCO (Mágica Dinâmica)
            // Seleciona as categorias distintas que possuem vagas abertas no momento
            $stmtCat = $db->query("SELECT category, COUNT(*) as total_vagas 
                                   FROM jobs 
                                   WHERE status = 'aberto' 
                                   GROUP BY category 
                                   ORDER BY total_vagas DESC LIMIT 4");
            $categoriesPopulares = $stmtCat->fetchAll(\PDO::FETCH_ASSOC);

            // 💼 3. Traz as 3 vagas mais recentes
            $stmtMural = $db->query("SELECT j.*, u.name as nome_cliente 
                                     FROM jobs j 
                                     INNER JOIN users u ON j.user_id = u.id 
                                     WHERE j.status = 'aberto' 
                                     ORDER BY j.id DESC LIMIT 3");
            $vagasRecentes = $stmtMural->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            $totalVagas = 0;
            $totalFreelas = 0;
            $categoriesPopulares = [];
            $vagasRecentes = [];
        }

        $viewPath = __DIR__ . '/../Views/home.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }



    /**
     * Testa a conexão real com o banco de dados
     */
    public function testeBanco(): void {
        try {
            // Puxa a conexão única via Singleton gerenciada pelo Autoload
            $db = Database::getInstance();
            
            // Realiza uma consulta simples para contar os dados existentes
            $stmt = $db->query("SELECT COUNT(*) as total FROM users");
            $resultado = $stmt->fetch();
            
            echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
            echo "<h1 style='color:#16a34a;'>✅ Conexão com o Banco bem-sucedida!</h1>";
            echo "<p style='color:#475569;'>O sistema leu a tabela <strong>users</strong> do banco <strong>freela_db</strong>.</p>";
            echo "<p style='color:#64748b;'>Total de registros encontrados: <strong>" . $resultado['total'] . "</strong></p>";
            echo "<a href='" . AppConfig::url('/') . "' style='color:#4f46e5; text-decoration:none; font-weight:bold;'>← Voltar para a Home</a>";
            echo "</div>";
        } catch (\Exception $e) {
            echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
            echo "<h1 style='color:#dc3545;'>❌ Falha ao ler dados:</h1>";
            echo "<p style='color:#475569;'>" . $e->getMessage() . "</p>";
            echo "</div>";
        }
    }
        /**
     * Exibe a listagem de profissionais (Rota temporária)
     */
    public function profissionais(): void {
        echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
        echo "<h1 style='color:#4f46e5;'>🔍 Página de Busca de Profissionais</h1>";
        echo "<p style='color:#64748b;'>Esta rota foi ativada com sucesso! Logo mais criaremos os filtros dinâmicos aqui.</p>";
        echo "<a href='" . \App\Config\AppConfig::url('/') . "' style='color:#334155; font-weight:bold;'>← Voltar para a Home</a>";
        echo "</div>";
    }

    /**
 * Renderiza a Landing Page avulsa de captação (Campanhas Externas)
 */
public function landingPage(): void {
    // Carrega o arquivo diretamente, ignorando o header e footer globais do sistema
    require_once __DIR__ . '/../Views/landing_page.php';
    exit;
}

    /**
     * Renderiza a Página de Contato Institucional
     */
    public function contato(): void {
        // Garanta que o nome do arquivo aqui esteja idêntico ao da pasta Views
        require_once __DIR__ . '/../Views/contato.php';
    }
        /**
     * Renderiza a página de Política de Privacidade
     */
     /**
     * Renderiza a página de Política de Privacidade
     */
    public function politicaPrivacidade(): void {
        require_once __DIR__ . '/../Views/politica.php';
    }

    /**
     * Renderiza a página de Termos de Uso
     */
    public function termosDeUso(): void {
        require_once __DIR__ . '/../Views/termos.php';
    }
        /**
     * Processa o formulário de contato e envia as notificações por e-mail
     */
    public function enviarContato(): void {
        // 1. Limpa qualquer saída residual para não corromper os cabeçalhos
        if (ob_get_length()) ob_clean();

        // 2. Captura e limpa os campos recebidos via POST
        $nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_DEFAULT);

        // Se houver qualquer campo inválido ou vazio, retorna para a tela com aviso
        if (!$nome || !$email || !$mensagem) {
            header('Location: ' . \App\Config\AppConfig::url('/contato?erro=campos_invalidos'));
            exit;
        }

        $nome = trim($nome);
        $mensagem = trim($mensagem);

        try {
            // =========================================================================
            // CONFIGURAÇÕES DE E-MAIL (ADAPTE COM SEUS DADOS)
            // =========================================================================
            
            // Em produção (Hostinger), use contas reais do seu próprio domínio
            $emailRemetente = "contato@talentohub.com.br"; 
            $emailAdmin = "admin@talentohub.com.br";

            // Configuração dos cabeçalhos padrões para e-mail em formato HTML e UTF-8
            $headersBase = "MIME-Version: 1.0\r\n";
            $headersBase .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headersBase .= "From: TalentoHub <" . $emailRemetente . ">\r\n";

            // -------------------------------------------------------------------------
            // DISPARO 1: NOTIFICAÇÃO PARA O ADMINISTRADOR (ALERTA)
            // -------------------------------------------------------------------------
            $assuntoAdmin = "📥 Nova mensagem recebida no Fale Conosco";
            $corpoAdmin = "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #1e1b4b;'>
                    <h2 style='color: #4f46e5;'>Olá, Administrador!</h2>
                    <p>Um usuário enviou uma nova mensagem através do site.</p>
                    <hr style='border: 1px solid #e2e8f0; margin: 20px 0;'>
                    <p><b>Nome:</b> {$nome}</p>
                    <p><b>E-mail:</b> {$email}</p>
                    <p><b>Mensagem:</b></p>
                    <div style='background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; font-style: italic;'>
                        " . nl2br(htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8')) . "
                    </div>
                </div>
            ";
            
            // Dispara apenas se não estiver em ambiente local (localhost muitas vezes não tem sendmail ativo)
            if ($_SERVER['HTTP_HOST'] !== 'localhost') {
                mail($emailAdmin, $assuntoAdmin, $corpoAdmin, $headersBase);
            }

            // -------------------------------------------------------------------------
            // DISPARO 2: CONFIRMAÇÃO PARA O USUÁRIO (CÓPIA)
            // -------------------------------------------------------------------------
            $assuntoUsuario = "🚀 Recebemos sua mensagem! - TalentoHub";
            $corpoUsuario = "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #1e1b4b;'>
                    <h2 style='color: #4f46e5;'>Olá, {$nome}!</h2>
                    <p>Confirmamos que a sua mensagem foi recebida com sucesso pela nossa central de suporte. Responderemos diretamente neste e-mail em até 24 horas úteis.</p>
                </div>
            ";
            
            if ($_SERVER['HTTP_HOST'] !== 'localhost') {
                mail($email, $assuntoUsuario, $corpoUsuario, $headersBase);
            }

            // Redireciona de volta informando o sucesso
            header('Location: ' . \App\Config\AppConfig::url('/contato?sucesso=1'));

        } catch (Exception $e) {
            error_log("Erro ao processar envio de contato: " . $e->getMessage());
            header('Location: ' . \App\Config\AppConfig::url('/contato?erro=falha_servidor'));
        }
        exit;
    }





}
