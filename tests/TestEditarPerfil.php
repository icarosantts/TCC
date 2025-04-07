<?php
use PHPUnit\Framework\TestCase;

class TestEditarPerfil extends TestCase
{
    private $conn;
    private $stmt;

    protected function setUp(): void
    {
        // Mock da conexão com o banco de dados
        $this->conn = $this->createMock(mysqli::class);
        
        // Mock do Statement de Preparação
        $this->stmt = $this->createMock(mysqli_stmt::class);
        
        // Configurando o mock para o método prepare
        $this->conn->method('prepare')->willReturn($this->stmt);
    }

    public function testAtualizarPerfilSucesso()
    {
        // Dados simulados de um técnico
        $_SESSION['usuario_id'] = 1; // Simula o técnico logado
        $_SESSION['usuario_tipo'] = 'tecnico'; // Tipo de usuário

        // Dados enviados pelo formulário
        $nome = "João Silva";
        $telefone = "123456789";
        $email = "joao@dominio.com";
        $especialidades = "Eletricista";
        $valor_servico = "150";
        $descricao_tecnico = "Experiência de 10 anos em eletricidade.";

        // Simula a preparação do banco de dados
        $this->stmt->expects($this->once())
            ->method('bind_param')
            ->with($this->equalTo("ssssssi"), $this->equalTo($nome), $this->equalTo($telefone), $this->equalTo($email), $this->equalTo($especialidades), $this->equalTo($valor_servico), $this->equalTo($descricao_tecnico), $this->equalTo(1));

        // Simula a execução da query retornando sucesso
        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Função de atualização de perfil (simulando o comportamento do código original)
        $result = $this->atualizarPerfil($nome, $telefone, $email, $especialidades, $valor_servico, $descricao_tecnico);

        // Verifica se a resposta foi bem-sucedida
        $this->assertEquals("Perfil atualizado com sucesso! Atualize a página.", $result);
    }

    public function testEmailInvalido()
    {
        // Dados simulados de um técnico
        $_SESSION['usuario_id'] = 1; // Simula o técnico logado
        $_SESSION['usuario_tipo'] = 'tecnico'; // Tipo de usuário

        // Dados enviados pelo formulário com e-mail inválido
        $nome = "João Silva";
        $telefone = "123456789";
        $email = "email-invalido";
        $especialidades = "Eletricista";
        $valor_servico = "150";
        $descricao_tecnico = "Experiência de 10 anos em eletricidade.";

        // Função de atualização de perfil (simulando o comportamento do código original)
        $result = $this->atualizarPerfil($nome, $telefone, $email, $especialidades, $valor_servico, $descricao_tecnico);

        // Verifica se a resposta foi de erro de e-mail inválido
        $this->assertEquals("Erro: E-mail inválido.", $result);
    }

    public function testCamposObrigatorios()
    {
        // Dados simulados de um técnico
        $_SESSION['usuario_id'] = 1; // Simula o técnico logado
        $_SESSION['usuario_tipo'] = 'tecnico'; // Tipo de usuário

        // Dados enviados pelo formulário com um campo faltando
        $nome = "";
        $telefone = "123456789";
        $email = "joao@dominio.com";
        $especialidades = "Eletricista";
        $valor_servico = "150";
        $descricao_tecnico = "Experiência de 10 anos em eletricidade.";

        // Função de atualização de perfil (simulando o comportamento do código original)
        $result = $this->atualizarPerfil($nome, $telefone, $email, $especialidades, $valor_servico, $descricao_tecnico);

        // Verifica se a resposta foi de erro por campo obrigatório
        $this->assertEquals("Erro: Todos os campos são obrigatórios.", $result);
    }

    // Função de atualização de perfil (simulando o comportamento do código original)
    private function atualizarPerfil($nome, $telefone, $email, $especialidades, $valor_servico, $descricao_tecnico)
    {
        // Valida os campos obrigatórios
        if (empty($nome) || empty($telefone) || empty($email) || empty($especialidades) || empty($descricao_tecnico)) {
            return "Erro: Todos os campos são obrigatórios.";
        }

        // Valida o formato do e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Erro: E-mail inválido.";
        }

        // Prepara a query para atualizar os dados do técnico
        $stmt = $this->conn->prepare("UPDATE tecnicos SET nome = ?, telefone = ?, email = ?, especialidades = ?, valor_servico = ?, descricao_tecnico = ? WHERE id_tecnico = ?");
        $stmt->bind_param("ssssssi", $nome, $telefone, $email, $especialidades, $valor_servico, $descricao_tecnico, $_SESSION['usuario_id']);

        if ($stmt->execute()) {
            return "Perfil atualizado com sucesso! Atualize a página.";
        } else {
            return "Erro ao atualizar o perfil: " . $stmt->error;
        }
    }
}
?>
