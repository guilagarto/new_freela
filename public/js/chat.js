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

    // 1. FUNÇÃO PARA CARREGAR OS USUÁRIOS NA BARRA LATERAL DINAMICAMENTE
    function carregarUsuariosContatos() {
        fetch('./chat/usuarios', {
            method: "POST"
        })
        .then(response => response.json())
        .then(usuarios => {
            usuariosLista.innerHTML = ""; // Limpa o conteúdo estático antigo

            if (usuarios.length === 0) {
                usuariosLista.innerHTML = `<div style="padding: 15px; color: #999; font-size: 14px;">Nenhum contato encontrado.</div>`;
                return;
            }

            usuarios.forEach((user, index) => {
                const div = document.createElement("div");
                div.classList.add("usuario-item");
                div.setAttribute("data-id", user.id);
                
                // Estilo base do item da lista
                div.style.padding = "15px";
                div.style.borderBottom = "1px solid #f9f9f9";
                div.style.cursor = "pointer";
                div.style.fontWeight = "500";
                div.style.color = "#333";
                div.style.transition = "background 0.2s";

                // Se o nome no banco vier em outra coluna, use user.nome ao invés de user.name
                const nomeUsuario = user.name || user.nome || `Usuário ${user.id}`;
                div.innerHTML = `👤 ${nomeUsuario}`;

                // Define o primeiro usuário da lista como ativo por padrão no carregamento inicial
                if (!destinatarioIdInput.value && index === 0) {
                    div.classList.add("active");
                    div.style.background = "#eef2f3";
                    div.style.color = "#007bff";
                    div.style.fontWeight = "600";
                    destinatarioIdInput.value = user.id;
                    nomeChatAtivo.textContent = nomeUsuario;
                } else if (destinatarioIdInput.value == user.id) {
                    div.classList.add("active");
                    div.style.background = "#eef2f3";
                    div.style.color = "#007bff";
                    div.style.fontWeight = "600";
                }

                // Evento de clique para trocar de conversa
                div.addEventListener("click", function() {
                    document.querySelectorAll(".usuario-item").forEach(i => {
                        i.classList.remove("active");
                        i.style.background = "transparent";
                        i.style.color = "#333";
                        i.style.fontWeight = "500";
                    });
                    
                    this.classList.add("active");
                    this.style.background = "#eef2f3";
                    this.style.color = "#007bff";
                    this.style.fontWeight = "600";

                    destinatarioIdInput.value = this.getAttribute("data-id");
                    nomeChatAtivo.textContent = nomeUsuario;
                    
                    iniciarChat();
                });

                usuariosLista.appendChild(div);
            });

            // Após desenhar a lista lateral de usuários, inicia a busca de mensagens
            iniciarChat();
        })
        .catch(err => console.error("Erro ao carregar lista de contatos:", err));
    }

    // 2. FUNÇÃO PARA CARREGAR AS MENSAGENS DO BANCO
    function carregarMensagens() {
        const destinatarioId = destinatarioIdInput.value;
        if (!destinatarioId) return;

        const formData = new FormData();
        formData.append("destinatario_id", destinatarioId);

        fetch('./chat/buscar', {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(mensagens => {
                const estavaNoFinal = chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 50;
                chatBox.innerHTML = "";

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
                        div.style.background = "#007bff";
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

                if (estavaNoFinal) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            })
            .catch(err => console.error("Erro ao carregar mensagens:", err));
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

        fetch('./chat/enviar', {
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

    // 4. GERENCIA O INTERVALO DE TEMPO REAL
    function iniciarChat() {
        if (intervaloChat) clearInterval(intervaloChat);
        carregarMensagens();
        intervaloChat = setInterval(carregarMensagens, 2000); // Polling a cada 2s
    }

    // Inicialização do Chat: Busca os usuários primeiro
    carregarUsuariosContatos();
});
