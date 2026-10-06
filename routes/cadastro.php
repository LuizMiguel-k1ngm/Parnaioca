<?php

return [
    //Rotas das telas;
    'GET /cadastro' => __DIR__ . '/../app/Views/cadastro/index.php',
    'GET /cadastro/hospede' => __DIR__ . '/../app/Views/cadastro/hospede/index.php',
    'GET /cadastro/funcionario' => __DIR__ . '/../app/Views/cadastro/funcionario/index.php',
    'GET /cadastro/itens' => __DIR__ . '/../app/Views/cadastro/itens/index.php',
    'GET /cadastro/quarto' => __DIR__ . '/../app/Views/cadastro/quarto/index.php',
    'GET /cadastro/frigobar' => __DIR__ . '/../app/Views/cadastro/frigobar/index.php',
    'GET /cadastro/movimentacao' => __DIR__ . '/../app/Views/cadastro/movimentacoes/index.php',
    'GET /cadastro/consumo' => __DIR__ . '/../app/Views/cadastro/consumo/index.php',
    'GET /cadastro/acesso' => __DIR__ . '/../app/Views/cadastro/acesso/index.php',
    
    //Rotas para o controller
    'POST /cadastro/hospedes' => __DIR__ . '/../app/Controllers/HospedeController.php',
    'POST /cadastro/quarto' => __DIR__ . '/../app/Controllers/QuartoController.php',
    'POST /cadastro/frigobar' => __DIR__ . '/../app/Controllers/FrigobarController.php',
    'POST /cadastro/quarto/editar' => __DIR__ . '/../app/Controllers/QuartoController.php'


];