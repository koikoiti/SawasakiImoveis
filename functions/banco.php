<?php
	class banco{
		
		#Funcao que inicia conexao com banco
		function Conecta(){	
			$link = mysql_connect(DB_Host,DB_User,DB_Pass);
			if (!$link) {
				$this->ChamaManutencao();
			}
			$db_selected = mysql_select_db(DB_Database, $link);
			if (!$db_selected) {
				$this->ChamaManutencao();
			}
		}	
		
		#funcao imprime conteudo
		function Imprime($Conteudo){
			$SaidaHtml = $this->CarregaHtml('modelo');
			$SaidaHtml = str_replace('<%CONTEUDO%>',$Conteudo,$SaidaHtml);
			$SaidaHtml = str_replace('<%URLPADRAO%>',UrlPadrao,$SaidaHtml);
			echo $SaidaHtml;
		}
		
		#funcao que chama manutencao
		function ChamaManutencao(){
			$filename = 'html/manutencao.html';
			$handle = fopen($filename,"r");
			$Html = fread($handle,filesize($filename));
			fclose($handle);
			$SaidaHtml = $this->CarregaHtml('modelo');
			$SaidaHtml = str_replace('<%CONTEUDO%>',$Html,$SaidaHtml);
			$SaidaHtml = str_replace('<%URLPADRAO%>',UrlPadrao,$SaidaHtml);
			echo $SaidaHtml;
		}
		
		#funcao que monta o conteudo
		function MontaConteudo(){
			#verifica se nao tem nada do lado da URLPADRAO
			if(!isset($this->Pagina)){
				return $Conteudo = $this->ChamaPhp('inicio');
			#verifica se a pagina existe e chama ela
			}elseif($this->BuscaPagina()){
				return $Conteudo = $this->ChamaPhp($this->Pagina);
			#Se nao tiver pagina chama 404
			}else{
				return $Conteudo = $this->CarregaHtml('404');
			}
		} 
		
		#Busca a pagina e verifica se existe
		function BuscaPagina(){
			$Sql = "SELECT * FROM t_paginas_site WHERE url = '".$this->Pagina."'";
			$result = $this->Execute($Sql);
			$num_rows = $this->Linha($result);
			if($num_rows){
				return true;
			}else{
				return false;
			}
		}
		
		#Função que chama a pagina.php desejada.
		public function ChamaPhp($Nome){
			@require_once('lib/'.$Nome.'.php');
			return $Conteudo;
		}
	
		#Função que monta o html da pagina
		public function CarregaHtml($Nome){
			$filename = 'html/'.$Nome.".html";
			$handle = fopen($filename,"r");
			$Html = fread($handle,filesize($filename));
			fclose($handle);
			return $Html;
		}
		
		#Funcao que executa uma Sql e retorna.
		static function Execute($Sql){
			$result = mysql_query($Sql);
			return $result;
		}
		
		#Funcao que retorna o numero de linhas 
		static function Linha($result){
			$num_rows = mysql_num_rows($result);
			return $num_rows;
		}
		
		#Funcao que redireciona para pagina solicitada
		function RedirecionaPara($nome){
			header("Location: ".UrlPadrao.$nome);
		}
		
		#Funcao que carrega as páginas
		function CarregaPaginas(){
			$urlDesenvolve = 'sawasakiimoveis';
			$primeiraBol = true;
			$uri = $_SERVER["REQUEST_URI"];
			$exUrls = explode('/',$uri);
			$SizeUrls = count($exUrls)-1;

			$p = 0;
			foreach( $exUrls as $chave => $valor ){
				if( $valor != '' && $valor != $urlDesenvolve ){
					$valorUri = $valor;
					$valorUri = strip_tags($valorUri);
					$valorUri = trim($valorUri);
					$valorUri = addslashes($valorUri);
					
					if( $primeiraBol ){
						$this->Pagina = $valorUri;
						$primeiraBol = false;
					}else{
						$this->PaginaAux[$p] = $valorUri;
						$p++;
					}
				}
			}
		}
        
        #Retorna array da consulta SQL
        function ArrayData($result){
            return mysql_fetch_array($result, MYSQL_ASSOC);
        }
        
        #Monta busca rapida
        function MontaBuscaRapida($idcategoria = 0){
            $Auxilio = $this->CarregaHtml('busca-rapida');
            
            $select_categoria = $this->MontaSelectCategoria($idcategoria);
            $cidade_estado = $this->MontaCidadeEstado();
            $select_bairro = $this->MontaSelectBairro();
            
            $Auxilio = str_replace('<%SELECTCATEGORIA%>', $select_categoria, $Auxilio);
            $Auxilio = str_replace('<%SELECTCIDADEESTADO%>', $cidade_estado, $Auxilio);
            $Auxilio = str_replace('<%SELECTBAIRRO%>', $select_bairro, $Auxilio);
            return utf8_encode($Auxilio);
        }
        
        #Monta select categorias
        function MontaSelectCategoria($idcategoria){
            $Sql = "SELECT * FROM fixo_categorias_imovel ORDER BY nome ASC";
            $result = $this->Execute($Sql);
            $categorias = '<select name="tipo" data-placeholder="-- Tipo do Imóvel --" class="chzn-select" style="width:100%;" tabindex="0">';
            $categorias .= '<option value="0"> Tipo do Imóvel </option>';
            while($rs = $this->ArrayData($result)){
                if($idcategoria == $rs['idcategoria']){
                    $categorias .= '<option selected value="'.$rs['idcategoria'].'">'.$rs['nome'].'</option>';
                }else{
                    $categorias .= '<option value="'.$rs['idcategoria'].'">'.$rs['nome'].'</option>';
                }
            }
            $categorias .= '</select>';
            return $categorias;
        }
        
        #Monta cidade/estado
        function MontaCidadeEstado(){
            $Sql = 'SELECT DISTINCT cidade, estado FROM t_imoveis WHERE cidade <> "" AND estado <> ""';
            $result = $this->Execute($Sql);
            $ce = '<select id="ce" name="ce[]" data-placeholder="-- Cidade --" multiple="multiple" class="chzn-select" style="width:100%;" tabindex="2">';
            while($rs = $this->ArrayData($result)){
                $aux = $rs['cidade'] . "/" . $rs['estado'];
                $ce .= '<option value="'.$aux.'">'.$aux.'</option>';
            }
            $ce .= '</select>';
            return $ce;
        }
        
        #Monta select bairro
        function MontaSelectBairro(){
            $Sql = 'SELECT DISTINCT bairro FROM t_imoveis WHERE bairro <> ""';
            $result = $this->Execute($Sql);
            $ce = '<select id="bairro" name="bairro[]" data-placeholder="-- Bairro --" multiple="multiple" class="chzn-select" style="width:100%;" tabindex="2">';
            while($rs = $this->ArrayData($result)){
                $aux = $rs['bairro'];
                $ce .= '<option value="'.$aux.'">'.$aux.'</option>';
            }
            $ce .= '</select>';
            return $ce;
        }
	}
?>