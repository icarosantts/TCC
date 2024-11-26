<?php
session_start();

// Verifica se o usuário está logado e se é do tipo técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    // Se não estiver logado ou não for técnico, redireciona para a página de login
    header("Location: login.html");
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

// Verifica se encontrou o técnico
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
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.html">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="#" onclick="mostrarSecao('painel')">Painel</a></li>
                    <li><a href="#" onclick="mostrarSecao('perfil')">Meu Perfil</a></li>
                    <li><a href="#" onclick="mostrarSecao('mensagens')">Mensagens</a></li>
                    <li><a href="#" onclick="mostrarSecao('ajuda')">Ajuda</a></li>
                    <li><a href="logout.php">Sair</a></li>
                    <li class="configuracoes-dropdown">
                        <a href="#">Configurações</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="mostrarSecao('mudar-senha')">Mudar Senha</a></li>
                            <li><a href="#" onclick="mostrarSecao('alterar-email')">Alterar E-mail</a></li>
                            <li><a href="#" onclick="mostrarSecao('calendario')">Calendário</a></li>
                            <li><a href="#" onclick="mostrarSecao('excluir-conta')">Excluir Conta</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="painel" class="secao" style="display: none;">
            <h2>Painel</h2>
            <div class="icone-agendamentos">
                <button onclick="mostrarSecao('agendamentos-confirmados')">Agendamentos Confirmados</button>
                <button onclick="mostrarSecao('pedidos-agendamentos')">Pedidos de Agendamentos</button>
            </div>
        </section>

        <section id="perfil" class="secao">
            <h2>Meu perfil</h2>
            <div id="perfil-info">
                <!-- Exibe a foto do técnico -->
                <img src="<?php echo $foto_para_exibir; ?>" alt="Foto do Técnico" />
                
                <!-- Botão de upload de nova foto -->
                <form id="upload-foto-form" enctype="multipart/form-data">
                    <input type="file" id="upload-foto" name="foto" accept="image/*">
                    <button type="button" onclick="enviarFoto()">Atualizar Foto</button>
                </form>

                <!-- Exibe o nome e a descrição -->
                <h2 id="nome-tecnico"><?php echo htmlspecialchars($tecnico['nome']); ?></h2>
                <p><strong>Área de Ação:</strong> <?php echo htmlspecialchars($tecnico['especialidades']); ?></p>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($tecnico['descricao_tecnico']); ?></p>
                <p><strong>Valores de Serviço:</strong> <?php echo htmlspecialchars($tecnico['valor_servico']); ?></p>
            </div>
        </section>


        <section id="mensagens" class="secao" style="display: none;">
            <h2>Mensagens</h2>
            <div id="mensagens-list"></div>
        </section>

        <section id="ajuda" class="secao" style="display: none;">
            <h2>Ajuda</h2>
            <p>Precisa de ajuda? Consulte nossas informações de suporte ou entre em contato conosco.</p>
        </section>

        <section id="mudar-senha" class="secao" style="display: none;">
            <h2>Mudar Senha</h2>
            <!-- Formulário para mudar a senha -->
        </section>

        <section id="alterar-email" class="secao" style="display: none;">
            <h2>Alterar E-mail</h2>
            <!-- Formulário para alterar o e-mail -->
        </section>

        <section id="calendario" class="secao" style="display: none;">
            <h2>Calendário</h2>
            <p>Visualize seus agendamentos em um calendário interativo.</p>
        </section>

        <section id="excluir-conta" class="secao" style="display: none;">
            <h2>Excluir Conta</h2>
            <p>Cuidado! Esta ação é irreversível.</p>
            <!-- Botão ou formulário para confirmar exclusão da conta -->
        </section>

        <section id="agendamentos-confirmados" class="secao" style="display: none;">
            <h2>Agendamentos Confirmados</h2>
            <div id="agendamentos-confirmados-list"></div>
        </section>

        <section id="pedidos-agendamentos" class="secao" style="display: none;">
            <h2>Pedidos de Agendamentos</h2>
            <div id="pedidos-agendamentos-list"></div>
        </section>
    </main>

    <script>

        document.getElementById('form-foto').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('atualizar-foto-perfil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza a imagem de perfil
                    document.getElementById('perfil-foto').src = data.foto_url;
                } else {
                    alert('Erro ao atualizar a foto de perfil: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar foto:', error);
            });
        });

        // JavaScript para exibir nova foto após upload
        document.getElementById('upload-foto').addEventListener('change', function(event) {
            const file = event.target.files[0];
            
            if (file) {
                const formData = new FormData();
                formData.append('foto', file);
                
                // Envia a imagem para o servidor
                fetch('/atualizar-foto-perfil.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualiza a imagem de perfil na página
                        document.getElementById('perfil-foto').src = data.foto_url;
                    } else {
                        alert('Erro ao atualizar a foto de perfil');
                    }
                })
                .catch(error => {
                    console.error('Erro ao enviar foto:', error);
                });
            }
        });

        function enviarFoto() {
            const fotoInput = document.getElementById('upload-foto');
            const file = fotoInput.files[0];

            if (file) {
                const formData = new FormData();
                formData.append('foto', file);

                fetch('/atualizar-foto-perfil.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('foto-perfil').src = data.foto_url;
                    } else {
                        alert('Erro ao atualizar a foto de perfil');
                    }
                })
                .catch(error => console.error('Erro ao enviar foto:', error));
            } else {
                alert('Por favor, selecione uma foto.');
            }
        }

        function mostrarSecao(secaoId) {
            const secoes = document.querySelectorAll('.secao');
            secoes.forEach(secao => secao.style.display = 'none');
            document.getElementById(secaoId).style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', () => {
            mostrarSecao('perfil');
            carregarPerfil();
            carregarMensagens();
            carregarAgendamentos();
        });

        function carregarPerfil() {
            fetch('/buscar-perfil-tecnico.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nome-tecnico').textContent = data.nome;
                    document.getElementById('area-tecnica').textContent = data.area_tecnica;
                    document.getElementById('descricao-tecnico').textContent = data.descricao;
                    document.getElementById('valor-servico').textContent = data.valor_servico || "Não definido";
                    document.getElementById('foto-perfil').src = data.foto_perfil || 'default-profile.png';
                })
                .catch(error => console.error('Erro ao carregar perfil:', error));
        }

        // Função para exibir o campo de edição
        function editarCampo(campo) {
            const campoEdicao = document.getElementById('editar-' + campo);
            const campoExibicao = document.getElementById(campo);

            // Exibe o campo de edição e esconde o valor atual
            campoExibicao.style.display = 'none';
            campoEdicao.style.display = 'inline-block';
            campoEdicao.value = campoExibicao.textContent.trim(); // Coloca o valor atual no campo de edição
        }


        // Função para salvar as alterações
        function salvarAlteracoes() {
            const nome = document.getElementById('editar-nome').value;
            const descricao = document.getElementById('editar-descricao').value;

            const dados = {
                nome: nome,
                descricao: descricao
            };

            fetch('/atualizar-perfil-tecnico.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dados),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza os dados na página
                    document.getElementById('nome-tecnico').textContent = nome;
                    document.getElementById('descricao-tecnico').textContent = descricao;
                    // Esconde o campo de edição
                    document.getElementById('editar-nome').style.display = 'none';
                    document.getElementById('editar-descricao').style.display = 'none';
                } else {
                    alert('Erro ao salvar alterações');
                }
            })
            .catch(error => console.error('Erro ao salvar alterações:', error));
        }


        function cancelarEdicoes() {
            document.querySelectorAll('[id^="editar-"]').forEach(input => input.style.display = 'none');
            carregarPerfil();
        }
        document.addEventListener('DOMContentLoaded', () => {
    mostrarSecao('perfil'); // Exibir a seção do perfil por padrão
    carregarPerfil(); // Carregar o perfil do técnico
});

        function carregarPerfil() {
            fetch('/buscar-perfil-tecnico.php') // Chama o script PHP para buscar os dados do perfil
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Erro ao carregar perfil: ' + data.error);
                        return;
                    }
                    // Preencher os dados do perfil
                    document.getElementById('nome-tecnico').textContent = data.nome;
                    document.getElementById('descricao-tecnico').textContent = data.descricao;
                    document.getElementById('foto-perfil').src = data.foto || 'default-profile.png';
                })
                .catch(error => console.error('Erro ao carregar perfil:', error));
        }

        document.getElementById('upload-foto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    
    if (file) {
        const formData = new FormData();
        formData.append('foto', file);
        
        // Enviar a imagem para o servidor
        fetch('/atualizar-foto-perfil.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Atualiza a imagem de perfil na página
                document.getElementById('foto-perfil').src = data.foto_url;
            } else {
                alert('Erro ao atualizar a foto de perfil');
            }
        })
        .catch(error => {
            console.error('Erro ao enviar foto:', error);
        });
    }
});


    </script>
</body>
</html>
