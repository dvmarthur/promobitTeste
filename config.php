<?php

const APP_NAME = "simPHPlio";
const CONTROLLERS = 'app/controllers/';
const VIEWS = 'app/views/';


//Email
const CONTACT_EMAIL = ""; //contato@empresa.com.br
const EMAIL_SENDER = ''; //naoresponda@empresa.com.br
const NAME_SENDER = ''; //Nome de quem envia

//Template
const MODE = 'dev';//dev ou production

const CACHE_TIMESTAMP = 1588683870; //Timestamp no arquivo css, mudamos aqui quando queremos forçar limpeza de cache no navegador do usuário
const COMPRESS_CUSTOMCSS = true;        //Só funciona se a constante MODE estiver setada como "production"
const COMPRESS_HTML = true;            //Só funciona se a constante MODE estiver setada como "production"


//As rotas incluídas aqui serão exibidas mesmo que o sistema de permissão esteja bloqueando
const IGNORE_PERMISSION = array(
    "index/index_action/",
    "index/buscar/",
    "index/blank/",
    "acesso/entrar/",
    "acesso/sair/",
    "compra/doc/",
    "api/tipo_telefones/",
    "api/tipo_enderecos/",
    "api/busca_produtos/",
    "api/retorna_produto/",
    "api/busca_medicos/",
    "api/retorna_medico/",
    "api/busca_clientes/",
    "api/retorna_cliente/",
    "api/busca_laboratorios/",
    "api/retorna_laboratorio/",
    "api/buscar/",
    "anexos/deletar/"
);

const NAVIGATION = array(
    "Pessoas" => array(
        array("icon" => "la flaticon-user-2", "label" => "Clientes", "href" => "clientes", "childs" => array(
            array("label"   => "Listar", "href" => "cliente/listar/", "nivel" => array(1,2), "contains" => array("cliente/editar/","cliente/receita/")),
            array("label"   => "Cadastrar", "href" => "cliente/cadastrar/", "nivel" => array(1,2),"contains" => array(array("href" => "cliente/deletar/","nivel" => array(1)), "cliente/perfil/","cliente/modal_cadastrar/")),
        )),
        array("icon" => "la flaticon-user-4", "label" => "Médicos", "href" => "medicos", "childs" => array(
            array("label" => "Listar", "href" => "medico/listar/", "nivel" => array(1,2), "contains" => array(array("href" => "medico/deletar/","nivel" => array(1)) )),
            array("label" => "Cadastrar", "href" => "medico/cadastrar/", "nivel" => array(1,2), "contains" => array("medico/editar/","medico/modal_cadastrar/")),
            array("label" => "Especialidades", "href" => "medico/especialidades/", "nivel" => array(1), "contains" => array("medico/deletar_especialidade/")),
        )),
    ),
    "Itens" => array(
        array("icon" => "la flaticon-box-2", "label" => "Produtos", "href" => "produtos", "childs" => array(
            array("label" => "Listar", "href" => "produtos/listar/", "nivel" => array(1,2), "contains" => array("produtos/editar/", array("href" => "produtos/deletar/", "nivel" => array(1)) )),
            array("label" => "Cadastrar", "href" => "produtos/cadastrar/", "nivel" => array(1,2), "contains" => array("produtos/modal_cadastrar/")),
            array("label" => "Laboratórios", "href" => "produtos/laboratorios/", "nivel" => array(1,2), "contains" => array( array("href" => "produtos/deletar_lab/", "nivel" => array(1)) )),
        )),
        array("icon" => "la flaticon-file", "label" => "Receitas", "href" => "receitas", "childs" => array(
            array("label" => "Listar", "href" => "receita/listar/", "nivel" => array(1,2), "contains" => array(array("href" => "receita/deletar/","nivel" => array(1)), "receita/editar/","receita/visualizar/", "receita/detalhes/")),
            array("label" => "Cadastrar", "href" => "receita/cadastrar/", "nivel" => array(1,2)),
        )),
    ),
    "Sistema" => array(
        array("icon" => "la flaticon-users", "label" => "Usuários", "href" => "usuarios", "childs" => array(
            array("label" => "Listar", "href" => "usuario/listar/", "nivel" => array(1), "contains" => array("usuario/editar/","usuario/deletar/")),
            array("label" => "Cadastrar", "href" => "usuario/cadastrar/", "nivel" => array(1)),
        )),
        array("icon" => "la flaticon-graph", "label" => "Relatórios", "href" => "relatorios", "childs" => array(
            array("label" => "Próximas Receitas", "href" => "relatorios/proximas_receitas/", "nivel" => array(1)),
            array("label" => "Uso Medicamento", "href" => "relatorios/uso_medicamento/", "nivel" => array(1)),
            array("label" => "Aniversariantes Mês", "href" => "relatorios/aniversariantes/", "nivel" => array(1)),
            array("label" => "Registros Sistema", "href" => "relatorios/registros_sistema/", "nivel" => array(1)),
            array("label" => "Exportar Receitas", "href" => "relatorios/exportar_receitas/", "nivel" => array(1), "contains" => array("relatorios/exportar_receitas_zip/")),
        )),
        array("icon" => "la flaticon-settings", "label" => "Configurações", "href" => "configuracoes", "childs" => array(
            array("label" => "Parametrização", "href" => "config/parametrizacao/","nivel" => array(1)),
        ))
    )
);

const FORMS = array(
    "pessoa_fisica"     => "form_pessoa_fisica.html",
    "email"             => "form_email.html",
    "endereco"          => "form_endereco.html",
    "telefone"          => "form_telefone.html",
    "usuario"           => "form_usuario.html",
    "receita"           => "form_receita.html",
);
