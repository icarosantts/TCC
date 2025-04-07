<?php
use PHPUnit\Framework\TestCase;

class TestEmailUpdate extends TestCase
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

    public function testUpdateEmailSuccess()
    {
        // Dados simulados
        $novo_email = "novoemail@dominio.com";
        $id_usuario = 1;

        // Simulação do método bind_param
        $this->stmt->expects($this->once())
            ->method('bind_param')
            ->with($this->equalTo("si"), $this->equalTo($novo_email), $this->equalTo($id_usuario));

        // Simulação do método execute retornando sucesso
        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        // Simulando a sessão
        $_SESSION['id_usuario'] = $id_usuario;

        // Função de alteração de e-mail
        $result = $this->updateEmail($novo_email, $id_usuario);

        // Verifica se a resposta foi bem-sucedida
        $this->assertEquals("E-mail alterado com sucesso.", $result);
    }

    public function testInvalidEmailFormat()
    {
        // Dados simulados
        $novo_email = "invalid-email";
        $id_usuario = 1;

        // Simulando a sessão
        $_SESSION['id_usuario'] = $id_usuario;

        // Função de alteração de e-mail (retorna erro se o e-mail for inválido)
        $result = $this->updateEmail($novo_email, $id_usuario);

        // Verifica se a mensagem de erro foi retornada
        $this->assertEquals("Por favor, insira um e-mail válido.", $result);
    }

    public function testUpdateEmailFailure()
    {
        // Dados simulados
        $novo_email = "novoemail@dominio.com";
        $id_usuario = 1;

        // Simulação do método bind_param
        $this->stmt->expects($this->once())
            ->method('bind_param')
            ->with($this->equalTo("si"), $this->equalTo($novo_email), $this->equalTo($id_usuario));

        // Simulação do método execute retornando falha
        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(false);

        // Simulando a sessão
        $_SESSION['id_usuario'] = $id_usuario;

        // Função de alteração de e-mail
        $result = $this->updateEmail($novo_email, $id_usuario);

        // Verifica se a resposta de erro foi retornada
        $this->assertEquals("Erro ao alterar o e-mail.", $result);
    }

    // Função de atualização de e-mail (simulando o comportamento do código original)
    private function updateEmail($novo_email, $id_usuario)
    {
        // Valida o formato do e-mail
        if (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
            return "Por favor, insira um e-mail válido.";
        }

        // Prepara e executa a atualização no banco
        $stmt = $this->conn->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_email, $id_usuario);

        if ($stmt->execute()) {
            return "E-mail alterado com sucesso.";
        } else {
            return "Erro ao alterar o e-mail.";
        }
    }
}
?>
