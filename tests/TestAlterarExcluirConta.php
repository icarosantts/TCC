<?php
use PHPUnit\Framework\TestCase;

class TestAlterarExcluirConta extends TestCase
{
    protected $conn;
    protected $stmt;

    protected function setUp(): void
    {
        // Criar um mock para a conexão com o banco de dados
        $this->conn = $this->createMock(mysqli::class);
        // Criar um mock para a declaração preparada
        $this->stmt = $this->createMock(mysqli_stmt::class);

        // Simulando que o método prepare retorna um mock de stmt
        $this->conn->method('prepare')->willReturn($this->stmt);
    }

    public function testExcluirContaSucesso()
    {
        // Definindo que o método bind_param deve ser chamado corretamente
        $this->stmt->expects($this->once())
                    ->method('bind_param')
                    ->with($this->equalTo('i'), $this->equalTo(1));

        // Definindo que o método execute vai retornar true, simulando sucesso
        $this->stmt->expects($this->once())
                    ->method('execute')
                    ->willReturn(true);

        // Simulando variáveis de sessão
        $_SESSION['usuario_id'] = 1;
        $_SESSION['usuario_tipo'] = 'tecnico';

        // Capturando a saída para verificar a mensagem de sucesso
        ob_start();
        include('excluir_conta.php');  // O arquivo que contém a lógica de exclusão
        $output = ob_get_clean();

        // Verificando se a mensagem de sucesso foi exibida
        $this->assertStringContainsString('Conta excluída com sucesso!', $output);
    }

    public function testExcluirContaFalha()
    {
        // Definindo que o método bind_param será chamado corretamente
        $this->stmt->expects($this->once())
                    ->method('bind_param')
                    ->with($this->equalTo('i'), $this->equalTo(1));

        // Definindo que o método execute vai retornar false, simulando falha
        $this->stmt->expects($this->once())
                    ->method('execute')
                    ->willReturn(false);

        // Simulando variáveis de sessão
        $_SESSION['usuario_id'] = 1;
        $_SESSION['usuario_tipo'] = 'tecnico';

        // Capturando a saída para verificar a mensagem de erro
        ob_start();
        include('excluir_conta.php');  // O arquivo que contém a lógica de exclusão
        $output = ob_get_clean();

        // Verificando se a mensagem de erro foi exibida
        $this->assertStringContainsString('Erro ao excluir conta.', $output);
    }
}
?>
