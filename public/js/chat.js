// public/js/chat.js

document.addEventListener("DOMContentLoaded", () => {
    const chatBox = document.getElementById("chat-box");
    const chatForm = document.getElementById("chat-form");
    const mensagemInput = document.getElementById("mensagem-input");
    const remetenteId = document.getElementById("remetente_id").value;
    const destinatarioIdInput = document.getElementById("destinatario_id");
    const usuariosLista = document.querySelector(".usuarios-lista");
    const nomeChatAtivo = document.getElementById("nome-chat-ativo");

    let intervaloChat = null;
   // Captura o ID do profissional vindo do parâmetro da URL (?destinatario_id=4)
const urlParams = new URLSearchParams(window.location.search);
const idDaUrl = urlParams.get('destinatario_id');

// Se houver um ID na URL, força a gravação dele no input escondido do HTML
if (idDaUrl) {
    destinatarioIdInput.value = idDaUrl;
}

// Define quem é o destinatário atual lendo diretamente o input validado
let idDestinatarioAtual = destinatarioIdInput.value;


    // 1. FUNÇÃO PARA CARREGAR OS USUÁRIOS NA BARRA LATERAL (VIA POST)
       // 1. FUNÇÃO PARA CARREGAR OS USUÁRIOS NA BARRA LATERAL (BLINDADA PARA CONTATOS NOVOS)
    function carregarUsuariosContatos() {
        fetch(`${URL_BASE}/chat/usuarios`, {
            method: "POST"
        })
        .then(response => response.json())
        .then(usuarios => {
            usuariosLista.innerHTML = ""; 

            let alvoExisteNaLista = false;
            if (Array.isArray(usuarios) && idDaUrl) {
                alvoExisteNaLista = usuarios.some(user => user.id == idDaUrl);
            }

            // Renderiza as conversas que já existem no banco
            if (Array.isArray(usuarios) && usuarios.length > 0) {
                usuarios.forEach((user, index) => {
                    const div = document.createElement("div");
                    div.classList.add("usuario-item");
                    div.setAttribute("data-id", user.id);
                    
                    div.style.padding = "15px";
                    div.style.borderBottom = "1px solid #f9f9f9";
                    div.style.cursor = "pointer";
                    div.style.fontWeight = "500";
                    div.style.color = "#333";

                    const nomeUsuario = user.name || user.nome || `Usuário ${user.id}`;
                    div.innerHTML = `👤 ${nomeUsuario}`;

                    // Seleciona se vier da URL ou se for o primeiro
                    if (idDaUrl && user.id == idDaUrl) {
                        div.classList.add("active");
                        div.style.background = "#eef2f3";
                        div.style.color = "#4f46e5";
                        div.style.fontWeight = "600";
                        nomeChatAtivo.textContent = nomeUsuario;
                    } else if (!idDaUrl && index === 0) {
                        div.classList.add("active");
                        div.style.background = "#eef2f3";
                        div.style.color = "#4f46e5";
                        div.style.fontWeight = "600";
                        destinatarioIdInput.value = user.id;
                        nomeChatAtivo.textContent = nomeUsuario;
                    }

                    div.addEventListener("click", function() {
                        document.querySelectorAll(".usuario-item").forEach(i => {
                            i.classList.remove("active");
                            i.style.background = "transparent";
                            i.style.color = "#333";
                            i.style.fontWeight = "500";
                        });
                        
                        this.classList.add("active");
                        this.style.background = "#eef2f3";
                        this.style.color = "#4f46e5";
                        this.style.fontWeight = "600";

                        destinatarioIdInput.value = this.getAttribute("data-id");
                        nomeChatAtivo.textContent = nomeUsuario;
                        
                        iniciarChat();
                    });

                    usuariosLista.appendChild(div);
                });
            }

            // =========================================================================
            // COLOQUE ESTA TRAVA AQUI: Se for um contato inédito, força o card no topo!
            // =========================================================================
            if (idDaUrl && !alvoExisteNaLista) {
                const divNovo = document.createElement("div");
                divNovo.classList.add("usuario-item", "active");
                divNovo.setAttribute("data-id", idDaUrl);
                
                divNovo.style.padding = "15px";
                divNovo.style.borderBottom = "1px solid #f9f9f9";
                divNovo.style.cursor = "pointer";
                divNovo.style.fontWeight = "600";
                divNovo.style.background = "#eef2f3";
                divNovo.style.color = "#4f46e5";
                divNovo.innerHTML = `👤 Nova Conversa (ID: ${idDaUrl})`;
                
                usuariosLista.insertBefore(divNovo, usuariosLista.firstChild);
                nomeChatAtivo.textContent = "Nova Conversa";
                
                // CRUCIAL: Força o input do HTML a receber esse ID para o POST enviar certo!
                destinatarioIdInput.value = idDaUrl; 
            } else if (!idDaUrl && (!usuarios || usuarios.length === 0)) {
                usuariosLista.innerHTML = `<div style="padding: 15px; color: #999; font-size: 14px;">Nenhuma conversa ativa.</div>`;
            }

            iniciarChat();
        })
        .catch(err => console.error("Erro ao carregar contatos:", err));
    }


    // 2. FUNÇÃO PARA CARREGAR AS MENSAGENS (VIA POST)
    function carregarMensagens() {
        const destinatarioId = destinatarioIdInput.value;
        if (!destinatarioId) return;

        const formData = new FormData();
        formData.append("destinatario_id", destinatarioId);

        fetch(`${URL_BASE}/chat/buscar`, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(mensagens => {
            const estavaNoFinal = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 50;
            chatBox.innerHTML = "";

            if (Array.isArray(mensagens)) {
                mensagens.forEach(msg => {
                    const div = document.createElement("div");
                    div.style.padding = "10px 14px";
                    div.style.borderRadius = "12px";
                    div.style.maxWidth = "70%";
                    div.style.fontSize = "14px";
                    div.style.lineHeight = "1.4";
                    div.style.wordBreak = "break-word";

                    if (msg.remetente_id == remetenteId) {
                        div.style.alignSelf = "flex-end";
                        div.style.background = "#4f46e5";
                        div.style.color = "#fff";
                        div.style.borderRadius = "12px 12px 0 12px";
                    } else {
                        div.style.alignSelf = "flex-start";
                        div.style.background = "#f1f1f1";
                        div.style.color = "#333";
                        div.style.borderRadius = "12px 12px 12px 0";
                    }

                    div.textContent = msg.mensagem;
                    chatBox.appendChild(div);
                });
            }

            if (estavaNoFinal) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        })
        .catch(err => console.error("Erro ao buscar histórico:", err));
    }

    // 3. ENVIAR NOVA MENSAGEM VIA AJAX
    chatForm.addEventListener("submit", (e) => {
        e.preventDefault();
        
        const destinatarioId = destinatarioIdInput.value;
        const textoMensagem = mensagemInput.value.trim();

        if (!textoMensagem || !destinatarioId) return;

        const formData = new FormData();
        formData.append("destinatario_id", destinatarioId);
        formData.append("mensagem", textoMensagem);

        mensagemInput.value = "";

        fetch(`${URL_BASE}/chat/enviar`, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                carregarMensagens();
            }
        })
        .catch(err => console.error("Erro ao enviar mensagem:", err));
    });

    function iniciarChat() {
        if (intervaloChat) clearInterval(intervaloChat);
        carregarMensagens();
        intervaloChat = setInterval(carregarMensagens, 2000);
    }

    carregarUsuariosContatos();
});
