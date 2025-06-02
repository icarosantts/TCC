<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();

// Verifica se o usuário está logado e se é do tipo técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
require_once 'conexao.php';

// Obtém o ID do técnico da sessão
$tecnico_id = $_SESSION['usuario_id'];

// Busca os dados do técnico no banco de dados
$query = "SELECT * FROM tecnicos WHERE id_tecnico = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tecnico = $result->fetch_assoc();
} else {
    echo "Erro: Técnico não encontrado.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Técnico - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .btn-ajuda {
            background: #f0f0f0;
            border: 1px solid #ddd;
            padding: 5px 10px;
            margin-top: 5px;
            cursor: pointer;
            font-size: 0.8em;
        }
        #whatsapp-help {
            background: #f8f8f8;
            padding: 10px;
            margin: 10px 0;
            border-left: 3px solid #25D366;
        }
        .whatsapp-link {
            color: #25D366;
            text-decoration: none;
            font-weight: bold;
        }
        .whatsapp-link:hover {
            text-decoration: underline;
        }
        input[type="url"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        .secao {
            display: none;
        }
        .secao.ativa {
            display: block;
        }

        .calendar-container {
    max-width: 800px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}

.calendar-day-header {
    text-align: center;
    font-weight: bold;
    padding: 10px;
    background-color: #f0f0f0;
}

.calendar-day {
    min-height: 80px;
    border: 1px solid #ddd;
    padding: 5px;
    position: relative;
}

.calendar-day.empty {
    background-color: #f9f9f9;
}

.calendar-day:hover {
    background-color: #f0f0f0;
}

.day-number {
    font-weight: bold;
    margin-bottom: 5px;
}

.event-indicator {
    display: block;
    background-color: #4CAF50;
    color: white;
    font-size: 12px;
    padding: 2px 5px;
    margin: 2px 0;
    border-radius: 3px;
    cursor: pointer;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.has-events {
    background-color: #e8f5e9;
}

.current-day {
    background-color: #bbdefb;
    font-weight: bold;
}

    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.php">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="#" onclick="mostrarSecao('perfil')" class="ativa">Meu Perfil</a></li>
                    <li><a href="#" onclick="mostrarSecao('ajuda')">Ajuda</a></li>
                    <li><a href="logout.php">Sair</a></li>
                    <li class="configuracoes-dropdown">
                        <a href="#">Configurações</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="mostrarSecao('mudar-senha')">Mudar Senha</a></li>
                            <li><a href="#" onclick="mostrarSecao('calendario')">Calendário</a></li>
                            <li><a href="#" onclick="mostrarSecao('excluir-conta')">Excluir Conta</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="perfil" class="secao ativa">
            <h2>Perfil do Técnico</h2>

            <div id="perfil-exibicao">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($tecnico['nome']); ?></p>
                <p><strong>WhatsApp:</strong> 
                    <a href="<?php echo htmlspecialchars($tecnico['whatsapp_link']); ?>" class="whatsapp-link" target="_blank">
                        Contato via WhatsApp
                    </a>
                </p>
                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($tecnico['email']); ?></p>
                <p><strong>Área de Atuação:</strong> <?php echo htmlspecialchars($tecnico['especialidades']); ?></p>
                <p><strong>Valor de Serviços:</strong> R$ <?php echo number_format($tecnico['valor_servico'], 2, ',', '.'); ?></p>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($tecnico['descricao_tecnico']); ?></p>
                
                <button type="button" onclick="mostrarEdicao()">Editar Perfil</button>
                <p id="mensagem-edicao" style="color: green;"></p>
            </div>

            <form id="form-edicao" action="atualizar_perfil_tecnico.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($tecnico['nome']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="whatsapp_link">Link do WhatsApp:</label>
                    <input type="url" id="whatsapp_link" name="whatsapp_link" 
                           value="<?php echo htmlspecialchars($tecnico['whatsapp_link']); ?>"
                           placeholder="https://wa.me/5511999999999" required>
                    <small>Formato: https://wa.me/5511999999999 (incluindo código do país e DDD)</small>
                    <button type="button" class="btn-ajuda" onclick="mostrarAjudaWhatsApp()">Como obter meu link?</button>
                    <div id="whatsapp-help" style="display:none;">
                        <p>1. Abra o WhatsApp no seu celular</p>
                        <p>2. Vá em Configurações > Conta</p>
                        <p>3. Seu número aparecerá no formato: 55DDDNNNNNNNN</p>
                        <p>4. Insira no campo: https://wa.me/55DDDNNNNNNNN</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($tecnico['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="especialidades">Área de Atuação:</label>
                    <input type="text" id="especialidades" name="especialidades" 
                           value="<?php echo htmlspecialchars($tecnico['especialidades']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="valor_servico">Valor de Serviços (R$):</label>
                    <input type="number" id="valor_servico" name="valor_servico" 
                           value="<?php echo htmlspecialchars($tecnico['valor_servico']); ?>" 
                           step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="descricao_tecnico">Descrição:</label>
                    <textarea id="descricao_tecnico" name="descricao_tecnico" rows="4" required><?php 
                        echo htmlspecialchars($tecnico['descricao_tecnico']); 
                    ?></textarea>
                </div>
                
                <button type="submit">Salvar Alterações</button>
                <button type="button" onclick="cancelarEdicao()">Cancelar</button>
            </form>
        </section>

        <section id="ajuda" class="secao">
            <h2>Ajuda</h2>
            <p>Precisa de ajuda? Entre em contato conosco:</p>
            <ul>
                <li><strong>E-mail:</strong> suporte@conecttecs.com</li>
                <li><strong>Telefone:</strong> (00) 1234-5678</li>
            </ul>
        </section>

        <section id="mudar-senha" class="secao">
            <h3>Mudar Senha</h3>
            <form action="mudar_senha_tecnico.php" method="post">
                <div class="form-group">
                    <label for="senha_atual">Senha Atual:</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" id="nova_senha" name="nova_senha" required>
                </div>
                <button type="submit">Atualizar Senha</button>
            </form>
        </section>

        <section id="calendario" class="secao">
            <h3>Calendário de Serviços</h3>
            <div class="calendar-container">
                <div class="calendar-header">
                    <button id="prev-month">&lt; Mês Anterior</button>
                    <h2 id="current-month">Abril 2023</h2>
                    <button id="next-month">Próximo Mês &gt;</button>
                </div>
                <div class="calendar-grid" id="calendar-grid">
                    <!-- Os dias serão gerados dinamicamente via JavaScript -->
                </div>
            </div>
            
            <div id="event-form-container" style="display:none;">
                <h3>Adicionar/Editar Serviço</h3>
                <form id="event-form">
                    <input type="hidden" id="event-id">
                    <div class="form-group">
                        <label for="event-date">Data:</label>
                        <input type="date" id="event-date" required>
                    </div>
                    <div class="form-group">
                        <label for="event-time">Hora:</label>
                        <input type="time" id="event-time" required>
                    </div>
                    <div class="form-group">
                        <label for="event-title">Título:</label>
                        <input type="text" id="event-title" required>
                    </div>
                    <div class="form-group">
                        <label for="event-description">Descrição:</label>
                        <textarea id="event-description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="event-client">Cliente (opcional):</label>
                        <input type="text" id="event-client">
                    </div>
                    <div class="form-group">
                        <label for="event-address">Endereço:</label>
                        <input type="text" id="event-address">
                    </div>
                    <button type="submit">Salvar</button>
                    <button type="button" id="cancel-event">Cancelar</button>
                    <button type="button" id="delete-event" style="display:none;">Excluir</button>
                </form>
            </div>
            
            <div id="event-details" style="display:none;">
                <h3>Detalhes do Serviço</h3>
                <p><strong>Data:</strong> <span id="detail-date"></span></p>
                <p><strong>Hora:</strong> <span id="detail-time"></span></p>
                <p><strong>Título:</strong> <span id="detail-title"></span></p>
                <p><strong>Descrição:</strong> <span id="detail-description"></span></p>
                <p><strong>Cliente:</strong> <span id="detail-client"></span></p>
                <p><strong>Endereço:</strong> <span id="detail-address"></span></p>
                <button type="button" id="edit-event">Editar</button>
                <button type="button" id="close-details">Fechar</button>
            </div>
        </section>

        <section id="excluir-conta" class="secao">
            <h3>Excluir Conta</h3>
            <p>Cuidado! Esta ação é irreversível.</p>
            <form action="excluir_conta_tecnico.php" method="post">
                <div class="form-group">
                    <label for="confirmacao">Digite "EXCLUIR" para confirmar:</label>
                    <input type="text" id="confirmacao" name="confirmacao" required>
                </div>
                <button type="submit">Excluir Conta</button>
            </form>
        </section>

        <section id="agendamentos-confirmados" class="secao">
            <h2>Agendamentos Confirmados</h2>
            <div id="agendamentos-confirmados-list"></div>
        </section>

        <section id="pedidos-agendamentos" class="secao">
            <h2>Pedidos de Agendamentos</h2>
            <div id="pedidos-agendamentos-list"></div>
        </section>
    </main>

    <script>
        // Função para mostrar o formulário de edição
        function mostrarEdicao() {
            document.getElementById("perfil-exibicao").style.display = "none";
            document.getElementById("form-edicao").style.display = "block";
        }

        // Função para cancelar a edição
        function cancelarEdicao() {
            document.getElementById("perfil-exibicao").style.display = "block";
            document.getElementById("form-edicao").style.display = "none";
            document.getElementById("mensagem-edicao").textContent = "";
        }

        // Função para mostrar ajuda do WhatsApp
        function mostrarAjudaWhatsApp() {
            const helpDiv = document.getElementById('whatsapp-help');
            helpDiv.style.display = helpDiv.style.display === 'none' ? 'block' : 'none';
        }

        // Validação do WhatsApp em tempo real
        document.getElementById('whatsapp_link').addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && !/^https:\/\/wa\.me\/\d{10,15}$/.test(value)) {
                alert('Formato inválido! Use: https://wa.me/5511999999999');
                this.focus();
            }
        });

        // Envio do formulário via AJAX
        document.getElementById('form-edicao').addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Validação do WhatsApp
            const whatsappLink = document.getElementById('whatsapp_link').value;
            if (!/^https:\/\/wa\.me\/\d{10,15}$/.test(whatsappLink)) {
                alert('Por favor, insira um link válido do WhatsApp no formato: https://wa.me/5511999999999');
                return false;
            }

            const formData = new FormData(this);

            fetch('atualizar_perfil_tecnico.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('mensagem-edicao').textContent = data;
                setTimeout(() => {
                    location.reload(); // Recarrega a página para atualizar os dados
                }, 1500);
            })
            .catch(error => {
                console.error('Erro ao salvar:', error);
                document.getElementById('mensagem-edicao').textContent = "Erro ao atualizar perfil.";
            });
        });

        // Função para mostrar seções
        function mostrarSecao(secaoId) {
            // Oculta todas as seções
            document.querySelectorAll('.secao').forEach(secao => {
                secao.classList.remove('ativa');
            });
            
            // Mostra a seção solicitada
            document.getElementById(secaoId).classList.add('ativa');
            
            // Atualiza o menu ativo
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.classList.remove('ativa');
            });
            event.target.classList.add('ativa');
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            // Validação do formulário
            document.getElementById('form-edicao').addEventListener('submit', function(e) {
                const whatsappLink = document.getElementById('whatsapp_link').value;
                
                if (!/^https:\/\/wa\.me\/\d{10,15}$/.test(whatsappLink)) {
                    e.preventDefault();
                    alert('Por favor, insira um link válido do WhatsApp no formato: https://wa.me/5511999999999');
                    return false;
                }
                return true;
            });
        });

        // Variáveis globais
