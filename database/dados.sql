-- ============================================================
-- BANCO DE DADOS
-- ============================================================

DROP DATABASE IF EXISTS parnaoica;

CREATE DATABASE parnaoica
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_0900_ai_ci;

USE parnaoica;


-- ============================================================
-- 1. CARGOS
-- ============================================================

CREATE TABLE cargo (
    id_cargo INT PRIMARY KEY AUTO_INCREMENT,

    nome VARCHAR(100) NOT NULL,

    descricao VARCHAR(255),

    status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo',

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_cargo_nome
        UNIQUE (nome)
);


-- ============================================================
-- 2. PERMISSÕES
-- ============================================================

CREATE TABLE permissao (
    id_permissao INT PRIMARY KEY AUTO_INCREMENT,

    nome VARCHAR(100) NOT NULL,

    chave VARCHAR(100) NOT NULL,

    descricao VARCHAR(255),

    tela VARCHAR(100),

    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_permissao_nome
        UNIQUE (nome),

    CONSTRAINT uk_permissao_chave
        UNIQUE (chave)
);


-- ============================================================
-- 3. ACESSO ÀS PERMISSÕES
-- ============================================================

CREATE TABLE acesso_permissao (
    id_acesso_permissao INT PRIMARY KEY AUTO_INCREMENT,

    id_cargo INT NOT NULL,

    id_permissao INT NOT NULL,

    permitido BOOLEAN NOT NULL DEFAULT TRUE,

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_acesso_cargo_permissao
        UNIQUE (id_cargo, id_permissao),

    CONSTRAINT fk_acesso_permissao_cargo
        FOREIGN KEY (id_cargo)
        REFERENCES cargo(id_cargo)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_acesso_permissao_permissao
        FOREIGN KEY (id_permissao)
        REFERENCES permissao(id_permissao)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);


-- ============================================================
-- 4. FUNCIONÁRIO
-- ============================================================

