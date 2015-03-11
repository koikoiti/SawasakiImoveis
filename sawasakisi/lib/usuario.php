<?php
    $titulosenha = "Senha:";
    $titulo = "Novo Usuário";
    $botao_excluir = '';
    
	#include das funcoes da tela inico
	include('functions/banco-usuario.php');

	#Instancia o objeto
	$banco = new bancousuario();

    if($this->PaginaAux[0] == 'editar'){
        #Trabalha com editar
        $idusuario = $this->PaginaAux[1];
        $rUsuario = $banco->BuscaUsuarioPorId($idusuario);
        $rsUsuario = $banco->ArrayData($rUsuario);
        
        $titulosenha = "Nova Senha:";
        $titulo = "Editar Usuário";
        
        $nome = $rsUsuario['nome_exibicao'];
        $login = $rsUsuario['login'];
        $idsetor = $rsUsuario['idsetor'];
        
        $botao_excluir = '<button type="button" style="background-color: #B6195B;" onclick="remover(\''.$idusuario.'\', \''.$nome.'\')" class="btn btn-success btn-flat"> Excluir</button>';
        
    }elseif($this->PaginaAux[0] == 'remover'){
        #Trabalha com remover
        $idusuario = $this->PaginaAux[1];
        
        $banco->RemoveUsuario($idusuario);
        $banco->RedirecionaPara('lista-usuario');
    }
        
    #Trabalha com Post
	if(isset($_POST["acao"]) && $_POST["acao"] != '' ){
        #recupera os dados passados por POST
		$nome = utf8_decode(strip_tags(trim(addslashes($_POST["nome"]))));
        $login = strip_tags(trim(addslashes($_POST["login"])));
		$senha = strip_tags(trim(addslashes($_POST["senha"])));
        $idsetor = strip_tags(trim(addslashes($_POST["setor"])));
		
		if($idusuario){
            #Update
            $banco->AtualizaUsuario($idusuario, $nome, $login, $senha, $idsetor);
            $banco->RedirecionaPara('lista-usuario');
        }else{
            #Busca Usuario no banco e verifica se ele existe
            $result = $banco->BuscaUsuarioPorLogin($login);
            $num_rows = $banco->Linha($result);
            if($num_rows){
                $msg = "Login em uso";
            }else{
                #Insert
                $banco->InsereUsuario($nome, $login, $senha, $idsetor);
                $banco->RedirecionaPara('lista-usuario');
            }
        }
    }
    
    $select_setor = $banco->SelectSetor($idsetor);
        
	#Imprime valores
	$Conteudo = $banco->CarregaHtml('Usuario/usuario');
    $Conteudo = str_replace("<%NOME%>", $nome, $Conteudo);
    $Conteudo = str_replace("<%LOGIN%>", $login, $Conteudo);
    $Conteudo = str_replace("<%TITULOSENHA%>", $titulosenha, $Conteudo);
    $Conteudo = str_replace("<%TITULO%>", $titulo, $Conteudo);
    $Conteudo = str_replace("<%SETOR%>", $select_setor, $Conteudo);
    $Conteudo = str_replace("<%BOTAOEXCLUIR%>", $botao_excluir, $Conteudo);
    $Conteudo = utf8_encode($Conteudo);
?>