let currentDate = new Date();
let events = [];

// Função para carregar eventos do servidor
async function loadEvents() {
    try {
        const response = await fetch('get_events.php?tecnico_id=<?php echo $tecnico_id; ?>');
        if (response.ok) {
            events = await response.json();
            renderCalendar();
        } else {
            console.error('Erro ao carregar eventos');
        }
    } catch (error) {
        console.error('Erro:', error);
    }
}

// Função para renderizar o calendário
function renderCalendar() {
    const monthNames = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
    document.getElementById('current-month').textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    
    const daysInMonth = lastDay.getDate();
    const startingDay = firstDay.getDay();
    
    const today = new Date();
    const isCurrentMonth = currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear();
    
    let calendarHtml = '';
    
    // Cabeçalho dos dias da semana
    const weekdays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    weekdays.forEach(day => {
        calendarHtml += `<div class="calendar-day-header">${day}</div>`;
    });
    
    // Dias vazios no início do mês
    for (let i = 0; i < startingDay; i++) {
        calendarHtml += `<div class="calendar-day empty"></div>`;
    }
    
    // Dias do mês
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = events.filter(event => event.date === dateStr);
        const isCurrentDay = isCurrentMonth && day === today.getDate();
        
        let dayClass = 'calendar-day';
        if (isCurrentDay) dayClass += ' current-day';
        if (dayEvents.length > 0) dayClass += ' has-events';
        
        calendarHtml += `<div class="${dayClass}" data-date="${dateStr}">`;
        calendarHtml += `<div class="day-number">${day}</div>`;
        
        // Mostra até 2 eventos por dia (para não sobrecarregar a visualização)
        dayEvents.slice(0, 2).forEach(event => {
            calendarHtml += `<div class="event-indicator" data-id="${event.id}" title="${event.title} - ${event.time}">${event.title}</div>`;
        });
        
        // Mostra um indicador se houver mais eventos
        if (dayEvents.length > 2) {
            calendarHtml += `<div class="event-indicator">+${dayEvents.length - 2} mais</div>`;
        }
        
        calendarHtml += `</div>`;
    }
    
    document.getElementById('calendar-grid').innerHTML = calendarHtml;
    
    // Adiciona eventos de clique aos dias
    document.querySelectorAll('.calendar-day:not(.empty)').forEach(day => {
        day.addEventListener('click', function() {
            const date = this.getAttribute('data-date');
            const dayEvents = events.filter(event => event.date === date);
            
            if (dayEvents.length > 0) {
                showDayEvents(date, dayEvents);
            } else {
                addNewEvent(date);
            }
        });
    });
    
    // Adiciona eventos de clique aos indicadores de eventos
    document.querySelectorAll('.event-indicator').forEach(indicator => {
        indicator.addEventListener('click', function(e) {
            e.stopPropagation();
            const eventId = this.getAttribute('data-id');
            const event = events.find(e => e.id == eventId);
            if (event) showEventDetails(event);
        });
    });
}

