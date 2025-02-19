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
        <h2>Perfil do Técnico</h2>

            <!-- Exibição do perfil -->
            <div id="perfil-exibicao">
                
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($tecnico['nome']); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($tecnico['telefone']); ?></p>
                <p><strong>Área de Atuação:</strong> <?php echo htmlspecialchars($tecnico['especialidades']); ?></p>
                <p><strong>Valor de Serviços:</strong> <?php echo htmlspecialchars($tecnico['valor_servico']); ?></p>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($tecnico['descricao_tecnico']); ?></p>
                
                <button type="button" onclick="mostrarEdicao()">Editar</button>
                <p id="mensagem-edicao" style="color: green;"></p>

            </div>

            <!-- Formulário de edição (oculto inicialmente) -->
            <form id="form-edicao" action="atualizar_perfil.php" method="POST" style="display: none;">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($tecnico['nome']); ?>">

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($tecnico['telefone']); ?>">

                <label for="especialidades">Área de Atuação:</label>
                <input type="text" id="especialidades" name="especialidades" value="<?php echo htmlspecialchars($tecnico['especialidades']); ?>">

                <label for="valor_servico">Valor de Serviços:</label>
                <input type="text" id="valor_servico" name="valor_servico" value="<?php echo htmlspecialchars($tecnico['valor_servico']); ?>">

                <label for="descricao_tecnico">Sua descrição:</label>
                <textarea id="descricao_tecnico" name="descricao_tecnico"><?php echo htmlspecialchars($tecnico['descricao_tecnico']); ?></textarea>

                <button type="submit">Salvar</button>
                <button type="button" onclick="cancelarEdicao()">Cancelar</button>
            </form>

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
        <h3>Mudar Senha</h3>
        <form action="mudar_senha_tecnico.php" method="post">
            <label for="senha_atual">Senha Atual:</label>
            <input type="password" id="senha_atual" name="senha_atual" required><br>
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" required><br>
            <button type="submit">Atualizar Senha</button>
        </form>
        </section>

        <section id="alterar-email" class="secao" style="display: none;">
        <h3>Alterar E-mail</h3>
        <form action="mudar_email_tecnico.php" method="post">
            <label for="novo_email">Novo E-mail:</label>
            <input type="email" id="novo_email" name="novo_email" required><br>
            <button type="submit">Atualizar E-mail</button>
        </form>
        </section>

        <section id="calendario" class="secao" style="display: none;">
        <h3>Calendário</h3>
        <p>Aqui ficará o calendário interativo.</p>
        </section>

        <section id="excluir-conta" class="secao" style="display: none;">
            <h3>Excluir Conta</h3>
            <form action="excluir_conta_tecnico.php" method="post">
                <label for="confirmacao">Digite "EXCLUIR" para confirmar:</label>
                <input type="text" id="confirmacao" name="confirmacao" required><br>
                <button type="submit">Excluir Conta</button>
            </form>
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

        document.getElementById('form-edicao').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o recarregamento da página

            const formData = new FormData(this);

            fetch('atualizar_perfil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('mensagem-edicao').textContent = data;
                cancelarEdicao(); // Volta para a exibição normal após salvar
            })
            .catch(error => {
                console.error('Erro ao salvar:', error);
            });
        });

        function mostrarEdicao() {
            document.getElementById("perfil-exibicao").style.display = "none";
            document.getElementById("form-edicao").style.display = "block";
        }

        function cancelarEdicao() {
            document.getElementById("perfil-exibicao").style.display = "block";
            document.getElementById("form-edicao").style.display = "none";
        }

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
