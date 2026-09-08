<?php include __DIR__ . '/partials/header.php'; ?>

<!-- Container principal com reset completo de qualquer grid/sidebar lateral residual -->
<div style="display: block; width: 100%; max-width: 1200px; margin: 0 auto; padding: 100px 20px 40px 20px; box-sizing: border-box; float: none; clear: both;">
    
    <h2 style="color: #1e1b4b; font-weight: 800; font-size: 28px; margin-bottom: 10px;">Central de Notícias</h2>
    <p style="color: #475569; margin-bottom: 40px;">Dicas de carreira, mercado freelancer e atualizações da nossa comunidade.</p>
    
    <!-- Forçamos o Grid dos cards a ocupar 100% da largura útil sem interferências -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; width: 100%; box-sizing: border-box; margin-bottom: 50px;">
        
        <!-- Artigo Exemplo 1 -->
        <article style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 20px;">
                <span style="font-size: 11px; font-weight: bold; color: #4f46e5; background: #e0e7ff; padding: 3px 8px; border-radius: 4px;">CARREIRA</span>
                <h3 style="color: #1e1b4b; margin: 15px 0 10px 0; font-size: 18px; font-weight: 700;">Como se destacar no mercado freelancer em 2026</h3>
                <p style="color: #475569; font-size: 13px; line-height: 1.5; margin-bottom: 20px;">Construir perfis detalhados e acumular avaliações positivas por estrelas são chaves para dobrar seus contratos corporativos.</p>
                <a href="#" style="color: #4f46e5; text-decoration: none; font-weight: bold; font-size: 13px;">Ler artigo completo &rarr;</a>
            </div>
        </article>

        <!-- Artigo Exemplo 2 -->
        <article style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="padding: 20px;">
                <span style="font-size: 11px; font-weight: bold; color: #10b981; background: #d1fae5; padding: 3px 8px; border-radius: 4px;">DICAS DE CONTRATAÇÃO</span>
                <h3 style="color: #1e1b4b; margin: 15px 0 10px 0; font-size: 18px; font-weight: 700;">O guia completo para contratar desenvolvedores seniores</h3>
                <p style="color: #475569; font-size: 13px; line-height: 1.5; margin-bottom: 20px;">Aprenda a analisar históricos de reputação biográfica e portfólios técnicos para evitar retrabalhos operacionais.</p>
                <a href="#" style="color: #4f46e5; text-decoration: none; font-weight: bold; font-size: 13px;">Ler artigo completo &rarr;</a>
            </div>
        </article>

    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