// Função para mostrar eventos de um dia específico
function showDayEvents(date, dayEvents) {
    // Implementação de uma modal ou lista de eventos para o dia
    alert(`Eventos em ${date}:\n\n${dayEvents.map(e => `- ${e.time}: ${e.title}`).join('\n')}`);
}

// Função para adicionar novo evento
function addNewEvent(date) {
    document.getElementById('event-form-container').style.display = 'block';
    document.getElementById('event-details').style.display = 'none';
    
    const form = document.getElementById('event-form');
    form.reset();
    document.getElementById('event-id').value = '';
    document.getElementById('event-date').value = date;
    document.getElementById('delete-event').style.display = 'none';
}

// Função para mostrar detalhes do evento
function showEventDetails(event) {
    document.getElementById('event-details').style.display = 'block';
    document.getElementById('event-form-container').style.display = 'none';
    
    document.getElementById('detail-date').textContent = formatDate(event.date);
    document.getElementById('detail-time').textContent = event.time;
    document.getElementById('detail-title').textContent = event.title;
    document.getElementById('detail-description').textContent = event.description || 'Nenhuma descrição';
    document.getElementById('detail-client').textContent = event.client || 'Não especificado';
    document.getElementById('detail-address').textContent = event.address || 'Não especificado';
    
    document.getElementById('edit-event').onclick = function() {
        editEvent(event);
    };
}

