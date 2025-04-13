<?php
session_start();
include 'conexao.php';

// Habilitar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Função para sanitizar inputs
        function sanitizeInput($data) {
            return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
        }

        // Coletar e validar dados
        $nome = sanitizeInput($_POST['nome']);
        $email = filter_var(sanitizeInput($_POST['email']), FILTER_SANITIZE_EMAIL);
        $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
        $certificado = isset($_POST['certificado']) ? $_POST['certificado'] : "nao";
        $cursando = isset($_POST['cursando']) ? $_POST['cursando'] : "nao";
        $especialidades = isset($_POST['especialidades']) && is_array($_POST['especialidades']) ? implode(",", $_POST['especialidades']) : "";
        $valor_servico = floatval($_POST['valor_servico']);
        $descricao_tecnico = sanitizeInput($_POST['descricao_tecnico']);
        $whatsapp_link = trim($_POST['whatsapp_link']);

        // Validação do WhatsApp
        if (!filter_var($whatsapp_link, FILTER_VALIDATE_URL) || 
            !preg_match('/^https:\/\/wa\.me\/\d{10,15}$/', $whatsapp_link)) {
            throw new Exception("Link do WhatsApp inválido. Use o formato: https://wa.me/5511999999999");
        }

        // Validação do valor do serviço
        if ($valor_servico < 0) {
            throw new Exception("Valor do serviço deve ser positivo");
        }

        // Cria pasta uploads se não existir
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Função para upload dos arquivos
        function uploadArquivo($file, $upload_dir) {
            if (isset($file) && $file['error'] == UPLOAD_ERR_OK) {
                $nomeArquivo = uniqid() . "_" . basename($file["name"]);
                $target_file = $upload_dir . $nomeArquivo;
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    return $target_file;
                }
            }
            return "";
        }

        $documento_certificado = ($certificado == "sim") ? uploadArquivo($_FILES['documento-certificado'], $upload_dir) : "";
        $documento_matricula = ($cursando == "sim") ? uploadArquivo($_FILES['documento-matricula'], $upload_dir) : "";

        // Query para inserir dados
        $sql = "INSERT INTO tecnicos 
            (nome, email, senha, certificado, documento_certificado, cursando, documento_matricula, especialidades, valor_servico, descricao_tecnico, whatsapp_link) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param(
                "sssssssssss",
                $nome,
                $email,
                $senha,
                $certificado,
                $documento_certificado,
                $cursando,
                $documento_matricula,
                $especialidades,
                $valor_servico,
                $descricao_tecnico,
                $whatsapp_link
            );

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                throw new Exception("Erro ao cadastrar: " . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Técnico - ConectTecs</title>
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
        input[type="url"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
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
                    <input type="text" id="nome" name="nome" required value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="form-group">
                    <label for="whatsapp_link">Link do WhatsApp (para contato direto):</label>
                    <input type="url" id="whatsapp_link" name="whatsapp_link" placeholder="https://wa.me/5511999999999" required value="<?= isset($_POST['whatsapp_link']) ? htmlspecialchars($_POST['whatsapp_link']) : '' ?>">
                    <small>Formato: https://wa.me/5511999999999 (incluindo código do país e DDD)</small>
                    <button type="button" id="gerar-whatsapp" class="btn-ajuda">Como obter meu link?</button>
                    <div id="whatsapp-help" style="display:none;">
                        <p>1. Abra o WhatsApp no seu celular</p>
                        <p>2. Vá em Configurações > Conta</p>
                        <p>3. Seu número aparecerá no formato: 55DDDNNNNNNNN</p>
                        <p>4. Insira no campo: https://wa.me/55DDDNNNNNNNN</p>
                    </div>
                </div>

                <div class="form-group">
                    <label>Possui Diploma Técnico?</label>
                    <label><input type="radio" name="certificado" value="sim" required <?= (isset($_POST['certificado']) && $_POST['certificado'] == 'sim') ? 'checked' : '' ?>> Sim</label>
                    <label><input type="radio" name="certificado" value="nao" required <?= (isset($_POST['certificado']) && $_POST['certificado'] == 'nao') ? 'checked' : '' ?>> Não</label>
                </div>

                <div class="form-group certificado-documento" style="display: none;">
                    <label for="documento-certificado">Documento do Diploma (PDF, DOC, JPEG, PNG - Máx: 2MB):</label>
                    <input type="file" id="documento-certificado" name="documento-certificado" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png">
                </div>

                <div class="form-group cursando" style="display: none;">
                    <label>Está cursando?</label>
                    <label><input type="radio" name="cursando" value="sim" <?= (isset($_POST['cursando']) && $_POST['cursando'] == 'sim') ? 'checked' : '' ?>> Sim</label>
                    <label><input type="radio" name="cursando" value="nao" <?= (isset($_POST['cursando']) && $_POST['cursando'] == 'nao') ? 'checked' : '' ?>> Não</label>
                </div>

                <div class="form-group documento-matricula" style="display: none;">
                    <label for="documento-matricula">Atestado de Matrícula (PDF, DOC, JPEG, PNG - Máx: 2MB):</label>
                    <input type="file" id="documento-matricula" name="documento-matricula" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png">
                </div>

                <div class="form-group">
                    <label for="especialidades">Especialidades:</label>
                    <select id="especialidades" name="especialidades[]" multiple required>
                        <?php
                        $especialidadesOptions = [
                            "Alimentos", "automacao-industrial", "mecatronica", "manutencao-automotiva",
                            "edificacoes", "design-calcados", "eletroeletronica", "eletronica",
                            "eletrotecnica", "energia-renovavel", "qualidade", "multimidia",
                            "comunicacao-visual", "impressao-offset", "impressao-rotografica-flexografica",
                            "processos-graficos", "portos", "logistica", "eletromecanica",
                            "fabricacao-mecanica", "manutencao-maquinas-industriais", "mecanica",
                            "mecanica-precisao", "metalurgia", "soldagem", "soldagem-papel",
                            "ceramica", "petroquimica", "plasticos", "analises-quimicas",
                            "quimica", "refrigeracao-climatizacao", "equipamentos-biomedicos",
                            "seguranca-trabalho", "desenvolvimento-sistemas", "informatica",
                            "rede-computadores", "vestuario", "textil"
                        ];
                        
                        foreach ($especialidadesOptions as $option) {
                            $selected = (isset($_POST['especialidades']) && in_array($option, $_POST['especialidades'])) ? 'selected' : '';
                            echo "<option value=\"$option\" $selected>" . ucfirst(str_replace("-", " ", $option)) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="valor_servico">Valor do Serviço:</label>
                    <input type="number" id="valor_servico" name="valor_servico" required step="0.01" min="0" value="<?= isset($_POST['valor_servico']) ? htmlspecialchars($_POST['valor_servico']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="descricao_tecnico">Pequena Descrição:</label>
                    <textarea id="descricao_tecnico" name="descricao_tecnico" rows="4" required><?= isset($_POST['descricao_tecnico']) ? htmlspecialchars($_POST['descricao_tecnico']) : '' ?></textarea>
                </div>

                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 ConectTecs. Todos os direitos reservados.</p>
    </footer>

    <script>
        // Mostrar/ocultar campos condicionais
        function toggleFields() {
            const certificadoValue = document.querySelector('input[name="certificado"]:checked')?.value;
            const cursandoValue = document.querySelector('input[name="cursando"]:checked')?.value;
            
            document.querySelector('.certificado-documento').style.display = 
                (certificadoValue === 'sim') ? 'block' : 'none';
            document.querySelector('.cursando').style.display = 
                (certificadoValue === 'nao') ? 'block' : 'none';
            document.querySelector('.documento-matricula').style.display = 
                (cursandoValue === 'sim') ? 'block' : 'none';
        }

        // Inicializar campos
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields();
            
            // Configurar listeners para mudanças
            document.querySelectorAll('input[name="certificado"]').forEach(radio => {
                radio.addEventListener('change', toggleFields);
            });
            
            document.querySelectorAll('input[name="cursando"]').forEach(radio => {
                radio.addEventListener('change', toggleFields);
            });
            
            // Botão de ajuda do WhatsApp
            document.getElementById('gerar-whatsapp').addEventListener('click', function() {
                const helpDiv = document.getElementById('whatsapp-help');
                helpDiv.style.display = helpDiv.style.display === 'none' ? 'block' : 'none';
            });
            
            // Validação do WhatsApp em tempo real
            document.getElementById('whatsapp_link').addEventListener('blur', function() {
                const value = this.value.trim();
                if (value && !/^https:\/\/wa\.me\/\d{10,15}$/.test(value)) {
                    alert('Formato inválido! Use: https://wa.me/5511999999999');
                    this.focus();
                }
            });
        });

        // Validação do formulário
        document.getElementById('cadastro-tecnico').addEventListener('submit', function(e) {
            const possuiCertificado = document.querySelector('input[name="certificado"]:checked')?.value;
            const estaCursando = document.querySelector('input[name="cursando"]:checked')?.value;
            const whatsappLink = document.getElementById('whatsapp_link').value;
            
            // Validação do WhatsApp
            if (!/^https:\/\/wa\.me\/\d{10,15}$/.test(whatsappLink)) {
                e.preventDefault();
                alert('Por favor, insira um link válido do WhatsApp no formato: https://wa.me/5511999999999');
                return false;
            }
            
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