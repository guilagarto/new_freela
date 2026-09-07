<?php

namespace App\Services;

class EmailService {
    /**
     * Dispara e-mail em formato HTML profissional compatível com XAMPP e Hostinger
     */
    public static function enviarBoasVindas(string $nome, string $emailDestino): bool {
        $assunto = "👑 Bem-vindo ao 8ou80.site! Você ganhou 20 moedas gratis!";
        
        // Template de e-mail moderno e limpo
        $mensagemHTML = "
        <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 16px; background-color: #ffffff;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <span style='background-color: #e0e7ff; color: #4f46e5; padding: 6px 16px; border-radius: 9999px; font-size: 12px; font-weight: bold; text-transform: uppercase;'>8ou80.site</span>
            </div>
            <h1 style='color: #1e1b4b; text-align: center; font-size: 24px;'>Olá, {$nome}! Seja muito bem-vindo!</h1>
            <p style='color: #475569; font-size: 16px; line-height: 1.6;'>Sua conta unificada de porta dupla foi criada com sucesso no maior ecossistema de trabalhadores autônomos e freelancers da web.</p>
            
            <div style='background-color: #fefce8; border: 1px solid #fef08a; padding: 15px; border-radius: 12px; margin: 25px 0; text-align: center;'>
                <h3 style='color: #854d0e; margin: 0 0 5px 0; font-size: 18px;'>💰 Seu saldo atual: 20 Moedas Grátis</h3>
                <p style='color: #713f12; margin: 0; font-size: 14px;'>Como presente de boas-vindas, creditamos moedas para você testar a plataforma imediatamente!</p>
            </div>

            <h3 style='color: #1e1b4b; font-size: 18px;'>📖 Como utilizar suas moedas na plataforma?</h3>
            <ul style='color: #475569; font-size: 14px; line-height: 1.8; padding-left: 20px;'>
                <li><strong>Para Freelancers/Profissionais:</strong> Cada proposta comercial enviada para uma vaga ativa consome <strong>2 moedas</strong> do seu saldo.</li>
                <li><strong>Para Contratantes/Clientes:</strong> Destacar o seu anúncio no topo do mural público como 'Vaga Estrela' para atrair propostas 5x mais rápido consome <strong>5 moedas</strong>.</li>
            </ul>

            <p style='color: #475569; font-size: 14px; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 20px;'>Bons negócios,<br><strong>Equipe 8ou80.site</strong></p>
        </div>
        ";

        // Cabeçalhos para formatação HTML e codificação UTF-8 correta
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: Não Responda <noreply@8ou80.site>\r\n";
        $headers .= "Reply-To: suporte@8ou80.site\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Envia de forma assíncrona/silenciosa para não travar o carregamento do PHP
        return @mail($emailDestino, $assunto, $mensagemHTML, $headers);
    }
}
