const tecnicos = [
    {
        nome: "João Silva",
        avaliacao: 4.5,
        valorServico: 100.00,
        area: "elétrica",
        quantidadeServicos: 50,
        apresentacao: "Técnico elétrico com 10 anos de experiência.",
    },
    {
        nome: "Maria Oliveira",
        avaliacao: 5.0,
        valorServico: 120.00,
        area: "informática",
        quantidadeServicos: 30,
        apresentacao: "Especialista em suporte técnico e redes.",
    },
    // Adicione mais técnicos conforme necessário
];

function mostrarTecnicos() {
    const tecnicosList = document.getElementById('tecnicos-list');
    tecnicosList.innerHTML = ''; // Limpa a lista atual

    // Exibe todos os técnicos sem aplicar filtros
    tecnicos.forEach(tecnico => {
        const card = document.createElement('div');
        card.classList.add('tecnico-card');
        card.innerHTML = `
            <h3>${tecnico.nome}</h3>
            <p>Avaliação: ${tecnico.avaliacao} / 5</p>
            <p>Valor do Serviço: R$ ${tecnico.valorServico.toFixed(2)}</p>
            <p>Área Técnica: ${tecnico.area}</p>
            <p>Serviços Realizados: ${tecnico.quantidadeServicos}</p>
            <p>${tecnico.apresentacao}</p>
            <div class="button-container">
                <button onclick="enviarMensagem('${tecnico.nome}')">Enviar Mensagem</button>
                <button onclick="agendarServico('${tecnico.nome}')">Agendar Serviço</button>
            </div>
        `;
        tecnicosList.appendChild(card);
    });
}

function enviarMensagem(nome) {
    const mensagem = prompt(`Digite sua mensagem para ${nome}:`);
    if (mensagem) {
        fetch('/enviar-mensagem.php', { // Certifique-se de que este caminho está correto
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ nome, mensagem })
        })
        .then(response => response.json())
        .then(data => {
            alert(`Resposta do servidor: ${data.message}`);
        })
        .catch(error => {
            console.error('Erro ao enviar mensagem:', error);
        });
    } else {
        alert("Mensagem não enviada.");
    }
}

function agendarServico(nome) {
    const dataHora = prompt(`Digite a data e hora para agendar o serviço com ${nome} (ex: 2024-10-30 14:00):`);
    if (dataHora) {
        fetch('/agendar-servico.php', { // Certifique-se de que este caminho está correto
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ nome, dataHora })
        })
        .then(response => response.json())
        .then(data => {
            alert(`Resposta do servidor: ${data.message}`);
        })
        .catch(error => {
            console.error('Erro ao agendar serviço:', error);
        });
    } else {
        alert("Agendamento não realizado.");
    }
}

// Chama a função para mostrar os técnicos quando a página carrega
document.addEventListener('DOMContentLoaded', mostrarTecnicos);
