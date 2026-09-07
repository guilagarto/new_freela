<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container">
        <span class="badge-version" style="background-color: #bbf7d0; color: #16a34a;">Ativar Perfil Profissional 🚀</span>
        <h1 class="main-title">Trabalhar na Plataforma</h1>
        <p class="description-text">Preencha seus dados técnicos para começar a enviar propostas para projetos.</p>

        <?php if (isset($_SESSION['erro_perfil'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['erro_perfil']; unset($_SESSION['erro_perfil']); ?></div>
        <?php endif; ?>

        <form action="<?php echo \App\Config\AppConfig::url('/profissional/completar-perfil'); ?>" method="POST" class="auth-form">
            <div class="form-group">
                <label for="title">Seu Título Profissional *</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Ex: Desenvolvedor PHP Sênior, Designer UI/UX" required>
            </div>

            <div class="form-group">
                <label for="skills">Tecnologia Principal / Habilidade *</label>
                <select name="skills" id="skills" class="form-control" required>
                    <option value="PHP" selected>PHP / Laravel</option>
                    <option value="JavaScript">JavaScript / Node.js</option>
                    <option value="Tailwind">Tailwind CSS / Design</option>
                    <option value="Python">Python / Data Science</option>
                </select>
            </div>

            <div class="form-group">
                <label for="bio">Apresentação / Biografia</label>
                <textarea name="bio" id="bio" class="form-control" rows="4" placeholder="Conte um pouco sobre sua experiência e principais projetos..." style="resize: none; font-family: inherit;"></textarea>
            </div>

            <button type="submit" class="btn-action btn-primary" style="width: 100%; background-color: #16a34a; box-shadow: none;">Gravar Perfil e Acessar Painel</button>
        </form>

        <p class="auth-footer-link">
            <a href="<?php echo \App\Config\AppConfig::url('/dashboard'); ?>">← Voltar ao Painel</a>
        </p>
    </div>

</body>
</html>
