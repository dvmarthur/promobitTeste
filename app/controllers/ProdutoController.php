<?php

use simphplio\Controller;
use simphplio\app\models\ProdutoModel;
use simphplio\app\models\TagModel;

class Produto extends Controller
{
    public function criar(){

        $tag = new TagModel();

        $tags = $tag->selectTags();

        $selecttag = "";

        foreach($tags as $k =>$v){

            $selecttag .= "<option value='{$v['id']}'>{$v['name']}</option> ";

        }
        $this->set('LOOPSELECTTAG', $selecttag);

        $this->set('RETURNMESSAGE','');

        $this->view('cadastrarproduto');

    }
    
     public function cadastrar(){

        $produto = new ProdutoModel();
        $tags = str_split($_POST['tags_array']);
        
        $idproduto = $produto->insertProduto($_POST['produto']);

        foreach($tags as $k=>$v){

            $produto->insertTagProduto($idproduto,$v);
        }
       
        header("Location:".APP_URL."core/produto/criar");

     }
     public function listar(){

        $produto = new ProdutoModel();

        $listaprod = $produto->listarProdutos();

        $iddelete = $this->getParam('delete');
        
        
        if(!empty($iddelete)){

            $produto->deleteProdutoTag($iddelete);
            $produto->deleteProduto($iddelete);

            header("Location:".APP_URL."core/produto/listar");
        }

        $foreachhtml = "";
       foreach($listaprod as $k =>$v){
        $foreachhtml .= " <tr>
        <th scope='row'>{$v['id']}</th>
        <td>{$v['name']}</td>
        <td><a href=".APP_URL."core/produto/editar/ideditar/{$v['id']}><button type='button' class='btn-sm btn-primary'>Editar</button></a></td>
        <td><a href=".APP_URL."core/produto/listar/delete/{$v['id']}><button type='button' class='btn-sm btn-danger'>Deletar</button></a></td>
        </tr>";
       }

        $this->set('LISTAPRODUTOS', $foreachhtml);
        $this->view('listarprodutos');
     }

     public function editar(){

        $produto = new ProdutoModel();
        $ideditar = $this->getParam('ideditar');
        
        if(!empty($ideditar)){

            $dados = $produto->selectProduto($ideditar);

            $this->set('NAME',$dados['name']);
            $this->set('ID',$dados['id']);
        }

        if(!empty($_POST)){

            $tags = str_split($_POST['tags_array']);
            
            $produto->deleteProdutoTag($ideditar);
            $produto->updateProduto($_POST['produto'],$ideditar);

            foreach($tags as $k=>$v){

                $produto->insertTagProduto($ideditar,$v);
            }

            header("Location:".APP_URL."core/produto/listar");
        }
        $tag = new TagModel();

        $tags = $tag->selectTags();

        $selecttag = "";

        foreach($tags as $k =>$v){

            $selecttag .= "<option value='{$v['id']}'>{$v['name']}</option> ";

        }
        $this->set('LOOPSELECTTAG', $selecttag);

        $this->set('RETURNMESSAGE','');

        $this->view('editar');
     }
     
}
