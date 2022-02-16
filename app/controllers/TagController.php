<?php

use simphplio\Controller;
use simphplio\app\models\TagModel;

class Tag extends Controller
{
    public function criar(){

        $this->set('RETURNMESSAGE', '');

        $this->view('cadastrartag');
    }

     public function cadastrar(){

         $tag = new TagModel();

         $valida = $tag->insertTag($_POST['tag']);

         if($valida  == true){
             $this->set('RETURNMESSAGE', '<div class="alert alert-success mt-5" role="alert">  Tag cadastrada com sucesso! </div>');
         }else{
             $this->set('RETURNMESSAGE', '<div class="alert alert-alert mt-5" role="alert">  Erro ao cadastrar Tags! </div>');

         }
         $this->view('cadastrartag');
     }
     public function relatorio(){

        $tag = new TagModel();

        $oi= $tag->selectTags();

        $relatorio = "";
        foreach($oi as $k => $v){
            $qtd = $tag->sumPROD($v['id']);

            
          $relatorio.=  "<th scope='row'>{$v['id']}</th>
        <td>{$v['name']}</td>
        <td>{$qtd['COUNT(tag_id)']}</td>
        </tr>";
        
        
        }
        $this->set('LISTARELATORIO',$relatorio);
        $this->view('relatorio');
    }
  
}
