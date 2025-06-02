<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos internos (você pode mover para styles.css) */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
        }

        .accordion {
            max-width: 900px;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
        }

        .accordion-header {
            padding: 15px;
            background: #007bff;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .accordion-header:hover {
            background: #0056b3;
        }

        .accordion-body {
            display: none;
            padding: 15px;
            background: #f9f9f9;
            font-size: 16px;
        }

        .accordion-body p {
            margin-bottom: 10px;
        }

        .accordion-body a {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .accordion-body a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.php">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="cadastro_tecnico.php">Sou Técnico</a></li>
                    <li><a href="cadastro_cliente.php">Estou procurando por um Técnico</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>    
    </header>

    <main>
        <section class="sobre">
            <div class="accordion">
                <div class="accordion">
                    <div class="accordion-header">Sobre Nós</div>
                    <div class="accordion-body">
                        <p>Convidamos você a explorar e apoiar essa ideia inovadora, ajudando a transformar a vida de técnicos e clientes através da ConectTecs.</p>
                        <p>Imagine um técnico recém-formado que, em poucos cliques, encontra sua primeira oportunidade de trabalho, ou um cliente que descobre um profissional qualificado perto de casa.</p>
                        <a href="escolha_cadastro.php">Cadastre-se</a>
                    </div>
                </div>
        </section>

        <section>
            <h2>Áreas Técnicas disponíveis para serviços</h2>
        </section>
    
        <div class="accordion">
            <div class="accordion">
                <div class="accordion-header">Técnico em Manutenção Automotiva</div>
                <div class="accordion-body">
                    <p>Especialista em manutenção e reparação de veículos automotivos.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Edificações</div>
                <div class="accordion-body">
                    <p>Atua em projetos e construção de edificações e estruturas.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Design de Calçados</div>
                <div class="accordion-body">
                    <p>Cria e desenvolve designs de calçados inovadores.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Eletroeletrônica</div>
                <div class="accordion-body">
                    <p>Especializado em manutenção de sistemas eletroeletrônicos.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Eletrônica</div>
                <div class="accordion-body">
                    <p>Desenvolve e mantém circuitos e sistemas eletrônicos.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Eletrotécnica</div>
                <div class="accordion-body">
                    <p>Atua na instalação e manutenção de redes elétricas.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Sistemas de Energia Renovável</div>
                <div class="accordion-body">
                    <p>Trabalha com projetos de energia solar, eólica e outras fontes renováveis.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Qualidade</div>
                <div class="accordion-body">
                    <p>Especialista em processos e controle de qualidade industrial.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Multimídia</div>
                <div class="accordion-body">
                    <p>Cria conteúdos audiovisuais e digitais para diversas mídias.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Comunicação Visual</div>
                <div class="accordion-body">
                    <p>Desenvolve projetos gráficos e de identidade visual.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Impressão Offset</div>
                <div class="accordion-body">
                    <p>Atua na impressão gráfica de alta qualidade.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Impressão Rotográfica e Flexográfica</div>
                <div class="accordion-body">
                    <p>Responsável por operar e supervisionar processos de impressão rotográfica e flexográfica.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Processos Gráficos</div>
                <div class="accordion-body">
                    <p>Atua no planejamento e controle de processos gráficos e finalização de projetos.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Portos</div>
                <div class="accordion-body">
                    <p>Realiza operações logísticas e administrativas relacionadas a portos e terminais.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Logística</div>
                <div class="accordion-body">
                    <p>Gerencia processos logísticos e cadeias de suprimentos.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>
            <div class="accordion">
                <div class="accordion-header">Técnico em Eletromecânica</div>
                <div class="accordion-body">
                    <p>Combina conhecimentos em mecânica e elétrica para manutenção de máquinas.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            </div>

            <div class="accordion">
                <div class="accordion-header">Técnico em Fabricação Mecânica</div>
                <div class="accordion-body">
                    <p>Atua na fabricação de peças e equipamentos mecânicos, utilizando processos industriais modernos.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>

            <div class="accordion">
                <div class="accordion-header">Técnico em Manutenção de Máquinas Industriais</div>
                <div class="accordion-body">
                    <p>Especialista na manutenção preventiva e corretiva de máquinas utilizadas em indústrias.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>

            <div class="accordion">
                <div class="accordion-header">Técnico em Mecânica</div>
                <div class="accordion-body">
                    <p>Desenvolve projetos e realiza manutenção de sistemas mecânicos, equipamentos e máquinas.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>

            <div class="accordion">
                <div class="accordion-header">Técnico em Mecânica de Precisão</div>
                <div class="accordion-body">
                    <p>Trabalha com a produção de peças e sistemas que exigem alta precisão, como moldes e ferramentas.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>
            
            <div class="accordion">
                <div class="accordion-header">Técnico em Metalurgia</div>
                <div class="accordion-body">
                    <p>Responsável pela transformação e tratamento de metais em processos industriais diversos.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>

            <div class="accordion">
                <div class="accordion-header">Técnico em Soldagem</div>
                <div class="accordion-body">
                    <p>Especialista em processos de soldagem, garantindo qualidade em estruturas e materiais soldados.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>
        
            <div class="accordion">
                <div class="accordion-header">Técnico em Soldagem e Papel</div>
                <div class="accordion-body">
                    <p>Atua na união de materiais e no controle de qualidade no setor de papel e celulose.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>
            
            <div class="accordion">
                <div class="accordion-header">Técnico em Cerâmica</div>
                <div class="accordion-body">
                    <p>Especialista no desenvolvimento, produção e controle de qualidade de produtos cerâmicos.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
                
            <div class="accordion">
                <div class="accordion-header">Técnico em Petroquímica</div>
                <div class="accordion-body">
                    <p>Atua na transformação de petróleo e gás natural em produtos químicos e combustíveis.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>

            <div class="accordion">
                <div class="accordion-header">Técnico em Plásticos</div>
                <div class="accordion-body">
                    <p>Desenvolve processos e produtos no setor de plásticos, garantindo eficiência e sustentabilidade.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            
            <div class="accordion">
                <div class="accordion-header">Técnico em Análises Químicas</div>
                <div class="accordion-body">
                    <p>Realiza análises químicas para controle de qualidade e pesquisa em diferentes setores industriais.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            
            <div class="accordion">
                <div class="accordion-header">Técnico em Química</div>
                <div class="accordion-body">
                    <p>Trabalha no desenvolvimento de processos químicos, produtos e controle de qualidade.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            
            <div class="accordion">
                <div class="accordion-header">Técnico em Refrigeração e Climatização</div>
                <div class="accordion-body">
                    <p>Especialista na instalação e manutenção de sistemas de refrigeração e climatização.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            
            <div class="accordion">
                <div class="accordion-header">Técnico em Equipamentos Biomédicos</div>
                <div class="accordion-body">
                    <p>Atua na manutenção, calibração e gestão de equipamentos utilizados na área da saúde.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>
            
            <div class="accordion">
                <div class="accordion-header">Técnico em Segurança do Trabalho</div>
                <div class="accordion-body">
                    <p>Responsável por garantir a segurança dos trabalhadores, prevenindo acidentes e doenças ocupacionais.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
                </div>

            <div class="accordion">
                <div class="accordion-header">Técnico em Desenvolvimento de Sistemas</div>
                <div class="accordion-body">
                    <p>Atua na criação, manutenção e análise de sistemas computacionais, garantindo a funcionalidade e inovação tecnológica.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>
                
            <div class="accordion">
                <div class="accordion-header">Técnico em Informática</div>
                <div class="accordion-body">
                    <p>Trabalha na montagem, manutenção e configuração de computadores, além de suporte técnico e desenvolvimento de software básico.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>
                
            <div class="accordion">
                <div class="accordion-header">Técnico em Rede de Computadores</div>
                <div class="accordion-body">
                    <p>Especialista em instalação, manutenção e segurança de redes de computadores, garantindo a conectividade e eficiência.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>
                
            <div class="accordion">
                <div class="accordion-header">Técnico em Vestuário</div>
                <div class="accordion-body">
                    <p>Atua no planejamento, desenvolvimento e produção de peças de vestuário, com foco em qualidade e design.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>
                
            <div class="accordion">
                <div class="accordion-header">Técnico em Têxtil</div>
                <div class="accordion-body">
                    <p>Especialista na produção, análise e controle de qualidade de tecidos e materiais têxteis, promovendo eficiência e inovação no setor.</p>
                    <a href="escolha_cadastro.php">Cadastre-se</a>
            </div>
    </main>
    
      <script>
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const body = header.nextElementSibling;

                // Fecha outros accordions
                document.querySelectorAll('.accordion-body').forEach(item => {
                    if (item !== body) item.style.display = 'none';
                });

                // Alterna o estado do accordion clicado
                body.style.display = body.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
    
    <footer>
        <p>&copy; 2024 ConectTecs. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
