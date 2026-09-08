    <!-- Fecha o container principal da página com segurança -->
    </div> 

    <!-- Rodapé Inteligente com Transição Suave -->
    <footer id="main-footer-dinamico" class="main-footer" style="background: #0f172a !important; color: #94a3b8 !important; padding: 20px !important; text-align: center !important; clear: both !important; display: block !important; box-sizing: border-box !important; position: fixed !important; left: 0 !important; bottom: 0 !important; width: 100vw !important; z-index: 99999 !important; transition: transform 0.3s ease-in-out !important;">
        <div style="max-width: 1200px; margin: 0 auto; width: 100%;">
            <p style="margin: 0 0 8px 0; font-size: 13px; color: #94a3b8;">&copy; <?php echo date('Y'); ?> TalentoHub. Todos os direitos reservados.</p>
            <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; list-style: none; padding: 0; margin: 0;">
                <a href="<?php echo \App\Config\AppConfig::url('/politica-de-privacidade'); ?>" style="color: #cbd5e1; text-decoration: none; font-size: 12px; font-weight: 500;">Política de Privacidade</a>
                <a href="<?php echo \App\Config\AppConfig::url('/termos-de-uso'); ?>" style="color: #cbd5e1; text-decoration: none; font-size: 12px; font-weight: 500;">Termos de Uso</a>
            </div>
        </div>
    </footer>

    <!-- SCRIPT DE COMPORTAMENTO DO RODAPÉ -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const footer = document.getElementById("main-footer-dinamico");
        if (!footer) return;

        let ultimaRolagem = 0;

        window.addEventListener("scroll", function() {
            let rolagemAtual = window.pageYOffset || document.documentElement.scrollTop;

            if (rolagemAtual > ultimaRolagem && rolagemAtual > 60) {
                // Rolou para BAIXO -> Esconde o rodapé empurrando ele para fora da tela
                footer.style.transform = "translateY(100%)";
            } else {
                // Rolou para CIMA -> Mostra o rodapé trazendo ele de volta
                footer.style.transform = "translateY(0)";
            }
            
            // Impede valores negativos no topo (comum em iPhones)
            ultimaRolagem = rolagemAtual <= 0 ? 0 : rolagemAtual; 
        });
    });
    </script>

</body>
</html>
