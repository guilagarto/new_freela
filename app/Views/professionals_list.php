<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

  <!-- ESTILOS EXCLUSIVOS DA VIEW PARA GARANTIR RESPONSIVIDADE EM QUALQUER CELULAR -->
<style>
    /* Container principal da página */
    .pagina-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 10px;
        box-sizing: border-radius;
    }

    /* Barra de Filtros Inteligente */
    .filtro-container-mobile {
        display: flex; 
        gap: 15px; 
        margin-bottom: 30px; 
        background: #f8fafc; 
        padding: 15px; 
        border-radius: 12px; 
        border: 1px solid #e2e8f0; 
        align-items: center;
        flex-wrap: wrap; /* Permite quebrar linha se não couber */
    }

    .filtro-item-busca {
        flex: 2; 
        min-width: 250px; /* Impede de espremer menos que isso */
    }

    .filtro-item-select {
        flex: 1; 
        min-width: 200px;
    }

    .filtro-item-botao {
        flex: 1;
        min-width: 120px;
    }

    /* Grid de Cards dos Profissionais */
    .grid-profissionais-mobile {
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
        gap: 20px; 
        margin-bottom: 30px;
        width: 100%;
    }

    /* FORÇAR COMPORTAMENTO NO CELULAR (Telas de até 768px) */
    @media (max-width: 768px) {
        .filtro-container-mobile {
            flex-direction: column !important; /* Empilha os filtros verticalmente */
            align-items: stretch !important;
            gap: 12px;
            padding: 12px;
        }

        .filtro-item-busca, 
        .filtro-item-select, 
        .filtro-item-botao {
            width: 100% !important;
            flex: none !important;
        }
        
        .form-control, #btn-buscar {
            width: 100% !important;
            display: block;
        }

        .grid-profissionais-mobile {
            grid-template-columns: 1fr !important; /* 1 card inteiro por linha no celular */
            gap: 15px;
        }
    }
</style>

