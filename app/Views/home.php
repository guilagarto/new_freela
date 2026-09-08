<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>
<?php include __DIR__ . '/partials/header.php'; ?>

    <div class="welcome-container" style="max-width: 850px;">
        <span class="sub-badge">A plataforma do trabalhador autônomo</span>
        <h1 class="main-title" style="font-size: 38px; margin-top: 10px;">Precisa de um <span style="color: #4f46e5;">Profissional Liberal?</span></h1>
        <p class="description-text" style="max-width: 600px; margin: 0 auto 25px auto;">
            Encontre profissionais qualificados perto de você ou cadastre seus serviços Freelancers de forma simples, rápida e segura.
        </p>

        <!-- 📊 Indicadores reais extraídos do banco de dados -->
        <div class="stats-grid">
            <div class="stat-item">
                <strong><?php echo $totalVagas; ?></strong> Vagas Abertas
            </div>
            <div style="width: 1px; background: #cbd5e1; margin: 0 15px;"></div>
            <div class="stat-item">
                <strong><?php echo $totalFreelas; ?></strong> Freelancers
            </div>
        </div>

        <!-- Botões principais de tomada de ação imediata -->
        <div class="button-group" style="margin-bottom: 40px;">
            <a href="<?php echo \App\Config\AppConfig::url('/profissionais'); ?>" class="btn-action btn-primary">
                🔍 Encontrar Profissional
            </a>
            <a href="<?php echo \App\Config\AppConfig::url('/login'); ?>" class="btn-action btn-secondary" style="background: #ffffff; border: 1px solid #cbd5e1;">
                💼 Quero Oferecer Serviços
            </a>
        </div>

        <!-- 🧰 Seção de Categorias Dinâmicas vindas do Banco -->
        <?php if (!empty($categoriesPopulares)): ?>
            <h2 class="categories-title">Categorias Populares</h2>
            <div class="categories-grid-limpo">
                <?php foreach ($categoriesPopulares as $cat): ?>
                    <div class="category-card-limpo" onclick="window.location.href='<?php echo \App\Config\AppConfig::url('/profissionais?tecnologia=' . $cat['category']); ?>'">
                        💼 <?php echo $cat['category']; ?> 
                        <span style="font-size: 11px; color: #64748b; font-weight: normal;">(<?php echo $cat['total_vagas']; ?>)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 📋 Últimas vagas publicadas em tempo real -->
        <?php if (!empty($vagasRecentes)): ?>
            <h2 class="categories-title" style="margin-top: 20px;">Últimas Vagas Postadas</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 15px; text-align: left; margin-bottom: 20px;">
                <?php foreach ($vagasRecentes as $job): ?>
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; padding: 15px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <span style="background: #f1f5f9; color: #475569; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px;"><?php echo $job['category']; ?></span>
                            <h4 style="margin: 8px 0 4px 0; color: #1e1b4b; font-size: 15px; font-weight: 700;"><?php echo $job['title']; ?></h4>
                            <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.4;">
                                <?php echo mb_strimwidth($job['description'], 0, 75, "..."); ?>
                            </p>
                        </div>
                        <a href="<?php echo \App\Config\AppConfig::url('/login'); ?>" style="color: #4f46e5; font-size: 13px; font-weight: 600; text-decoration: none; display: block; margin-top: 12px;">Ver escopo →</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<!-- Última linha do arquivo home.php -->
<?php include __DIR__ . '/partials/footer.php'; ?>

