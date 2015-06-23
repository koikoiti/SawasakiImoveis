<?php
	class bancobusca extends banco{
		
        #Monta select Order
        function SelectOrder($idorder){
            $Sql = "SELECT * FROM fixo_order_imovel ORDER BY idorder";
			$select_order = "<select onchange='filtrar()' class='form-control' id='order' name='order' style='width: auto; margin: 10px 25px;'>";
			$select_order .= "<option value=''>Ordenar Por</option>";
			$result = parent::Execute($Sql);
			if($result){
				while($rs = parent::ArrayData($result)){
					if($rs['idorder'] == $idorder){
						$select_order .= "<option selected value='".$rs['idorder']."'>".$rs['nome']."</option>";
					}else{
						$select_order .= "<option value='".$rs['idorder']."'>".$rs['nome']."</option>";
					}
				}
				$select_order .= "</select>";
				return utf8_encode($select_order);
			}else{
				return false;
			}
        }
        
        #Monta itens busca rapida
        function MontaBuscaRapidaItens($idcategoria, $ce, $bairro, $pagina, $order){
            $inicio = ($pagina * LimiteBuscaRapida) - LimiteBuscaRapida;
            
            if($order){
                $SqlOrder = "SELECT ordem FROM fixo_order_imovel WHERE idorder = '$order'";
                $resultOrder = parent::Execute($SqlOrder);
                $rsOrder = parent::ArrayData($resultOrder);
                $ordenacao = " ORDER BY " . $rsOrder['ordem'];
            }else{
                $ordenacao = " ORDER BY I.data_cadastro ASC";
            }
            $Auxilio = utf8_encode(parent::CarregaHtml('itens/busca-itens'));
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria 
                    WHERE 1 ";
            
            if($idcategoria != ''){
                $Sql .= " AND I.idcategoria = '$idcategoria'";
            }
            if($ce[0] != ''){
                $Sql .= " AND (";
                foreach($ce as $value){
                    $aux = explode('_', $value);
                    $Sql .= " (I.cidade = '".utf8_decode($aux[0])."' AND I.estado = '".utf8_decode($aux[1])."') OR";
                }
                $Sql = rtrim($Sql, ' OR');
                $Sql .= ")";
            }
            if($bairro[0] != ''){
                $Sql .= " AND (";
                foreach($bairro as $val){
                    $Sql .= "(I.bairro = '".utf8_decode($val)."') OR";
                }
                $Sql = rtrim($Sql, ' OR');
                $Sql .= ")";
            }
            $Sql .= " $ordenacao";
            $Sql .= " LIMIT $inicio, ".LimiteBuscaRapida;
            
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                $SqlFoto = "SELECT * FROM t_imagens_imovel WHERE idimovel = " . $rs['idimovel'] . " AND ordem = 1";
                $resultFoto = parent::Execute($SqlFoto);
                $rsFoto = parent::ArrayData($resultFoto);
                $Linha = str_replace('<%CAMINHO%>', $rsFoto['caminho'], $Linha);
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                $Linha = str_replace('<%REFERENCIA%>', utf8_encode($rs['referencia']), $Linha);
                $Linha = str_replace('<%DORMITORIOS%>', utf8_encode($rs['dormitorios']), $Linha);
                $Linha = str_replace('<%BAIRRO%>', utf8_encode($rs['bairro']), $Linha);
                $Linha = str_replace('<%GARAGEM%>', utf8_encode($rs['garagem']), $Linha);
                $Linha = str_replace('<%AREATOTAL%>', utf8_encode($rs['area_total']), $Linha);
                $tipo_endereco = $rs['categoria'] . " - " . $rs['cidade'] . "/" . $rs['estado'];
                $Linha = str_replace('<%TIPOENDERECO%>', utf8_encode($tipo_endereco), $Linha);
                $Busca .= $Linha;
            }
            
            return $Busca;
        }
        
        #Monta busca normal
        function MontaBuscaNormal($pesquisa, $pagina, $order){
            $inicio = ($pagina * LimiteBuscaRapida) - LimiteBuscaRapida;
            if($order){
                $SqlOrder = "SELECT ordem FROM fixo_order_imovel WHERE idorder = '$order'";
                $resultOrder = parent::Execute($SqlOrder);
                $rsOrder = parent::ArrayData($resultOrder);
                $ordenacao = " ORDER BY " . $rsOrder['ordem'];
            }else{
                $ordenacao = " ORDER BY I.data_cadastro ASC";
            }
            $Auxilio = utf8_encode(parent::CarregaHtml('itens/busca-itens'));
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria 
                    WHERE I.referencia LIKE '%$pesquisa%' 
                    OR I.bairro LIKE '%$pesquisa%' 
                    OR I.endereco LIKE '%$pesquisa%'
                    OR I.cidade LIKE '%$pesquisa%' 
                    $ordenacao 
                    LIMIT $inicio, ".LimiteBuscaRapida;
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                $SqlCaminho = "SELECT * FROM t_imagens_imovel WHERE idimovel = " . $rs['idimovel'] . " ORDER BY caminho ASC";
                $resultCaminho = parent::Execute($SqlCaminho);
                $rsCaminho = parent::ArrayData($resultCaminho);
                $Linha = str_replace('<%CAMINHO%>', $rsCaminho['caminho'], $Linha);
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                $Linha = str_replace('<%REFERENCIA%>', utf8_encode($rs['referencia']), $Linha);
                $Linha = str_replace('<%DORMITORIOS%>', utf8_encode($rs['dormitorios']), $Linha);
                $Linha = str_replace('<%BAIRRO%>', utf8_encode($rs['bairro']), $Linha);
                $Linha = str_replace('<%GARAGEM%>', utf8_encode($rs['garagem']), $Linha);
                $Linha = str_replace('<%AREATOTAL%>', utf8_encode($rs['area_total']), $Linha);
                $tipo_endereco = $rs['categoria'] . " - " . $rs['cidade'] . "/" . $rs['estado'];
                $Linha = str_replace('<%TIPOENDERECO%>', utf8_encode($tipo_endereco), $Linha);
                $Busca .= $Linha;
            }
            
            return $Busca;
        }
        
        #Monta Paginacao Normal
        function MontaPaginacaoNormal($pesquisa, $pagina, $order){
            $totalPaginas = $this->TotalPaginasBuscaNormal($pesquisa);
            if($pesquisa || $order){
                $url = "pesquisa=".utf8_encode($pesquisa)."&order=$order";
            }
            $url .= "&page=";
            $pag = '';
            if($totalPaginas > 1){
                if($pagina == 1){
                    $pag = '<span class="page active">&laquo;</span>';
                    $pag .= '<span class="page active">1</span>';
                }else{
                    $pag .= '<a href="'.UrlPadrao.'busca/?'.$url.($pagina-1).'" class="page">&laquo;</a>';
                    $pag .= '<a href="'.UrlPadrao.'busca/?'.$url.'1" class="page">1</a>';
                }
                $pag .= '<span class="page">...</span>';
                
                #Monta a paginação do meio
				if($totalPaginas < QtdPag){
				    if($pagina <= $totalPaginas){
				        for($i = 2; $i <= $totalPaginas - 1; $i++){
				            if($i == $pagina){
        						$pag .= '<span class="page active">'.$i.'</span>'; 
        					}else{
        						$pag .= '<a href="'.UrlPadrao.'busca/?'.$url.$i.'" class="page">'.$i.'</a>';	
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
    							$pag .= '<a href="'.UrlPadrao.'busca/?'.$url.$i.'" class="page">'.$i.'</a>';
    						}
    					}
    				}
				}
                
                $pag .= '<span class="page">...</span>';
                if($pagina == $totalPaginas){
                    $pag .= '<span class="page active">'.$totalPaginas.'</span>';
                    $pag .= '<span class="page active">&raquo;</span>';
                }else{
                    $pag .= '<a href="'.UrlPadrao.'busca/?'.$url.$totalPaginas.'" class="page">'.$totalPaginas.'</a>';
                    $pag .= '<a href="'.UrlPadrao.'busca/?'.$url.($pagina+1).'"class="page">&raquo;</a>';
                }
                
                
                return $pag;
            }else{
                return '';
            }
        }
        
        #Total paginas busca normal
        function TotalPaginasBuscaNormal($pesquisa){
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria 
                    WHERE I.referencia LIKE '%$pesquisa%' 
                    OR I.bairro LIKE '%$pesquisa%' 
                    OR I.endereco LIKE '%$pesquisa%'
                    OR I.cidade LIKE '%$pesquisa%'";
            $result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			$totalPag = ceil($num_rows/LimiteBuscaRapida);
			return $totalPag;
        }
        
        #Monta Paginacao Rapida
        function MontaPaginacaoRapida($idcategoria, $ce, $bairro, $pagina, $order){
            $totalPaginas = $this->TotalPaginasBuscaRapida($idcategoria, $ce, $bairro);
            if($idcategoria || $ce || $bairro || $order){
                if($bairro[0] != ''){
                    foreach($bairro as $b){
                        $urlBairro .= "&bairro[]=".utf8_encode($b);
                    }
                }
                if($ce[0] != ''){
                    foreach($ce as $c){
                        $urlCE .= "&ce[]=".utf8_encode($c);
                    }
                }
                $url = "tipo=$idcategoria".$urlCE.$urlBairro."&order=$order";
            }
            $url .= "&page=";
            $pag = '';
            if($totalPaginas > 1){
                if($pagina == 1){
                    $pag = '<span class="page active">&laquo;</span>';
                    $pag .= '<span class="page active">1</span>';
                }else{
                    $pag .= '<a href="'.UrlPadrao.'busca/?'.$url.($pagina-1).'" class="page">&laquo;</a>';
                    $pag .= '<a href="'.UrlPadrao.'busca/?'.$url.'1" class="page">1</a>';
                }
                $pag .= '<span class="page">...</span>';
                
                #Monta a paginação do meio
				if($totalPaginas < QtdPag){
				    if($pagina <= $totalPaginas){
				        for($i = 2; $i <= $totalPaginas - 1; $i++){
				            if($i == $pagina){
        						$pag .= '<span class="page active">'.$i.'</span>'; 
        					}else{
        						$pag .= '<a href="'.UrlPadrao.'busca/?'.$url.$i.'" class="page">'.$i.'</a>';	
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
    							$pag .= '<a href="'.UrlPadrao.'busca/?'.$url.$i.'" class="page">'.$i.'</a>';
    						}
    					}
    				}
				}
                
                $pag .= '<span class="page">...</span>';
                if($pagina == $totalPaginas){
                    $pag .= '<span class="page active">'.$totalPaginas.'</span>';
                    $pag .= '<span class="page active">&raquo;</span>';
                }else{
                    $pag .= '<a href="'.UrlPadrao.'busca/?'.$url.$totalPaginas.'" class="page">'.$totalPaginas.'</a>';
                    $pag .= '<a href="'.UrlPadrao.'busca/?'.$url.($pagina+1).'"class="page">&raquo;</a>';
                }
                
                
                return $pag;
            }else{
                return '';
            }
        }
        
        #Total paginas busca rapida
        function TotalPaginasBuscaRapida($idcategoria, $ce, $bairro){
            if($idcategoria){
                $where .= "AND I.idcategoria = '$idcategoria'";
            }
            if($ce[0] != ''){
                $where .= " AND (";
                foreach($ce as $value){
                    $aux = explode('_', $value);
                    $where .= " (I.cidade = '".utf8_decode($aux[0])."' AND I.estado = '".utf8_decode($aux[1])."') OR";
                }
                $where = rtrim($where, ' OR');
                $where .= ")";
            }
            if($bairro[0] != ''){
                $where .= " AND (";
                foreach($bairro as $val){
                    $where .= "(I.bairro = '".utf8_decode($val)."') OR";
                }
                $where = rtrim($where, ' OR');
                $where .= ")";
            }
            $Sql = "SELECT I.* FROM t_imoveis I 
                    WHERE 1 
                    $where
                    ";
            $result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			$totalPag = ceil($num_rows/LimiteBuscaRapida);
			return $totalPag;
        }
	}
?>