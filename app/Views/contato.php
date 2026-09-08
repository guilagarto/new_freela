<?php include __DIR__ . '/partials/header.php'; ?>

<!-- Container principal com o mesmo reset estrutural de barreira lateral -->
<div style="display: block; width: 100%; max-width: 600px; margin: 0 auto; padding: 100px 20px 40px 20px; box-sizing: border-box; float: none; clear: both;">
    
    <div style="background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); margin-bottom: 50px;">
        <h2 style="color: #1e1b4b; margin-bottom: 10px; font-weight: 700; font-size: 24px;">Entre em contato conosco</h2>
        <p style="color: #475569; font-size: 14px; margin-bottom: 25px; line-height: 1.4;">Preencha os campos abaixo e nosso time de suporte responderá em até 24 hours úteis.</p>
        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
            <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; border: 1px solid #a7f3d0; margin-bottom: 20px; font-weight: 600; text-align: center; font-size: 14px;">
                🚀 Mensagem enviada com sucesso! Uma cópia de confirmação foi enviada para o seu e-mail.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'campos_invalidos'): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; border: 1px solid #fca5a5; margin-bottom: 20px; font-weight: 600; text-align: center; font-size: 14px;">
                ⚠️ Por favor, preencha todos os campos do formulário corretamente.
            </div>
        <?php endif; ?>

        <!-- Mude a action para gerar o caminho injetado pelo PHP -->
        <!-- Deixe a tag do formulário exatamente com esta configuração de action -->
        <!-- SUBSTITUA A SUA TAG DE FORMULÁRIO POR ESTA EXATAMENTE ASSIM: -->
<form action="<?php echo \App\Config\AppConfig::url('/contato/enviar'); ?>" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
  


            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1e1b4b; margin-bottom: 5px;">Nome Completo</label>
                <input type="text" name="nome" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;" required>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1e1b4b; margin-bottom: 5px;">E-mail Corporativo</label>
                <input type="email" name="email" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;" required>
            </div>
            <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #1e1b4b; margin-bottom: 5px;">Mensagem / Assunto</label>
                <textarea name="mensagem" class="form-control" style="width: 100%; height: 120px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; resize: vertical;" required></textarea>
            </div>
            <button type="submit" style="background: #4f46e5; color: #fff; font-weight: bold; border: none; padding: 12px; border-radius: 6px; cursor: pointer; transition: background 0.2s;">
                ✉️ Enviar Mensagem
            </button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
