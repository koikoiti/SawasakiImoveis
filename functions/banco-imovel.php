<?php
	class bancoimovel extends banco{
	   
		#Monta slider
        function MontaSliderImovel($idimovel){
            $Auxilio = parent::CarregaHtml('itens/imovel-slider-itens');
            $Sql = "SELECT * FROM t_imagens_imovel WHERE idimovel = $idimovel";
            $result = parent::Execute($Sql);
            while($rs = mysql_fetch_array($result, MYSQL_ASSOC)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%CAMINHO%>', $rs['caminho'], $Linha);
                $Slider .= $Linha;
            }
            return $Slider;
        }
        
        #Lista imóveis
        function ListaImoveis($pagina){
            $inicio = ($pagina * Limite) - Limite;
            $Auxilio = parent::CarregaHtml('itens/lista-imoveis-itens');
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria
                    ORDER BY I.data_cadastro ASC
                    LIMIT $inicio, ".Limite."
                    ";
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                #Foto
                $SqlFoto = "SELECT * FROM t_imagens_imovel WHERE idimovel = " . $rs['idimovel'] . " ORDER BY caminho ASC";
                $resultFoto = parent::Execute($SqlFoto);
                $rsFoto = parent::ArrayData($resultFoto);
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%CAMINHO%>', $rsFoto['caminho'], $Linha);
                $Linha = str_replace('<%REFERENCIA%>', $rs['referencia'], $Linha);
                $Linha = str_replace('<%CATEGORIA%>', utf8_encode($rs['categoria']), $Linha);
                $Linha = str_replace('<%BAIRRO%>', utf8_encode($rs['bairro']), $Linha);
                $Linha = str_replace('<%CIDADEESTADO%>', utf8_encode($rs['cidade'] . "/" . $rs['estado']), $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                
                #Colocar 'novo' para imóveis cadastrados a 15 dias
                if(strtotime($rs['data_cadastro']) < strtotime('-15 day') ){
                    $label_novo = '';
                }else{
                    $label_novo = '<span class="sticker sticker-hot">Novo</span>';
                }
                $Linha = str_replace('<%NOVO%>', $label_novo, $Linha);
                $retorno .= $Linha;
            }
            return $retorno;
        }
        
        #Monta paginacao
        function MontaPaginacao($pagina){
            $totalPaginas = $this->TotalPaginas();
            $pag = '';
            if($totalPaginas > 1){
                if($pagina == 1){
                    $pag = '<span class="page active">&laquo;</span>';
                    $pag .= '<span class="page active">1</span>';
                }else{
                    $pag .= '<a href="'.UrlPadrao.'lista-imoveis/?page='.($pagina-1).'" class="page">&laquo;</a>';
                    $pag .= '<a href="'.UrlPadrao.'lista-imoveis/?page=1" class="page">1</a>';
                }
                $pag .= '<span class="page">...</span>';
                
                #Monta a paginação do meio
				if($totalPaginas < QtdPag){
				    if($pagina <= $totalPaginas){
				        for($i = 2; $i <= $totalPaginas - 1; $i++){
				            if($i == $pagina){
        						$pag .= '<span class="page active">'.$i.'</span>'; 
        					}else{
        						$pag .= '<a href="'.UrlPadrao.'lista-imoveis/?page='.$i.'" class="page">'.$i.'</a>';	
        					}
				        }
				    }
				}else{
				    if($pagina > 2){
    					$start = $pagina - 2;
    					$end = $pagina + 2;
    				}elseif($pagina == 2){
    					$start = $pagina - 1;
    					$end = $pagina + 3;
    				}elseif($pagina == 1){
    					$start = 1;
    					$end = $pagina + 4;
    				}
    				if($pagina == $totalPaginas){
    					$start = $pagina - 4;
    					$end = $totalPaginas;
    				}elseif($pagina == ($totalPaginas - 1)){
    					$start = $pagina - 3;
    					$end = $pagina + 1;
    				}
    				for($i = $start; $i <= $end; $i++){
    					if($i == $pagina){
    						$pag .= '<span class="page active">'.$i.'</span>'; 
    					}else{
    						if($i <= $totalPaginas){
    							$pag .= '<a href="'.UrlPadrao.'lista-imoveis/?page='.$i.'" class="page">'.$i.'</a>';
    						}
    					}
    				}
				}
                
                
                $pag .= '<span class="page">...</span>';
                if($pagina == $totalPaginas){
                    $pag .= '<span class="page active">'.$totalPaginas.'</span>';
                    $pag .= '<span class="page active">&raquo;</span>';
                }else{
                    $pag .= '<a href="'.UrlPadrao.'lista-imoveis/?page='.$totalPaginas.'" class="page">'.$totalPaginas.'</a>';
                    $pag .= '<a href="'.UrlPadrao.'lista-imoveis/?page='.($pagina+1).'"class="page">&raquo;</a>';
                }
                
                
                return $pag;
            }else{
                return '';
            }
            /*
            <a href="#" class="page">&laquo;</a>
                    <a href="#" class="page">2</a>
                    <a href="#" class="page">3</a>
                    <span class="page active">4</span>
                    <a href="#" class="page">5</a>
                    <a href="#" class="page">6</a>
                    <a href="#"class="page">&raquo;</a>
            */
        }
        
        #Total de paginas
        function TotalPaginas(){
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria";
            $result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			$totalPag = ceil($num_rows/Limite);
			return $totalPag;
        }
	}
?>