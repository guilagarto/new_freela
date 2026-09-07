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
        <span class="badge-version">Ecossistema de Talentos</span>
        <h1 class="main-title" style="text-align: center;">Encontre Profissionais</h1>
        
        <!-- Bloco de Filtros Estilizado local -->
        <div style="display: flex; gap: 15px; margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <input type="text" id="busca-nome" class="form-control" placeholder="🔍 Buscar por nome ou título...">
            </div>
            <div style="flex: 1; min-width: 200px;">
                           <div style="flex: 1; min-width: 200px;">
                <select id="busca-tecnologia" class="form-control">
                    <option value="">Todas as Profissões</option>
                    
                    <!-- 🚀 LOOP DINÂMICO: Renderiza apenas as profissões reais do banco -->
                    <?php if (!empty($todasCategorias)): ?>
                        <?php foreach ($todasCategorias as $cat): ?>
                            <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                </select>
            </div>

            </div>
        </div>

        <!-- Lista Dinâmica onde o JS injetará os Cards -->
        <div id="lista-profissionais" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
            <!-- Carregando via AJAX... -->
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="<?php echo \App\Config\AppConfig::url('/'); ?>" style="color: #4f46e5; text-decoration: none; font-weight: 600;">← Voltar para Home</a>
        </div>
    </div>

    <!-- ⚡ AJAX FETCH API EM TEMPO REAL -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputNome = document.getElementById("busca-nome");
        const selectTecnologia = document.getElementById("busca-tecnologia");
        const container = document.getElementById("lista-profissionais");

        function carregarProfissionais() {
            const nome = inputNome.value;
            const tech = selectTecnologia.value;

            // Faz a requisição AJAX silenciosa para a rota do PHP Controller
            fetch(`<?php echo \App\Config\AppConfig::url('/api/profissionais/filtrar'); ?>?nome=${encodeURIComponent(nome)}&tecnologia=${encodeURIComponent(tech)}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = ""; // Limpa os resultados antigos

                    if (data.length === 0) {
                        container.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 20px;">Nenhum profissional encontrado com esses filtros.</p>`;
                        return;
                    }

                    // Monta os cards dinamicamente na tela
                                        // Atualizado para ler as propriedades reais retornadas pelo banco
                    data.forEach(prof => {
                        container.innerHTML += `
                            <div style="border: 1px solid #e2e8f0; background: #ffffff; padding: 20px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <h3 style="margin: 0 0 5px 0; font-size: 18px; color: #1e1b4b;">${prof.nome_usuario}</h3>
                                    <p style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #4f46e5;">${prof.category}</p>
                                    <p style="margin: 0 0 15px 0; font-size: 13px; color: #475569; line-height: 1.4;">${prof.bio || 'Sem descrição biográfica.'}</p>
                                </div>
                                <div>
                                    <a href="<?php echo \App\Config\AppConfig::url('/vagas/publicar'); ?>" class="btn-action btn-primary" style="width: 100%; text-align: center; font-size: 13px; padding: 8px; font-weight: bold;">📥 Contratar Profissional</a>

                                </div>
                            </div>
                        `;
                    });

                });
        }

        // Eventos para escutar digitação e cliques
        inputNome.addEventListener("input", carregarProfissionais);
        selectTecnologia.addEventListener("change", carregarProfissionais);

        // Primeira carga automática ao abrir a página
        carregarProfissionais();
    });
    </script>
</body>
</html>
