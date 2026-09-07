<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? '8ou80 Shop'; ?></title>
    
    <!-- CARREGAMENTO INSTANTÂNEO: Puxando o CSS através da nova classe AppConfig -->
    <link rel="stylesheet" href="<?= \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container">
        <span class="badge-version">8ou80.site v2.0</span>
        
        <h1 class="main-title">Infraestrutura MVC Concluída!</h1>
        
        <p class="description-text">
            Seu novo ecossistema está operando em padrão de engenharia profissional <strong>PSR-4</strong>. O banco de dados está conectado e o roteador inteligente está pronto para rodar tanto localmente quanto na Hostinger de forma autônoma.
        </p>

        <div class="button-group">
            <!-- Corrigido: Chamando \App\Config\AppConfig de forma limpa -->
            <a href="<?= \App\Config\AppConfig::url('/profissionais'); ?>" class="btn-action btn-primary">
                🔍 Buscar Profissionais
            </a>
            <a href="<?= \App\Config\AppConfig::url('/login'); ?>" class="btn-action btn-secondary">
                Acessar Painel 🔒
            </a>
        </div>
    </div>

</body>
</html>
