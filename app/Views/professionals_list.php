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
        <!-- Bloco de Filtros Estilizado Local -->
<div style="display: flex; gap: 15px; margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; flex-wrap: wrap; align-items: center;">
    
    <div style="flex: 1; min-width: 250px;">
        <input type="text" id="busca-nome" class="form-control" placeholder="🔍 Buscar por nome ou título...">
    </div>
    
    <div style="flex: 1; min-width: 200px; display: flex; gap: 10px;">
        <select id="busca-tecnologia" class="form-control">
            <option value="">Todas as Profissões</option>
            <?php if (!empty($todasCategorias)): ?>
                <?php foreach ($todasCategorias as $cat): ?>
                    <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <!-- NOVO BOTÃO DE BUSCA -->
    <div style="min-width: 120px;">
        <button type="button" id="btn-buscar" style="width: 100%; background-color: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
            Buscar
        </button>
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
    const btnBuscar = document.getElementById("btn-buscar"); // 1. MAPEIA O NOVO BOTÃO

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
                data.forEach(prof => {
                    // Converte a média em número para manipulação
                    const nota = parseFloat(prof.media_estrelas);
                    let estrelasHTML = "";

                    if (nota > 0) {
                        // Arredonda a nota e monta a string visual de estrelas
                        const estrelasCheias = Math.round(nota);
                        estrelasHTML = `<span style="color: #eab308; font-size: 14px; font-weight: bold;">${"★".repeat(estrelasCheias)}${"☆".repeat(5 - estrelasCheias)}</span> ` +
                                       `<span style="color: #64748b; font-size: 12px; font-weight: 600;">(${nota.toFixed(1)} • ${prof.total_avaliacoes} avaliações)</span>`;
                    } else {
                        estrelasHTML = `<span style="color: #a1a1aa; font-size: 11px; font-weight: bold; background: #f4f4f5; padding: 2px 6px; border-radius: 4px;">🆕 NOVO PROFISSIONAL</span>`;
                    }

                    container.innerHTML += `
                        <div style="border: 1px solid #e2e8f0; background: #ffffff; padding: 20px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="margin: 0 0 4px 0; font-size: 18px; color: #1e1b4b;">${prof.nome_usuario}</h3>
                                
                                <!-- Bloco de Reputação Acoplado -->
                                <div style="margin-bottom: 12px;">${estrelasHTML}</div>
                                
                                <p style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #4f46e5;">${prof.category}</p>
                                <p style="margin: 0 0 15px 0; font-size: 13px; color: #475569; line-height: 1.4;">${prof.bio || 'Sem descrição biográfica.'}</p>
                            </div>
                            <div>
                                <a href="<?php echo \App\Config\AppConfig::url('/vagas/publicar'); ?>" class="btn-action btn-primary" style="width: 100%; text-align: center; font-size: 13px; padding: 8px; font-weight: bold;">📥 Contratar Profissional</a>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(error => console.error("Erro na busca de profissionais:", error)); // Boa prática tratar erros de requisição
    }
                    
    // Eventos para escutar cliques e digitação
    if (btnBuscar) {
        btnBuscar.addEventListener("click", carregarProfissionais); // 2. ESCUTA O CLIQUE DO BOTÃO
    }
    
    inputNome.addEventListener("input", carregarProfissionais);
    selectTecnologia.addEventListener("change", carregarProfissionais);

    // Primeira carga automática ao abrir a página
    carregarProfissionais();
});
</script>

</body>
</html>
