<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <!-- CSS Local do Sistema -->
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
    <style>
        /* Ajustes específicos de painel corporativo para complementar o style.css */
        .header-menu { display: flex; justify-content: space-between; align-items: center; background: #ffffff; padding: 15px 30px; border-bottom: 1px solid #e2e8f0; width: 100%; box-sizing: border-box; position: fixed; top: 0; left: 0; z-index: 100; }
        .footer-bar { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 20px; width: 100%; text-align: center; box-sizing: border-box; margin-top: auto; font-size: 13px; color: #64748b; }
        .dashboard-layout { display: grid; grid-template-columns: 1fr; gap: 25px; max-width: 1100px; width: 95%; margin: 90px auto 40px auto; }
        .section-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); text-align: left; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        .data-table th, .data-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        .data-table th { background: #f8fafc; color: #1e1b4b; font-weight: 700; }
        .btn-danger { background-color: #fef2f2; color: #dc3545; border: 1px solid #fecaca; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; }
        .btn-danger:hover { background-color: #dc3545; color: #ffffff; }
    </style>
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh; justify-content: flex-start; padding: 0;">

    <!-- 🌐 MENU SUPERIOR CORPORATIVO -->
    <header class="header-menu">
        <a href="<?php echo \App\Config\AppConfig::url('/'); ?>" style="font-weight: 800; color: #4f46e5; text-decoration: none; font-size: 18px;">8ou80.site</a>
        <div style="display: flex; gap: 20px; align-items: center;">
            <span style="background: #fefce8; color: #854d0e; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold;">💰 <?php echo $usuario['moedas']; ?> Moedas</span>
            <a href="<?php echo \App\Config\AppConfig::url('/'); ?>" style="color: #475569; text-decoration: none; font-size: 14px; font-weight: 600;">Voltar à Página Inicial</a>
            <a href="<?php echo \App\Config\AppConfig::url('/sair'); ?>" style="color: #dc3545; text-decoration: none; font-size: 14px; font-weight: 600;">Sair da Conta</a>
        </div>
    </header>

    <!-- 🎛️ CORPO DO DASHBOARD UNIFICADO -->
    <main class="dashboard-layout">
        
        <!-- Bloco 1: Editar Perfil e Dados Cadastrais -->
        <section class="section-card">
            <h2 style="color: #1e1b4b; margin-top: 0; font-size: 20px;">👤 Meus Dados e Perfil Profissional</h2>
            <p style="color: #64748b; font-size: 13px; margin-bottom: 20px;">Atualize suas informações de contato e adicione dados de trabalho caso queira oferecer seus serviços na vitrine.</p>
            
            <form action="<?php echo \App\Config\AppConfig::url('/dashboard/perfil/atualizar'); ?>" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px;">
                <div class="form-group">
                    <label>Seu Nome</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $usuario['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Telefone / WhatsApp</label>
                    <input type="text" name="telefone" class="form-control" value="<?php echo $usuario['telefone']; ?>" placeholder="(00) 00000-0000">
                </div>
                <div class="form-group">
                    <label>Sua Profissão (Se for Freelancer)</label>
                    <input type="text" name="category" class="form-control" value="<?php echo $perfilFreela['category'] ?? ''; ?>" placeholder="Ex: Pedreiro, Eletricista, Programador">
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Biografia / Resumo Profissional</label>
                    <textarea name="bio" class="form-control" rows="3" placeholder="Fale um pouco sobre sua experiência técnica..." style="resize:none; font-family:inherit;"><?php echo $perfilFreela['bio'] ?? ''; ?></textarea>
                </div>
                <div style="grid-column: 1 / -1; text-align: right;">
                    <button type="submit" class="btn-action btn-primary" style="padding: 10px 20px; font-size: 14px;">Salvar Alterações Perfil</button>
                </div>
            </form>
        </section>

        <!-- Bloco 2: Lado Contratante (Vagas que eu anunciei) -->
        <section class="section-card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <h2 style="color: #1e1b4b; margin: 0; font-size: 20px;">📋 Projetos que eu Anunciei (Como Cliente)</h2>
                <a href="<?php echo \App\Config\AppConfig::url('/vagas/publicar'); ?>" class="btn-action btn-primary" style="padding: 8px 16px; font-size: 13px; box-shadow: none;">➕ Anunciar Novo Serviço</a>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Título do Projeto</th>
                        <th>Categoria</th>
                        <th>Orçamento</th>
                        <th>Status</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($minhasVagas)): ?>
                        <tr><td colspan="5" style="color: #64748b; text-align: center; padding: 20px;">Você ainda não publicou nenhuma vaga de trabalho.</td></tr>
                    <?php else: ?>
                        <?php foreach ($minhasVagas as $job): ?>
                        <tr>
                            <td data-label="Projeto: " style="font-weight: 600; color: #1e1b4b;"><?php echo $job['title']; ?></td>
                            <td data-label="Categoria: "><span style="background:#f1f5f9; padding: 3px 8px; border-radius: 6px; font-size:12px;"><?php echo $job['category']; ?></span></td>
                            <td data-label="Orçamento: " style="color: #16a34a; font-weight: 600;"><?php echo $job['budget'] ? 'R$ ' . number_format($job['budget'], 2, ',', '.') : 'A combinar'; ?></td>
                            <td data-label="Status: "><span style="color: #4f46e5; font-weight: 600;"><?php echo ucfirst($job['status']); ?></span></td>
                            <td style="text-align: center;">
                                <a href="<?php echo \App\Config\AppConfig::url('/vagas/excluir?id=' . $job['id']); ?>" class="btn-danger" onclick="return confirm('Tem certeza que deseja remover esta vaga permanentemente?');">🗑️ Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Bloco 3: Lado Freelancer (Vagas para as quais enviei propostas) -->
        <section class="section-card">
            <h2 style="color: #1e1b4b; margin-top: 0; font-size: 20px;">💼 Propostas que Enviei (Como Freelancer)</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Projeto Solicitado</th>
                        <th>Minha Oferta Cobrada</th>
                        <th>Prazo de Entrega</th>
                        <th>Data do Envio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($minhasPropostas)): ?>
                        <tr><td colspan="4" style="color: #64748b; text-align: center; padding: 20px;">Você ainda não enviou nenhuma proposta comercial para vagas abertas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($minhasPropostas as $prop): ?>
                        <tr>
                            <td data-label="Vaga: " style="font-weight: 600; color: #1e1b4b;"><?php echo $prop['titulo_vaga']; ?></td>
                            <td data-label="Oferta: " style="color: #16a34a; font-weight: 600;">R$ <?php echo number_format($prop['bid_amount'], 2, ',', '.'); ?></td>
                            <td data-label="Prazo: "><?php echo $prop['delivery_days']; ?> dias úteis</td>
                            <td data-label="Envio: " style="color: #64748b;"><?php echo date('d/m/Y H:i', strtotime($prop['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <!-- 🧱 RODAPÉ INSTITUCIONAL UNIFICADO -->
    <footer class="footer-bar">
        <p style="margin: 0 0 10px 0;">&copy; 2026 <strong>8ou80.site</strong> - Todos os direitos reservados.</p>
        <div style="display: flex; justify-content: center; gap: 20px;">
            <a href="#" style="color: #64748b; text-decoration: none;">Política de Privacidade</a>
            <span style="color: #cbd5e1;">|</span>
            <a href="#" style="color: #64748b; text-decoration: none;">Termos de Uso</a>
        </div>
    </footer>

</body>
</html>
