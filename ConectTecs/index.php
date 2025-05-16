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
                        <a href="cadastro.php">Cadastre-se</a>
                    </div>
                </div>
        </section>

        <section>
            <h2>Áreas Técnicas disponíveis para serviços</h2>
        </section>

        <?php
        $tecnicos = [
            "Técnico em Manutenção Automotiva" => "Especialista em manutenção e reparação de veículos automotivos.",
            "Técnico em Edificações" => "Atua em projetos e construção de edificações e estruturas.",
            "Técnico em Design de Calçados" => "Cria e desenvolve designs de calçados inovadores.",
            "Técnico em Eletroeletrônica" => "Especializado em manutenção de sistemas eletroeletrônicos.",
            "Técnico em Eletrônica" => "Desenvolve e mantém circuitos e sistemas eletrônicos.",
            "Técnico em Eletrotécnica" => "Atua na instalação e manutenção de redes elétricas.",
            "Técnico em Sistemas de Energia Renovável" => "Trabalha com projetos de energia solar, eólica e outras fontes renováveis.",
            "Técnico em Qualidade" => "Especialista em processos e controle de qualidade industrial.",
            "Técnico em Multimídia" => "Cria conteúdos audiovisuais e digitais para diversas mídias.",
            "Técnico em Comunicação Visual" => "Desenvolve projetos gráficos e de identidade visual.",
            "Técnico em Impressão Offset" => "Atua na impressão gráfica de alta qualidade.",
            "Técnico em Impressão Rotográfica e Flexográfica" => "Opera e supervisiona processos de impressão rotográfica e flexográfica.",
            "Técnico em Processos Gráficos" => "Planeja e controla processos gráficos e finalização de projetos.",
            "Técnico em Portos" => "Realiza operações logísticas e administrativas em portos.",
            "Técnico em Logística" => "Gerencia processos logísticos e cadeias de suprimentos.",
            "Técnico em Eletromecânica" => "Combina conhecimentos em mecânica e elétrica.",
            "Técnico em Fabricação Mecânica" => "Fabrica peças e equipamentos mecânicos com processos industriais modernos.",
            "Técnico em Manutenção de Máquinas Industriais" => "Faz manutenção preventiva e corretiva de máquinas industriais.",
            "Técnico em Mecânica" => "Projeta e mantém sistemas mecânicos e equipamentos.",
            "Técnico em Mecânica de Precisão" => "Produz peças e sistemas de alta precisão.",
            "Técnico em Metalurgia" => "Transforma e trata metais em processos industriais.",
            "Técnico em Soldagem" => "Especialista em processos de soldagem.",
            "Técnico em Soldagem e Papel" => "Trabalha com união de materiais no setor de papel.",
            "Técnico em Cerâmica" => "Especialista no desenvolvimento, produção e controle de cerâmica."
        ];

        foreach ($tecnicos as $titulo => $descricao) {
            echo '<div class="accordion">';
            echo '<div class="accordion-header">' . htmlspecialchars($titulo) . '</div>';
            echo '<div class="accordion-body">';
            echo '<p>' . htmlspecialchars($descricao) . '</p>';
            echo '<a href="cadastro.php">Cadastre-se</a>';
            echo '</div></div>';
        }
        ?>
    </main>
</body>
</html>
