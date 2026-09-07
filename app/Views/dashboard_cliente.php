<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container" style="max-width: 800px;">
        <span class="badge-version" style="background-color: #dbeafe; color: #1e40af;">Painel do Contratante 📋</span>
        
        <h1 class="main-title">Olá, <?php echo $_SESSION['user_name']; ?>!</h1>
        <p class="description-text">Gerencie suas vagas abertas e encontre os melhores desenvolvedores do mercado.</p>

        <!-- 🔄 INTERRUPTOR DE PERFIL INTEGRADO -->
        <div style="margin-bottom: 30px; padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
            <?php if ($_SESSION['is_professional']): ?>
                <p style="font-size: 14px; margin-bottom: 10px; color: #64748b;">Você também é um Freelancer cadastrado.</p>
                <a href="<?php echo \App\Config\AppConfig::url('/dashboard/alternar?tipo=freelancer'); ?>" class="btn-action btn-secondary" style="font-size: 13px; padding: 8px 16px;">
                    🔄 Alternar para Painel Freelancer
                </a>
            <?php else: ?>
                <p style="font-size: 14px; margin-bottom: 10px; color: #64748b;">Quer trabalhar na plataforma e receber por projetos?</p>
                <a href="<?php echo \App\Config\AppConfig::url('/profissional/completar-perfil'); ?>" class="btn-action btn-primary" style="font-size: 13px; padding: 8px 16px; background-color: #16a34a; box-shadow: none;">🚀 Completar Perfil Freelancer</a>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <a href="#" class="btn-action btn-primary">➕ Publicar Nova Vaga</a>
            <a href="<?php echo \App\Config\AppConfig::url('/sair'); ?>" class="btn-action btn-secondary" style="color: #dc3545;">Sair da Conta</a>
        </div>
    </div>

</body>
</html>
