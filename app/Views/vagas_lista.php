<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container" style="max-width: 900px; text-align: left;">
        <span class="badge-version">Mural de Oportunidades</span>
        <h1 class="main-title" style="text-align: center;">Projetos Disponíveis</h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 30px;">
            <?php if (empty($vagas)): ?>
                <p style="grid-column: 1/-1; text-align: center; color: #64748b;">Nenhuma vaga aberta no momento.</p>
            <?php else: ?>
                <?php foreach ($vagas as $v): ?>
                    <div style="border: 1px solid #e2e8f0; background: #ffffff; padding: 20px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <span style="background: #e0e7ff; color: #4f46e5; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: bold;"><?php echo $v['category']; ?></span>
                            <h3 style="margin: 10px 0 5px 0; font-size: 18px; color: #1e1b4b;"><?php echo $v['title']; ?></h3>
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #64748b;">Postado por: <?php echo $v['nome_cliente']; ?></p>
                            <p style="font-size: 13px; color: #475569; line-height: 1.4; margin-bottom: 15px;">
                                <?php echo mb_strimwidth($v['description'], 0, 100, "..."); ?>
                            </p>
                        </div>
                        <div>
                            <p style="font-size: 14px; font-weight: bold; color: #16a34a; margin-bottom: 15px;">
                                Orçamento: <?php echo $v['budget'] ? 'R$ ' . number_format($v['budget'], 2, ',', '.') : 'A combinar'; ?>
                            </p>
                            <a href="<?php echo \App\Config\AppConfig::url('/vagas/detalhes?id=' . $v['id']); ?>" class="btn-action btn-primary" style="width: 100%; text-align: center; font-size: 13px; padding: 8px;">Ver e Candidatar-se</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="<?php echo \App\Config\AppConfig::url('/dashboard'); ?>" style="color: #4f46e5; text-decoration: none; font-weight: 600;">← Voltar ao Painel</a>
        </div>
    </div>

</body>
</html>
