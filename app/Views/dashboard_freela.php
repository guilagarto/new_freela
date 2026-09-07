<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container" style="max-width: 800px; border-color: #fef08a;">
        <span class="badge-version" style="background-color: #fef9c3; color: #854d0e;">Painel do Freelancer 🛠️</span>
        
        <h1 class="main-title">Olá, <?php echo $_SESSION['user_name']; ?>!</h1>
        <p class="description-text">Monitore suas propostas enviadas, gerencie seu portfólio e escale seus ganhos digitais.</p>

        <!-- 🔄 INTERRUPTOR DE PERFIL INTEGRADO -->
        <div style="margin-bottom: 30px; padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
            <p style="font-size: 14px; margin-bottom: 10px; color: #64748b;">Precisa delegar tarefas ou contratar outros profissionais?</p>
            <a href="<?php echo \App\Config\AppConfig::url('/dashboard/alternar?tipo=cliente'); ?>" class="btn-action btn-secondary" style="font-size: 13px; padding: 8px 16px;">
                🔄 Alternar para Painel Contratante
            </a>
        </div>

        <div class="button-group">
            <a href="#" class="btn-action btn-primary" style="background-color: #8b5cf6; box-shadow: 0 4px 14px rgba(139, 92, 246, 0.3);">💼 Procurar Projetos</a>
            <a href="<?php echo \App\Config\AppConfig::url('/sair'); ?>" class="btn-action btn-secondary" style="color: #dc3545;">Sair da Conta</a>
        </div>
    </div>

</body>
</html>
