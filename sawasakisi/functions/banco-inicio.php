<?php
	class bancoinicio extends banco{
		#Fecha Sessao
		function FechaSessao(){
			$_SESSION = array();
			session_destroy();
            parent::RedirecionaPara('login');
		}
	}
?>