// Função para editar evento
function editEvent(event) {
    document.getElementById('event-form-container').style.display = 'block';
    document.getElementById('event-details').style.display = 'none';
    
    const form = document.getElementById('event-form');
    form.reset();
    
    document.getElementById('event-id').value = event.id;
    document.getElementById('event-date').value = event.date;
    document.getElementById('event-time').value = event.time;
    document.getElementById('event-title').value = event.title;
    document.getElementById('event-description').value = event.description || '';
    document.getElementById('event-client').value = event.client || '';
    document.getElementById('event-address').value = event.address || '';
    
    document.getElementById('delete-event').style.display = 'inline-block';
}

// Função para formatar data
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('pt-BR');
}

// Event Listeners
document.getElementById('prev-month').addEventListener('click', function() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

document.getElementById('next-month').addEventListener('click', function() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

document.getElementById('cancel-event').addEventListener('click', function() {
    document.getElementById('event-form-container').style.display = 'none';
});

document.getElementById('close-details').addEventListener('click', function() {
    document.getElementById('event-details').style.display = 'none';
});

document.getElementById('event-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const eventData = {
        id: document.getElementById('event-id').value,
        tecnico_id: <?php echo $tecnico_id; ?>,
        date: document.getElementById('event-date').value,
        time: document.getElementById('event-time').value,
        title: document.getElementById('event-title').value,
        description: document.getElementById('event-description').value,
        client: document.getElementById('event-client').value,
        address: document.getElementById('event-address').value
    };
    
    try {
        const response = await fetch('save_event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventData)
        });
        
        if (response.ok) {
            alert('Evento salvo com sucesso!');
            document.getElementById('event-form-container').style.display = 'none';
            loadEvents(); // Recarrega os eventos
        } else {
            alert('Erro ao salvar evento');
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao salvar evento');
    }
});

document.getElementById('delete-event').addEventListener('click', async function() {
    if (confirm('Tem certeza que deseja excluir este evento?')) {
        const eventId = document.getElementById('event-id').value;
        
        try {
            const response = await fetch('delete_event.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: eventId })
            });
            
            if (response.ok) {
                alert('Evento excluído com sucesso!');
                document.getElementById('event-form-container').style.display = 'none';
                loadEvents(); // Recarrega os eventos
            } else {
                alert('Erro ao excluir evento');
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao excluir evento');
        }
    }
});

// Inicializa o calendário quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
});

    </script>
</body>
</html>