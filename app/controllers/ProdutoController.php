<?php

use simphplio\Controller;
use simphplio\app\models\ProdutoModel;
use simphplio\app\models\TagModel;

class Produto extends Controller
{


    public function criar()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }



        $tag = new TagModel();

        $tags = $tag->selectTags();

        $selecttag = "";

        foreach ($tags as $k => $v) {

            $selecttag .= "<option value='{$v['id']}'>{$v['name']}</option> ";
        }
        $this->set('LOOPSELECTTAG', $selecttag);

        $this->set('RETURNMESSAGE', '');

        $this->view('cadastrarproduto');
    }

    public function cadastrar()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }



        $produto = new ProdutoModel();
        $tags = explode('-', $_POST['tags_array']);



        $idproduto = $produto->insertProduto($_POST['produto']);

        foreach ($tags as $k => $v) {

            if ($v != '')
                $produto->insertTagProduto($idproduto, $v);
        }


        header("Location:" . APP_URL . "produto/criar");
    }
    public function listar()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }



        $produto = new ProdutoModel();

        $listaprod = $produto->listarProdutos();

        $iddelete = $this->getParam('delete');


        if (!empty($iddelete)) {

            $produto->deleteProdutoTag($iddelete);
            $produto->deleteProduto($iddelete);

            header("Location:" . APP_URL . "produto/listar");
        }

        $foreachhtml = "";
        foreach ($listaprod as $k => $v) {
            $foreachhtml .= " <tr>
        <th scope='row'>{$v['id']}</th>
        <td>{$v['name']}</td>
        <td><a href=" . APP_URL . "produto/editar/ideditar/{$v['id']}><button type='button' class='btn-sm btn-primary'>Editar</button></a></td>
        <td><a href=" . APP_URL . "produto/listar/delete/{$v['id']}><button type='button' class='btn-sm btn-danger'>Deletar</button></a></td>
        </tr>";
        }

        $this->set('LISTAPRODUTOS', $foreachhtml);
        $this->view('listarprodutos');
    }

    public function editar()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }
        $produto = new ProdutoModel();
        $ideditar = $this->getParam('ideditar');

        if (!empty($ideditar)) {

            $dados = $produto->selectProduto($ideditar);

            $this->set('NAME', $dados['name']);
            $this->set('ID', $dados['id']);
        }

        if (!empty($_POST)) {

            $tags = explode('-', $_POST['tags_array']);

            $produto->deleteProdutoTag($ideditar);
            $produto->updateProduto($_POST['produto'], $ideditar);

            foreach ($tags as $k => $v) {

                if ($v != '')
                    $produto->insertTagProduto($ideditar, $v);
            }

            header("Location:" . APP_URL . "produto/listar");
        }
        $tag = new TagModel();

        $tags = $tag->selectTags();

        $selecttag = "";

        foreach ($tags as $k => $v) {

            $selecttag .= "<option value='{$v['id']}'>{$v['name']}</option> ";
        }
        $this->set('LOOPSELECTTAG', $selecttag);

        $this->set('RETURNMESSAGE', '');

        $this->view('editar');
    }
}
