<?php
	#include das funcoes da tela contato
	include('functions/banco-contato.php');

	#Instancia o objeto
	$banco = new bancocontato();
    
    #Trabalha com Post
	if(isset($_POST["acao"]) && $_POST["acao"] != '' ){
        $email = strip_tags(trim(addslashes($_POST["email"])));
        $nome = strip_tags(trim(addslashes($_POST["nome"])));
        $sobrenome = strip_tags(trim(addslashes($_POST["sobrenome"])));
        $telefone = strip_tags(trim(addslashes($_POST["telefone"])));
        $comentario = strip_tags(trim(addslashes($_POST["comentario"])));
        
        if($banco->SendMailEmpresa($email, $nome, $sobrenome, $telefone, $comentario)){
            #$banco->SendMailContato($email, $nome);
        }
    }

	#Imprimi valores
	$Conteudo = $banco->CarregaHtml('contato');
?>