<!-- Envolvendo tudo em um container seguro que respeita as margens da tela -->
<div class="pagina-container">

    <!-- Cabeçalho da Página -->
    <div class="welcome-container" style="max-width: 100%; text-align: left; margin-bottom: 20px;">
        <span class="badge-version">Ecossistema de Talentos</span>
        <h1 class="main-title" style="text-align: center; font-size: calc(1.8rem + 1vw);">Encontre Profissionais</h1>
    </div>
  
    <!-- Bloco de Filtros Dinâmico -->
    <div class="filtro-container-mobile">
        
        <!-- Campo de Busca por Texto -->
        <div class="filtro-item-busca">
            <input type="text" id="busca-nome" class="form-control" placeholder="🔍 Buscar por nome ou título...">
        </div>
        
        <!-- Filtro de Seleção de Categorias -->
        <div class="filtro-item-select">
            <select id="busca-tecnologia" class="form-control">
                <option value="">Todas as Profissões</option>
                <?php if (!empty($todasCategorias)): ?>
                    <?php foreach ($todasCategorias as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Botão de Busca -->
        <div class="filtro-item-botao">
            <button type="button" id="btn-buscar" style="width: 100%; background-color: #4f46e5; color: white; border: none; padding: 11px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                Buscar
            </button>
        </div>

    </div>

    <!-- Container Onde os Cards Serão Injetados Dinamicamente via AJAX -->
    <div id="lista-profissionais" class="grid-profissionais-mobile">
        <!-- O script preencherá este espaço automaticamente -->
    </div>

</div> <!-- Fim do pagina-container -->


<!-- ========================================================================= -->
<!-- 2. SCRIPT DE COMUNICAÇÃO AJAX (JAVASCRIPT FETCH API)                     -->
<!-- ========================================================================= -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Mapeia todos os elementos necessários da interface
    const inputNome = document.getElementById("busca-nome");
    const selectTecnologia = document.getElementById("busca-tecnologia");
    const container = document.getElementById("lista-profissionais");
    const btnBuscar = document.getElementById("btn-buscar");

    // Trava de segurança inicial caso algum ID esteja incorreto na árvore do DOM
    if (!inputNome || !selectTecnologia || !container) {
        console.error("Erro Crítico: Verifique se os elementos 'busca-nome', 'busca-tecnologia' ou 'lista-profissionais' existem na página.");
        return;
    }

    // Função central encarregada de fazer a requisição à API e renderizar a tela
       function carregarProfissionais() {
        const nome = inputNome.value || '';
        const tech = selectTecnologia.value || '';

        const urlBusca = `<?php echo \App\Config\AppConfig::url('/api/profissionais/filtrar'); ?>?nome=${encodeURIComponent(nome)}&tecnologia=${encodeURIComponent(tech)}&v=${new Date().getTime()}`;

        fetch(urlBusca)
            .then(response => {
                if (!response.ok) throw new Error("Erro na requisição do servidor.");
                // 1. Mudamos para pegar como TEXTO primeiro para não quebrar o script se houver sujeira no PHP
                return response.text(); 
            })
            .then(textoBruto => {
                // 2. Limpa qualquer espaço em branco ou caractere fantasma nas pontas do texto
                const textoLimpo = textoBruto.trim();
                
                // 3. Tenta converter manualmente para objeto JSON
                let data;
                try {
                    data = JSON.parse(textoLimpo);
                } catch (e) {
                    console.error("Texto corrompido recebido do PHP:", textoBruto);
                    throw new Error("O servidor não retornou um JSON válido. Verifique o console.");
                }

                container.innerHTML = ""; // Limpa a listagem anterior

                if (!data || data.length === 0) {
                    container.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: #64748b; padding: 20px; font-weight: 600;">🔍 Nenhum profissional cadastrado com esses filtros.</p>`;
                    return;
                }

                // Renderiza os cards na tela
                data.forEach(prof => {
                    const nota = parseFloat(prof.media_estrelas || 0);
                    const totalAvaliacoes = parseInt(prof.total_avaliacoes || 0);
                    const nomeUsuario = prof.nome_usuario || 'Profissional';
                    const categoria = prof.category || 'Geral';
                    const biografia = prof.bio || 'Sem descrição biográfica.';
                    
                    let estrelasHTML = "";

                    if (nota > 0) {
                        const estrelasCheias = Math.round(nota);
                        estrelasHTML = `<span style="color: #eab308; font-size: 14px; font-weight: bold;">${"★".repeat(estrelasCheias)}${"☆".repeat(5 - estrelasCheias)}</span> ` +
                                       `<span style="color: #64748b; font-size: 12px; font-weight: 600;">(${nota.toFixed(1)} • ${totalAvaliacoes} avaliações)</span>`;
                    } else {
                        estrelasHTML = `<span style="color: #a1a1aa; font-size: 11px; font-weight: bold; background: #f4f4f5; padding: 2px 6px; border-radius: 4px;">🆕 NOVO PROFISSIONAL</span>`;
                    }

                    container.innerHTML += `
                        <div style="border: 1px solid #e2e8f0; background: #ffffff; padding: 20px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h3 style="margin: 0 0 4px 0; font-size: 18px; color: #1e1b4b;">${nomeUsuario}</h3>
                                <div style="margin-bottom: 12px;">${estrelasHTML}</div>
                                <p style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #4f46e5;">${categoria}</p>
                                <p style="margin: 0 0 15px 0; font-size: 13px; color: #475569; line-height: 1.4;">${biografia}</p>
                            </div>
                            <div>
                                <a href="<?php echo \App\Config\AppConfig::url('/vagas/publicar'); ?>" class="btn-action btn-primary" style="width: 100%; text-align: center; font-size: 13px; padding: 8px; font-weight: bold; display: block; border-radius: 6px;">📥 Contratar Profissional</a>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error("Erro interno no carregamento:", error);
                container.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: #ef4444; padding: 20px;">Houve um erro ao processar os filtros. Tente novamente mais tarde.</p>`;
            });
    }


    // Vincula a escuta de eventos aos manipuladores
    if (btnBuscar) {
        btnBuscar.addEventListener("click", carregarProfissionais);
    }
    
    // Mantém a busca em tempo real ativa ao interagir diretamente com os campos (Opcional)
    inputNome.addEventListener("input", carregarProfissionais);
    selectTecnologia.addEventListener("change", carregarProfissionais);

    // Executa a primeira inicialização de dados assim que a tela abre
    carregarProfissionais();
});
</script>

    
</body>
</html>
