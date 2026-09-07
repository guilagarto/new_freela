<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container" style="max-width: 700px; text-align: left;">
        <span class="badge-version"><?php echo $vaga['category']; ?></span>
        <h1 class="main-title" style="font-size: 28px;"><?php echo $vaga['title']; ?></h1>
        <p style="color: #64748b; font-size: 14px; margin-top: -10px; margin-bottom: 20px;">Publicado por: <strong><?php echo $vaga['nome_cliente']; ?></strong></p>

        <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
            <h3 style="margin-top: 0; color: #1e1b4b; font-size: 16px;">Descrição do Projeto:</h3>
            <p style="font-size: 14px; color: #475569; line-height: 1.6; white-space: pre-wrap;"><?php echo $vaga['description']; ?></p>
            <p style="font-size: 16px; font-weight: bold; color: #16a34a; margin-top: 15px; margin-bottom: 0;">
                Orçamento Estimado: <?php echo $vaga['budget'] ? 'R$ ' . number_format($vaga['budget'], 2, ',', '.') : 'A combinar'; ?>
            </p>
        </div>

        <h2 style="color: #1e1b4b; font-size: 20px; margin-bottom: 15px;">Enviar sua Proposta Comercial</h2>

        <?php if (isset($_SESSION['erro_proposta'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['erro_proposta']; unset($_SESSION['erro_proposta']); ?></div>
        <?php endif; ?>

        <form action="<?php echo \App\Config\AppConfig::url('/vagas/proposta'); ?>" method="POST" class="auth-form">
            <input type="hidden" name="job_id" value="<?php echo $vaga['id']; ?>">

            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label for="bid_amount">Seu Valor pelo Projeto (R$) *</label>
                    <input type="number" step="0.01" name="bid_amount" id="bid_amount" class="form-control" placeholder="0.00" required>
                </div>
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label for="delivery_days">Prazo de Entrega (Dias) *</label>
                    <input type="number" name="delivery_days" id="delivery_days" class="form-control" placeholder="Ex: 7" required>
                </div>
            </div>

            <div class="form-group">
                <label for="cover_letter">Carta de Apresentação / Proposta Técnica *</label>
                <textarea name="cover_letter" id="cover_letter" class="form-control" rows="5" placeholder="Explique por que você é o profissional ideal, quais tecnologias vai usar e como planeja entregar o escopo..." style="resize: none; font-family: inherit;" required></textarea>
            </div>

            <button type="submit" class="btn-action btn-primary" style="width: 100%; background-color: #8b5cf6; box-shadow: none;">Enviar Proposta ao Cliente</button>
        </form>

        <p class="auth-footer-link">
            <a href="<?php echo \App\Config\AppConfig::url('/vagas'); ?>">← Voltar ao Mural</a>
        </p>
    </div>

</body>
</html>
