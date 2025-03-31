<?php
session_start();
include 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
    $certificado = isset($_POST['certificado']) ? $_POST['certificado'] : "nao";
    $cursando = isset($_POST['cursando']) ? $_POST['cursando'] : "nao";
    $especialidades = isset($_POST['especialidades']) && is_array($_POST['especialidades']) ? implode(",", $_POST['especialidades']) : "";
    $valor_servico = trim($_POST['valor_servico']);
    $descricao_tecnico = trim($_POST['descricao_tecnico']);

    // Diretório de upload
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Processa uploads de arquivos
    $documento_certificado = "";
    $documento_matricula = "";

    function uploadArquivo($file, $upload_dir) {
        if (isset($file) && $file['error'] == UPLOAD_ERR_OK) {
            $target_file = $upload_dir . basename($file["name"]);
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $target_file;
            }
        }
        return "";
    }

    if ($certificado == "sim") {
        $documento_certificado = uploadArquivo($_FILES['documento-certificado'], $upload_dir);
    }
    if ($cursando == "sim") {
        $documento_matricula = uploadArquivo($_FILES['documento-matricula'], $upload_dir);
    }

    // Insere os dados no banco
    $sql = "INSERT INTO tecnicos (nome, telefone, email, senha, certificado, documento_certificado, cursando, documento_matricula, especialidades, valor_servico, descricao_tecnico) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssssss", $nome, $telefone, $email, $senha, $certificado, $documento_certificado, $cursando, $documento_matricula, $especialidades, $valor_servico, $descricao_tecnico);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Erro ao cadastrar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Erro na preparação da consulta: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Técnico - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.html">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="index.html">Página Inicial</a></li>
                    <li><a href="cadastro_cliente.php">Estou Procurando Técnico</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section>
            <h2>Cadastro de Técnico</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <p>Preencha os dados abaixo para se cadastrar como técnico.</p>
            <form id="cadastro-tecnico" action="cadastro_tecnico.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <div class="form-group">
                    <label>Possui Certificado Técnico?</label>
                    <label><input type="radio" name="certificado" value="sim" required> Sim</label>
                    <label><input type="radio" name="certificado" value="nao" required> Não</label>
                </div>

                <div class="form-group certificado-documento" style="display: none;">
                    <label for="documento-certificado">Documento do Certificado (PDF, DOC, JPEG, PNG - Máx: 2MB):</label>
                    <input type="file" id="documento-certificado" name="documento-certificado" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png">
                </div>

                <div class="form-group cursando" style="display: none;">
                    <label>Está cursando?</label>
                    <label><input type="radio" name="cursando" value="sim"> Sim</label>
                    <label><input type="radio" name="cursando" value="nao"> Não</label>
                </div>

                <div class="form-group documento-matricula" style="display: none;">
                    <label for="documento-matricula">Atestado de Matrícula (PDF, DOC, JPEG, PNG - Máx: 2MB):</label>
                    <input type="file" id="documento-matricula" name="documento-matricula" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png">
                </div>

                <div class="form-group">
                    <label for="especialidades">Especialidades:</label>
                    <select id="especialidades" name="especialidades[]" multiple required>
                        <option value="Alimentos">Técnico em Alimentos</option>
                        <option value="automacao-industrial">Técnico em Automação Industrial</option>
                        <option value="mecatronica">Técnico em Mecatrônica</option>
                        <option value="manutencao-automotiva">Técnico em Manutenção Automotiva</option>
                        <option value="edificacoes">Técnico em Edificações</option>
                        <option value="design-calcados">Técnico em Design de Calçados</option>
                        <option value="eletroeletronica">Técnico em Eletroeletrônica</option>
                        <option value="eletronica">Técnico em Eletrônica</option>
                        <option value="eletrotecnica">Técnico em Eletrotécnica</option>
                        <option value="energia-renovavel">Técnico em Sistemas de Energia Renovável</option>
                        <option value="qualidade">Técnico em Qualidade</option>
                        <option value="multimidia">Técnico em Multimídia</option>
                        <option value="comunicacao-visual">Técnico em Comunicação Visual</option>
                        <option value="impressao-offset">Técnico em Impressão Offset</option>
                        <option value="impressao-rotografica-flexografica">Técnico em Impressão Rotográfica e Flexográfica</option>
                        <option value="processos-graficos">Técnico em Processos Gráficos</option>
                        <option value="portos">Técnico em Portos</option>
                        <option value="logistica">Técnico em Logística</option>
                        <option value="eletromecanica">Técnico em Eletromecânica</option>
                        <option value="fabricacao-mecanica">Técnico em Fabricação Mecânica</option>
                        <option value="manutencao-maquinas-industriais">Técnico em Manutenção de Máquinas Industriais</option>
                        <option value="mecanica">Técnico em Mecânica</option>
                        <option value="mecanica-precisao">Técnico em Mecânica de Precisão</option>
                        <option value="metalurgia">Técnico em Metalurgia</option>
                        <option value="soldagem">Técnico em Soldagem</option>
                        <option value="soldagem-papel">Técnico em Soldagem e Papel</option>
                        <option value="ceramica">Técnico em Cerâmica</option>
                        <option value="petroquimica">Técnico em Petroquímica</option>
                        <option value="plasticos">Técnico em Plásticos</option>
                        <option value="analises-quimicas">Técnico em Análises Químicas</option>
                        <option value="quimica">Técnico em Química</option>
                        <option value="refrigeracao-climatizacao">Técnico em Refrigeração e Climatização</option>
                        <option value="equipamentos-biomedicos">Técnico em Equipamentos Biomédicos</option>
                        <option value="seguranca-trabalho">Técnico em Segurança do Trabalho</option>
                        <option value="desenvolvimento-sistemas">Técnico em Desenvolvimento de Sistemas</option>
                        <option value="informatica">Técnico em Informática</option>
                        <option value="rede-computadores">Técnico em Rede de Computadores</option>
                        <option value="vestuario">Técnico em Vestuário</option>
                        <option value="textil">Técnico em Têxtil</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="valor_servico">Valor do Serviço:</label>
                    <input type="number" id="valor_servico" name="valor_servico" required>
                </div>

                <div class="form-group">
                    <label for="descricao_tecnico">Pequena Descrição:</label>
                    <textarea id="descricao_tecnico" name="descricao_tecnico" rows="4" required></textarea>
                </div>

                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 ConectTecs. Todos os direitos reservados.</p>
    </footer>

    <script>
        const certificadoRadios = document.querySelectorAll('input[name="certificado"]');
        const cursandoRadiosContainer = document.querySelector('.cursando');
        const cursandoRadios = document.querySelectorAll('input[name="cursando"]');
        const certificadoDocumento = document.querySelector('.certificado-documento');
        const documentoMatricula = document.querySelector('.documento-matricula');

        // Exibe campos com base na seleção de certificado
        certificadoRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'sim') {
                    certificadoDocumento.style.display = 'block';
                    cursandoRadiosContainer.style.display = 'none';
                    documentoMatricula.style.display = 'none';
                } else {
                    certificadoDocumento.style.display = 'none';
                    cursandoRadiosContainer.style.display = 'block';
                }
            });
        });

        // Exibe campos com base na seleção de "cursando"
        cursandoRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'sim') {
                    documentoMatricula.style.display = 'block';
                } else {
                    documentoMatricula.style.display = 'none';
                    alert("Para prosseguir, é necessário possuir um certificado ou estar cursando com um comprovante de matrícula.");
                }
            });
        });

        // Valida o formulário antes de enviar
        document.getElementById('cadastro-tecnico').addEventListener('submit', function(e) {
            const possuiCertificado = document.querySelector('input[name="certificado"]:checked').value;
            const estaCursando = document.querySelector('input[name="cursando"]:checked');

            if (possuiCertificado === 'nao' && (!estaCursando || estaCursando.value === 'nao')) {
                e.preventDefault();
                alert("Não é possível concluir o cadastro sem certificado ou comprovante de matrícula.");
                return false;
            }

            // Validação de especialidades (máximo 3)
            const especialidades = document.getElementById('especialidades');
            const selectedOptions = [...especialidades.selectedOptions];
            if (selectedOptions.length > 3) {
                e.preventDefault();
                alert('Você pode escolher no máximo 3 especialidades.');
                return false;
            }

            return true;
        });

        // Limite de seleções na especialidade
        document.getElementById('especialidades').addEventListener('change', function() {
            const selectedOptions = [...this.selectedOptions];
            if (selectedOptions.length > 3) {
                alert('Você pode escolher até 3 especialidades.');
                selectedOptions[selectedOptions.length - 1].selected = false;
            }
        });
    </script>
</body>
</html>