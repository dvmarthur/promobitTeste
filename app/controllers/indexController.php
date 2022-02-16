<?php
use simphplio\Controller;

class Index extends Controller {

	public function Index_action()
	{
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        } 

        if(!empty($_SESSION['auth']['user'])){

            $this->view("index");
        }else{
        $this->set('RETURNMESSAGE', '');

		$this->view("login");
        }
	}

   
}

