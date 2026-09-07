<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container" style="max-width: 650px;">
        <span class="badge-version" style="background-color: #dbeafe; color: #1e40af;">Demanda de Projeto 📋</span>
        <h1 class="main-title">Publicar Nova Vaga</h1>
        <p class="description-text">Descreva detalhadamente o que você precisa para atrair os melhores profissionais.</p>

        <?php if (isset($_SESSION['erro_vaga'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['erro_vaga']; unset($_SESSION['erro_vaga']); ?></div>
        <?php endif; ?>

        <form action="<?php echo \App\Config\AppConfig::url('/vagas/publicar'); ?>" method="POST" class="auth-form">
            <div class="form-group">
                <label for="title">Título do Projeto *</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Ex: Criação de E-commerce responsivo em PHP" required>
            </div>

            <div class="form-group">
                <label for="category">Tecnologia Exigida *</label>
                <div class="form-group">
                <label for="category">Categoria do Profissional / Serviço *</label>
                <input type="text" name="category" id="category" class="form-control" placeholder="Ex: Pedreiro, Encanador, Eletricista, Desenvolvedor PHP" required>
            </div>

            </div>

            <div class="form-group">
                <label for="budget">Orçamento Estimado (R$) - Opcional</label>
                <input type="number" step="0.01" name="budget" id="budget" class="form-control" placeholder="Ex: 1500.00">
            </div>

            <div class="form-group">
                <label for="description">Descrição do Escopo do Trabalho *</label>
                <textarea name="description" id="description" class="form-control" rows="6" placeholder="Escreva aqui as funcionalidades que o profissional precisará desenvolver, requisitos e prazos esperados..." style="resize: none; font-family: inherit;" required></textarea>
            </div>

            <button type="submit" class="btn-action btn-primary" style="width: 100%;">Publicar Projeto Gratuitamente</button>
        </form>

        <p class="auth-footer-link">
            <a href="<?php echo \App\Config\AppConfig::url('/dashboard'); ?>">← Cancelar e Voltar ao Painel</a>
        </p>
    </div>

</body>
</html>
