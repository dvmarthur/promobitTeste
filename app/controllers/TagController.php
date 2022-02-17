<?php

use simphplio\Controller;
use simphplio\app\models\TagModel;

class Tag extends Controller
{
    public function criar()
    {


        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }






        $this->set('RETURNMESSAGE', '');

        $this->view('cadastrartag');
    }

    public function cadastrar()
    {


        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }





        $tag = new TagModel();

        $valida = $tag->insertTag($_POST['tag']);

        if ($valida  == true) {
            $this->set('RETURNMESSAGE', '<div class="alert alert-success mt-5" role="alert">  Tag cadastrada com sucesso! </div>');
        } else {
            $this->set('RETURNMESSAGE', '<div class="alert alert-alert mt-5" role="alert">  Erro ao cadastrar Tags! </div>');
        }
        $this->view('cadastrartag');
    }
    public function relatorio()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }






        $tag = new TagModel();

        $tags = $tag->selectTags();

        $relatorio = "";
        foreach ($tags as $k => $v) {
            $qtd = $tag->sumPROD($v['id']);


            $relatorio .=  "<th scope='row'>{$v['id']}</th>
        <td>{$v['name']}</td>
        <td>{$qtd['COUNT(tag_id)']}</td>
        </tr>";
        }
        $this->set('LISTARELATORIO', $relatorio);
        $this->view('relatorio');
    }
    public function listar()
    {


        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }





        $tag = new TagModel();

        $tags = $tag->selectTags();

        $iddelete = $this->getParam('delete');


        if (!empty($iddelete)) {

            $tag->deleteTAGrelacionadas($iddelete);
            $tag->deleteTag($iddelete);

            header("Location:" . APP_URL . "tag/listar");
        }

        $foreachhtml = "";
        foreach ($tags as $k => $v) {
            $foreachhtml .= " <tr>
         <th scope='row'>{$v['id']}</th>
         <td>{$v['name']}</td>
         <td><a href=" . APP_URL . "tag/editar/ideditar/{$v['id']}><button type='button' class='btn-sm btn-primary'>Editar</button></a></td>
         <td><a href=" . APP_URL . "tag/listar/delete/{$v['id']}><button type='button' class='btn-sm btn-danger'>Deletar</button></a></td>
         </tr>";
        }

        $this->set('LISTATAGS', $foreachhtml);
        $this->view('listartags');
    }
    public function editar()
    {



        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['auth']['user'])) {

            header("Location:" . APP_URL . "");
        }




        $tag = new TagModel();
        $ideditar = $this->getParam('ideditar');

        if (!empty($ideditar)) {

            $dados = $tag->selectTag($ideditar);

            $this->set('NAME', $dados['name']);
            $this->set('ID', $dados['id']);
        }

        if (!empty($_POST)) {

            $tag->updateTag($_POST['tag'], $ideditar);

            header("Location:" . APP_URL . "tag/listar");
        }
        $tag = new TagModel();

        $tags = $tag->selectTags();

        $selecttag = "";

        foreach ($tags as $k => $v) {

            $selecttag .= "<option value='{$v['id']}'>{$v['name']}</option> ";
        }
        $this->set('LOOPSELECTTAG', $selecttag);

        $this->set('RETURNMESSAGE', '');

        $this->view('editartag');
    }
}