CREATE TABLE funcionario (
    id_funcionario INT PRIMARY KEY AUTO_INCREMENT,

    nome VARCHAR(250) NOT NULL,

    cpf VARCHAR(14) NOT NULL,

    telefone VARCHAR(15),

    email VARCHAR(255),

    id_cargo INT NOT NULL,

    status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo',

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_funcionario_cpf
        UNIQUE (cpf),

    CONSTRAINT fk_funcionario_cargo
        FOREIGN KEY (id_cargo)
        REFERENCES cargo(id_cargo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


-- ============================================================
-- 5. LOGIN / USUÁRIO DO SISTEMA
-- ============================================================

CREATE TABLE usuario_login (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,

    usuario VARCHAR(100) NOT NULL,

    senha_hash VARCHAR(255) NOT NULL,

    id_funcionario INT NOT NULL,

    status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo',

    ultimo_login DATETIME NULL,

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_usuario_login_usuario
        UNIQUE (usuario),

    CONSTRAINT uk_usuario_login_funcionario
        UNIQUE (id_funcionario),

    CONSTRAINT fk_usuario_login_funcionario
        FOREIGN KEY (id_funcionario)
        REFERENCES funcionario(id_funcionario)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


-- ============================================================
-- 6. CLIENTE
-- ============================================================

CREATE TABLE cliente (
    id_cliente INT PRIMARY KEY AUTO_INCREMENT,

    nome VARCHAR(250) NOT NULL,

    data_nascimento DATE,

    cpf VARCHAR(14) NOT NULL,

    email VARCHAR(250),

    telefone VARCHAR(15),

    estado CHAR(2),

    cidade VARCHAR(100),

    status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo',

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    rg VARCHAR(9),

    endereco VARCHAR(50),

    numero INT,

    pais VARCHAR(40),

    observacao VARCHAR(100),

    CONSTRAINT uk_cliente_cpf
        UNIQUE (cpf)
);


-- ============================================================
-- 7. ACOMODAÇÃO
-- ============================================================

CREATE TABLE acomodacao (
    id_acomodacao INT PRIMARY KEY AUTO_INCREMENT,

    nome VARCHAR(250) NOT NULL,

    numero_quarto INT NOT NULL,

    tipo_acomodacao VARCHAR(100) NOT NULL,

    capacidade INT NOT NULL,

    valor_diaria DECIMAL(10,2) NOT NULL,

    status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo',

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_acomodacao_numero_quarto
        UNIQUE (numero_quarto),

    CONSTRAINT chk_acomodacao_capacidade
        CHECK (capacidade > 0),

    CONSTRAINT chk_acomodacao_valor
        CHECK (valor_diaria >= 0)
);


-- ============================================================
-- 8. ESTACIONAMENTO
-- ============================================================

CREATE TABLE estacionamento (
    id_estacionamento INT PRIMARY KEY AUTO_INCREMENT,

    numero_vaga VARCHAR(20) NOT NULL,

    id_acomodacao INT NULL,

    status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo',

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_estacionamento_numero_vaga
        UNIQUE (numero_vaga),

    CONSTRAINT fk_estacionamento_acomodacao
        FOREIGN KEY (id_acomodacao)
        REFERENCES acomodacao(id_acomodacao)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);


-- ============================================================
-- 9. STATUS DA RESERVA
-- ============================================================

CREATE TABLE status_reserva (
    id_status_reserva INT PRIMARY KEY AUTO_INCREMENT,

    codigo VARCHAR(30) NOT NULL,

    descricao VARCHAR(100) NOT NULL,

    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',

    CONSTRAINT uk_status_reserva_codigo
        UNIQUE (codigo)
);


-- ============================================================
-- 10. RESERVA
-- ============================================================

CREATE TABLE reserva (
    id_reserva INT PRIMARY KEY AUTO_INCREMENT,

    id_cliente INT NOT NULL,

    id_estacionamento INT NULL,

    id_acomodacao INT NOT NULL,

    id_status_reserva INT NOT NULL,

    data_checkin DATE NOT NULL,

    data_checkout DATE NOT NULL,

    quantidade_clientes INT NOT NULL DEFAULT 1,

    valor_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_reserva_datas
        CHECK (data_checkout > data_checkin),

    CONSTRAINT chk_reserva_clientes
        CHECK (quantidade_clientes > 0),

    CONSTRAINT chk_reserva_valor
        CHECK (valor_total >= 0),

    CONSTRAINT fk_reserva_cliente
        FOREIGN KEY (id_cliente)
        REFERENCES cliente(id_cliente)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_reserva_estacionamento
        FOREIGN KEY (id_estacionamento)
        REFERENCES estacionamento(id_estacionamento)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_reserva_acomodacao
        FOREIGN KEY (id_acomodacao)
        REFERENCES acomodacao(id_acomodacao)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_reserva_status
        FOREIGN KEY (id_status_reserva)
        REFERENCES status_reserva(id_status_reserva)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


-- ============================================================
-- 11. ITEM
-- ============================================================

CREATE TABLE item (
    id_item INT PRIMARY KEY AUTO_INCREMENT,

    nome VARCHAR(100) NOT NULL,

    quantidade_estoque INT NOT NULL DEFAULT 0,

    valor DECIMAL(10,2) NOT NULL,

    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_item_quantidade
        CHECK (quantidade_estoque >= 0),

    CONSTRAINT chk_item_valor
        CHECK (valor >= 0)
);


-- ============================================================
-- 12. STATUS DO FRIGOBAR
-- ============================================================

CREATE TABLE status_frigobar (
    id_status_frigobar INT PRIMARY KEY AUTO_INCREMENT,

    codigo VARCHAR(30) NOT NULL,

    descricao VARCHAR(100) NOT NULL,

    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',

    CONSTRAINT uk_status_frigobar_codigo
        UNIQUE (codigo)
);


-- ============================================================
-- 13. FRIGOBAR
-- ============================================================

CREATE TABLE frigobar (
    id_frigobar INT PRIMARY KEY AUTO_INCREMENT,

    id_acomodacao INT NOT NULL,

    id_status_frigobar INT NOT NULL,

    numero_identificacao VARCHAR(50),

    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',

    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT uk_frigobar_acomodacao
        UNIQUE (id_acomodacao),

    CONSTRAINT uk_frigobar_identificacao
        UNIQUE (numero_identificacao),

    CONSTRAINT fk_frigobar_acomodacao
        FOREIGN KEY (id_acomodacao)
        REFERENCES acomodacao(id_acomodacao)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_frigobar_status
        FOREIGN KEY (id_status_frigobar)
        REFERENCES status_frigobar(id_status_frigobar)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


-- ============================================================
-- 14. KIT DO FRIGOBAR
-- ============================================================

CREATE TABLE kit_frigobar (
    id_kit_frigobar INT PRIMARY KEY AUTO_INCREMENT,

    id_frigobar INT NOT NULL,

    id_item INT NOT NULL,

    quantidade INT NOT NULL,

    data_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_kit_frigobar_quantidade
        CHECK (quantidade > 0),

    CONSTRAINT uk_kit_frigobar_item
        UNIQUE (id_frigobar, id_item),

    CONSTRAINT fk_kit_frigobar_frigobar
        FOREIGN KEY (id_frigobar)
        REFERENCES frigobar(id_frigobar)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_kit_frigobar_item
        FOREIGN KEY (id_item)
        REFERENCES item(id_item)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


-- ============================================================
-- 15. CONSUMO DO FRIGOBAR
-- ============================================================

CREATE TABLE consumo_frigobar (
    id_consumo INT PRIMARY KEY AUTO_INCREMENT,

    id_reserva INT NOT NULL,

    id_frigobar INT NOT NULL,

    id_item INT NOT NULL,

    quantidade INT NOT NULL,

    valor_unitario_pago DECIMAL(10,2) NOT NULL,

    data_consumo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    total DECIMAL(10,2)
        GENERATED ALWAYS AS (quantidade * valor_unitario_pago) STORED,

    CONSTRAINT chk_consumo_quantidade
        CHECK (quantidade > 0),

    CONSTRAINT chk_consumo_valor
        CHECK (valor_unitario_pago >= 0),

    CONSTRAINT fk_consumo_reserva
        FOREIGN KEY (id_reserva)
        REFERENCES reserva(id_reserva)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_consumo_frigobar
        FOREIGN KEY (id_frigobar)
        REFERENCES frigobar(id_frigobar)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_consumo_item
        FOREIGN KEY (id_item)
        REFERENCES item(id_item)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);


-- ============================================================
-- 16. DADOS INICIAIS - CARGOS
-- ============================================================

INSERT INTO cargo
    (nome, descricao)
VALUES
    ('Administrador', 'Acesso completo ao sistema'),
    ('Gerente', 'Acesso às funções gerenciais do sistema'),
    ('Recepcionista', 'Acesso às funções de recepção'),
    ('Funcionário', 'Acesso às funções básicas do sistema');


-- ============================================================
-- 17. DADOS INICIAIS - STATUS DA RESERVA
-- ============================================================

INSERT INTO status_reserva
    (codigo, descricao)
VALUES
    ('PENDENTE', 'Reserva pendente'),
    ('CONFIRMADA', 'Reserva confirmada'),
    ('CHECKIN', 'Check-in realizado'),
    ('HOSPEDADO', 'Cliente hospedado'),
    ('CHECKOUT', 'Check-out realizado'),
    ('CANCELADA', 'Reserva cancelada'),
    ('FINALIZADA', 'Reserva finalizada');


-- ============================================================
-- 18. DADOS INICIAIS - STATUS DO FRIGOBAR
-- ============================================================

INSERT INTO status_frigobar
    (codigo, descricao)
VALUES
    ('ATIVO', 'Frigobar ativo'),
    ('INATIVO', 'Frigobar inativo'),
    ('MANUTENCAO', 'Frigobar em manutenção'),
    ('LIMPEZA', 'Frigobar em limpeza');


-- ============================================================
-- 19. DADOS INICIAIS - PERMISSÕES
-- ============================================================

INSERT INTO permissao
    (nome, chave, descricao, tela)
VALUES

    -- CLIENTES
    (
        'Visualizar clientes',
        'cliente.visualizar',
        'Permite visualizar clientes',
        'clientes'
    ),

    (
        'Cadastrar clientes',
        'cliente.cadastrar',
        'Permite cadastrar clientes',
        'clientes'
    ),

    (
        'Editar clientes',
        'cliente.editar',
        'Permite editar clientes',
        'clientes'
    ),

    (
        'Excluir clientes',
        'cliente.excluir',
        'Permite excluir clientes',
        'clientes'
    ),


    -- RESERVAS
    (
        'Visualizar reservas',
        'reserva.visualizar',
        'Permite visualizar reservas',
        'reservas'
    ),

    (
        'Cadastrar reservas',
        'reserva.cadastrar',
        'Permite cadastrar reservas',
        'reservas'
    ),

    (
        'Editar reservas',
        'reserva.editar',
        'Permite editar reservas',
        'reservas'
    ),

    (
        'Cancelar reservas',
        'reserva.cancelar',
        'Permite cancelar reservas',
        'reservas'
    ),

    (
        'Realizar check-in',
        'reserva.checkin',
        'Permite realizar check-in',
        'reservas'
    ),

    (
        'Realizar check-out',
        'reserva.checkout',
        'Permite realizar check-out',
        'reservas'
    ),


    -- ACOMODAÇÕES
    (
        'Visualizar acomodações',
        'acomodacao.visualizar',
        'Permite visualizar acomodações',
        'acomodacoes'
    ),

    (
        'Cadastrar acomodações',
        'acomodacao.cadastrar',
        'Permite cadastrar acomodações',
        'acomodacoes'
    ),

    (
        'Editar acomodações',
        'acomodacao.editar',
        'Permite editar acomodações',
        'acomodacoes'
    ),

    (
        'Excluir acomodações',
        'acomodacao.excluir',
        'Permite excluir acomodações',
        'acomodacoes'
    ),


    -- ESTACIONAMENTO
    (
        'Visualizar estacionamento',
        'estacionamento.visualizar',
        'Permite visualizar vagas de estacionamento',
        'estacionamento'
    ),

    (
        'Gerenciar estacionamento',
        'estacionamento.gerenciar',
        'Permite cadastrar e alterar vagas',
        'estacionamento'
    ),


    -- FRIGOBAR
    (
        'Visualizar frigobar',
        'frigobar.visualizar',
        'Permite visualizar frigobares',
        'frigobar'
    ),

    (
        'Gerenciar frigobar',
        'frigobar.gerenciar',
        'Permite gerenciar frigobares',
        'frigobar'
    ),

    (
        'Registrar consumo',
        'frigobar.consumo',
        'Permite registrar consumo do frigobar',
        'frigobar'
    ),


    -- ITENS
    (
        'Visualizar itens',
        'item.visualizar',
        'Permite visualizar itens',
        'itens'
    ),

    (
        'Cadastrar itens',
        'item.cadastrar',
        'Permite cadastrar itens',
        'itens'
    ),

    (
        'Editar itens',
        'item.editar',
        'Permite editar itens',
        'itens'
    ),

    (
        'Excluir itens',
        'item.excluir',
        'Permite excluir itens',
        'itens'
    ),


    -- FUNCIONÁRIOS
    (
        'Visualizar funcionários',
        'funcionario.visualizar',
        'Permite visualizar funcionários',
        'funcionarios'
    ),

    (
        'Cadastrar funcionários',
        'funcionario.cadastrar',
        'Permite cadastrar funcionários',
        'funcionarios'
    ),

    (
        'Editar funcionários',
        'funcionario.editar',
        'Permite editar funcionários',
        'funcionarios'
    ),

    (
        'Excluir funcionários',
        'funcionario.excluir',
        'Permite excluir funcionários',
        'funcionarios'
    ),


    -- CARGOS
    (
        'Visualizar cargos',
        'cargo.visualizar',
        'Permite visualizar cargos',
        'cargos'
    ),

    (
        'Gerenciar cargos',
        'cargo.gerenciar',
        'Permite cadastrar, editar e excluir cargos',
        'cargos'
    ),


    -- PERMISSÕES
    (
        'Visualizar permissões',
        'permissao.visualizar',
        'Permite visualizar permissões',
        'permissoes'
    ),

    (
        'Gerenciar permissões',
        'permissao.gerenciar',
        'Permite gerenciar permissões dos cargos',
        'permissoes'
    );


-- ============================================================
-- 20. ADMINISTRADOR RECEBE TODAS AS PERMISSÕES
-- ============================================================

INSERT INTO acesso_permissao
    (id_cargo, id_permissao, permitido)

SELECT
    c.id_cargo,
    p.id_permissao,
    TRUE

FROM cargo c

CROSS JOIN permissao p

WHERE c.nome = 'Administrador';


-- ============================================================
-- 21. PERMISSÕES DO GERENTE
-- ============================================================

INSERT INTO acesso_permissao
    (id_cargo, id_permissao, permitido)

SELECT
    c.id_cargo,
    p.id_permissao,
    TRUE

FROM cargo c

INNER JOIN permissao p

WHERE c.nome = 'Gerente'

AND p.chave IN (

    -- Clientes
    'cliente.visualizar',
    'cliente.cadastrar',
    'cliente.editar',

    -- Reservas
    'reserva.visualizar',
    'reserva.cadastrar',
    'reserva.editar',
    'reserva.cancelar',
    'reserva.checkin',
    'reserva.checkout',

    -- Acomodações
    'acomodacao.visualizar',
    'acomodacao.cadastrar',
    'acomodacao.editar',

    -- Estacionamento
    'estacionamento.visualizar',
    'estacionamento.gerenciar',

    -- Frigobar
    'frigobar.visualizar',
    'frigobar.gerenciar',
    'frigobar.consumo',

    -- Itens
    'item.visualizar',
    'item.cadastrar',
    'item.editar',

    -- Funcionários
    'funcionario.visualizar',
    'funcionario.cadastrar',
    'funcionario.editar',

    -- Cargos
    'cargo.visualizar'
);


-- ============================================================
-- 22. PERMISSÕES DO RECEPCIONISTA
-- ============================================================

INSERT INTO acesso_permissao
    (id_cargo, id_permissao, permitido)

SELECT
    c.id_cargo,
    p.id_permissao,
    TRUE

FROM cargo c

INNER JOIN permissao p

WHERE c.nome = 'Recepcionista'

AND p.chave IN (

    -- Clientes
    'cliente.visualizar',
    'cliente.cadastrar',
    'cliente.editar',

    -- Reservas
    'reserva.visualizar',
    'reserva.cadastrar',
    'reserva.editar',
    'reserva.cancelar',
    'reserva.checkin',
    'reserva.checkout',

    -- Acomodações
    'acomodacao.visualizar',

    -- Estacionamento
    'estacionamento.visualizar',

    -- Frigobar
    'frigobar.visualizar',
    'frigobar.consumo'
);


-- ============================================================
-- 23. PERMISSÕES DO FUNCIONÁRIO
-- ============================================================

INSERT INTO acesso_permissao
    (id_cargo, id_permissao, permitido)

SELECT
    c.id_cargo,
    p.id_permissao,
    TRUE

FROM cargo c

INNER JOIN permissao p

WHERE c.nome = 'Funcionário'

AND p.chave IN (

    'cliente.visualizar',

    'reserva.visualizar',

    'acomodacao.visualizar',

    'estacionamento.visualizar',

    'frigobar.visualizar',

    'frigobar.consumo',

    'item.visualizar'
);


-- ============================================================
-- 24. ÍNDICES
-- ============================================================

CREATE INDEX idx_funcionario_cargo
    ON funcionario(id_cargo);

CREATE INDEX idx_usuario_login_funcionario
    ON usuario_login(id_funcionario);

CREATE INDEX idx_estacionamento_acomodacao
    ON estacionamento(id_acomodacao);

CREATE INDEX idx_reserva_cliente
    ON reserva(id_cliente);

CREATE INDEX idx_reserva_acomodacao
    ON reserva(id_acomodacao);

CREATE INDEX idx_reserva_estacionamento
    ON reserva(id_estacionamento);

CREATE INDEX idx_reserva_status
    ON reserva(id_status_reserva);

CREATE INDEX idx_frigobar_acomodacao
    ON frigobar(id_acomodacao);

CREATE INDEX idx_frigobar_status
    ON frigobar(id_status_frigobar);

CREATE INDEX idx_kit_frigobar_frigobar
    ON kit_frigobar(id_frigobar);

CREATE INDEX idx_kit_frigobar_item
    ON kit_frigobar(id_item);

CREATE INDEX idx_consumo_reserva
    ON consumo_frigobar(id_reserva);

CREATE INDEX idx_consumo_frigobar
    ON consumo_frigobar(id_frigobar);

CREATE INDEX idx_consumo_item
    ON consumo_frigobar(id_item);


