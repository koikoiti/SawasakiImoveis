<?php
	class bancoimovel extends banco{
	   
		#Monta slider
        function MontaSliderImovel($idimovel){
            $Auxilio = parent::CarregaHtml('itens/imovel-slider-itens');
            $Sql = "SELECT * FROM t_imagens_imovel WHERE idimovel = $idimovel ORDER BY ordem";
            $result = parent::Execute($Sql);
            while($rs = mysql_fetch_array($result, MYSQL_ASSOC)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%CAMINHO%>', $rs['caminho'], $Linha);
                $Slider .= $Linha;
            }
            return $Slider;
        }
        
        #Lista imóveis
        function ListaImoveis($idcategoria, $cidadeestado, $min, $max, $bairro, $dormitorios, $garagens, $pagina, $order){
            $inicio = ($pagina * Limite) - Limite;
            $Auxilio = parent::CarregaHtml('itens/lista-imoveis-itens');
            if($idcategoria){
                $where .= "AND I.idcategoria = '$idcategoria'";
            }
            if($cidadeestado){
                $aux = explode('_', $cidadeestado);
                $where .= " AND I.cidade = '".$aux[0]."' AND I.estado = '".$aux[1]."'";
            }
            if($min){
                $min = str_replace('.', '', $min);
                $min = str_replace(',', '.', $min);
                $where .= " AND I.valor >= '$min'";
            }
            if($max){
                $max = str_replace('.', '', $max);
                $max = str_replace(',', '.', $max);
                $where .= " AND I.valor <= '$max'";
            }
            if($bairro){
                $where .= " AND I.bairro LIKE '%$bairro%'";
            }
            if($dormitorios){
                $where .= " AND I.dormitorios = '$dormitorios'";
            }
            if($garagens){
                $where .= " AND I.garagem = '$garagens'";
            }
            if($order){
                $SqlOrder = "SELECT ordem FROM fixo_order_imovel WHERE idorder = '$order'";
                $resultOrder = parent::Execute($SqlOrder);
                $rsOrder = parent::ArrayData($resultOrder);
                $ordenacao = " ORDER BY " . $rsOrder['ordem'];
            }else{
                $ordenacao = " ORDER BY I.data_cadastro ASC";
            }
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria 
                    WHERE 1 
                    $where 
                    $ordenacao 
                    LIMIT $inicio, ".Limite."
                    ";
            $result = parent::Execute($Sql);
            $num_rows = parent::Linha($result);
            if($num_rows){
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
            }else{
                return utf8_encode('Sem imóveis para visualizar!');
            }
        }
        
        #Monta paginacao
        function MontaPaginacao($idcategoria, $ce, $min, $max, $bairro, $dormitorios, $garagens, $pagina, $order){
            $totalPaginas = $this->TotalPaginas($idcategoria, $ce, $min, $max, $bairro, $dormitorios, $garagens);
            if($idcategoria || $ce || $min || $max || $bairro || $garagens || $dormitorios || $order){
                $url = "fcategoria=$idcategoria&fcidadeestado=$ce&fmin=$min&fmax=$max&bairro=".utf8_encode($bairro)."&dormitorios=$dor$dormitorios&garagens=$garagens&order=$order";
            }
            $url .= "&page=";
            $pag = '';
            if($totalPaginas > 1){
                if($pagina == 1){
                    $pag = '<span class="page active">&laquo;</span>';
                    $pag .= '<span class="page active">1</span>';
                }else{
                    $pag .= '<a href="'.UrlPadrao.'lista-imoveis/?'.$url.($pagina-1).'" class="page">&laquo;</a>';
                    $pag .= '<a href="'.UrlPadrao.'lista-imoveis/?'.$url.'1" class="page">1</a>';
                }
                $pag .= '<span class="page">...</span>';
                
                #Monta a paginação do meio
				if($totalPaginas < QtdPag){
				    if($pagina <= $totalPaginas){
				        for($i = 2; $i <= $totalPaginas - 1; $i++){
				            if($i == $pagina){
        						$pag .= '<span class="page active">'.$i.'</span>'; 
        					}else{
        						$pag .= '<a href="'.UrlPadrao.'lista-imoveis/?'.$url.$i.'" class="page">'.$i.'</a>';	
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
    							$pag .= '<a href="'.UrlPadrao.'lista-imoveis/?'.$url.$i.'" class="page">'.$i.'</a>';
    						}
    					}
    				}
				}
                
                
                $pag .= '<span class="page">...</span>';
                if($pagina == $totalPaginas){
                    $pag .= '<span class="page active">'.$totalPaginas.'</span>';
                    $pag .= '<span class="page active">&raquo;</span>';
                }else{
                    $pag .= '<a href="'.UrlPadrao.'lista-imoveis/?'.$url.$totalPaginas.'" class="page">'.$totalPaginas.'</a>';
                    $pag .= '<a href="'.UrlPadrao.'lista-imoveis/?'.$url.($pagina+1).'"class="page">&raquo;</a>';
                }
                
                
                return $pag;
            }else{
                return '';
            }
        }
        
        #Total de paginas
        function TotalPaginas($idcategoria, $ce, $min, $max, $bairro, $dormitorios, $garagens){
            if($idcategoria){
                $where .= "AND I.idcategoria = '$idcategoria'";
            }
            if($ce){
                $aux = explode('_', $ce);
                $where .= " AND I.cidade = '".$aux[0]."' AND I.estado = '".$aux[1]."'";
            }
            if($min){
                $min = str_replace('.', '', $min);
                $min = str_replace(',', '.', $min);
                $where .= " AND I.valor >= '$min'";
            }
            if($max){
                $max = str_replace('.', '', $max);
                $max = str_replace(',', '.', $max);
                $where .= " AND I.valor <= '$max'";
            }
            if($bairro){
                $where .= " AND I.bairro LIKE '%$bairro%'";
            }
            if($dormitorios){
                $where .= " AND I.dormitorios = '$dormitorios'";
            }
            if($garagens){
                $where .= " AND I.garagem = '$garagens'";
            }
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria 
                    WHERE 1 
                    $where
                    ";
            $result = parent::Execute($Sql);
			$num_rows = parent::Linha($result);
			$totalPag = ceil($num_rows/Limite);
			return $totalPag;
        }
        
        #Mail contato
		function SendMailContato($email, $nome, $referencia){
            require_once './app/PHPMailer/PHPMailerAutoload.php';
            
            $mail = new PHPMailer();
            $mail->SMTPDebug = 0;
            // Charset para evitar erros de caracteres
            $mail->CharSet = 'UTF-8';
            
            // Dados de quem está enviando o email
            $mail->From = 'sawasakiimoveis@gmail.com';
            $mail->FromName = 'Sawasaki Imóveis';
            
            // Setando o conteudo
            $mail->isHTML(true);
            $mail->Subject = 'Registro de interesse em imóvel através do site da Sawasaki Imóveis!';
            
            #Corpo do e-mail
            $aux = parent::CarregaHtml('Mail/imovel-cliente');
            $aux = str_replace('<%NOME%>', $nome, $aux);
            $aux = str_replace('<%REFERENCIA%>', $referencia, $aux);
            $mail->Body     = $aux;
            
            // Validando a autenticação
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host     = "ssl://smtp.googlemail.com";
			$mail->Port     = 465;
			$mail->Username = loginGmail;
			$mail->Password = pwGmail;
            
            // Setando o endereço de recebimento
            $mail->clearAllRecipients();
            $mail->addAddress($email, $nome);
            
            // Enviando o e-mail
            if($mail->send()){
                echo "<script type='text/javascript'>alert('Mensagem enviada com sucesso!')</script>";
				return true;
			}else{
				echo "<script type='text/javascript'>alert('Erro no envio de e-mail.')</script>";
			}
		}
        
        #Mail empresa
        function SendMailEmpresa($email, $nome, $telefone, $comentario, $referencia){
            require_once './app/PHPMailer/PHPMailerAutoload.php';
            
            $mail = new PHPMailer();
            $mail->SMTPDebug = 0;
            // Charset para evitar erros de caracteres
            $mail->CharSet = 'UTF-8';
            
            // Dados de quem está enviando o email
            $mail->From = 'sawasakiimoveis@gmail.com';
            $mail->FromName = 'Sawasaki Imoveis';
            
            // Setando o conteudo
            $mail->isHTML(true);
            $mail->Subject = 'Registro de interesse em imóvel através do site da Sawasaki Imóveis!';
            
            #Corpo do e-mail
            $aux = parent::CarregaHtml('Mail/imovel-empresa');
            $aux = str_replace('<%NOME%>', $nome, $aux);
            $aux = str_replace('<%TELEFONE%>', $telefone, $aux);
            $aux = str_replace('<%EMAIL%>', $email, $aux);
            $aux = str_replace('<%MENSAGEM%>', $comentario, $aux);
            $aux = str_replace('<%REFERENCIA%>', $referencia, $aux);
            $mail->Body     = $aux;
            
            // Validando a autenticação
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host     = "ssl://smtp.googlemail.com";
			$mail->Port     = 465;
			$mail->Username = loginGmail;
			$mail->Password = pwGmail;
            
            // Setando o endereço de recebimento
            $mail->clearAllRecipients();
            $mail->addAddress(emailRecebimento, 'Sawasaki Imoveis');
            // Enviando o e-mail
            if($mail->send()){
				return true;
			}else{
				echo "<script type='text/javascript'>alert('Erro no envio de e-mail.')</script>";
			}
		}
        
        #Monta select Categorias
        function SelectCategorias($idcategoria){
			$Sql = "SELECT * FROM fixo_categorias_imovel ORDER BY nome";
			$select_categorias = "<select class='form-control' id='categoria' name='categoria' style='width: 15%'>";
			$select_categorias .= "<option selected value=''>Categoria</option>";
			$result = parent::Execute($Sql);
			if($result){
				while($rs = parent::ArrayData($result)){
					if($rs['idcategoria'] == $idcategoria){
						$select_categorias .= "<option selected value='".$rs['idcategoria']."'>".$rs['nome']."</option>";
					}else{
						$select_categorias .= "<option value='".$rs['idcategoria']."'>".$rs['nome']."</option>";
					}
				}
				$select_categorias .= "</select>";
				return $select_categorias;
			}else{
				return false;
			}
        }
        
        #Monta select Order
        function SelectOrder($idorder){
            $Sql = "SELECT * FROM fixo_order_imovel ORDER BY idorder";
			$select_order = "<select onchange='filtrar()' class='form-control' id='order' name='order' style='width: 19%'>";
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
        
        #Monta cidade/estado
        function SelectCidadeEstado($cidadeestado){
            $auz = explode('_', $cidadeestado);
            $Sql = 'SELECT DISTINCT cidade, estado FROM t_imoveis WHERE cidade <> "" AND estado <> ""';
            $result = $this->Execute($Sql);
            $ce = '<select autofocus id="cidadeestado" style="width: 15%;">';
            $ce .= '<option value="">Cidade/UF</option>';
            while($rs = $this->ArrayData($result)){
                if($auz[0] == $rs['cidade'] && $auz[1] == $rs['estado']){
                    $aux = $rs['cidade'] . "/" . $rs['estado'];
                    $ce .= '<option selected value="'.$aux.'">'.$aux.'</option>';
                }else{
                    $aux = $rs['cidade'] . "/" . $rs['estado'];
                    $ce .= '<option value="'.$aux.'">'.$aux.'</option>';
                }
            }
            $ce .= '</select>';
            return utf8_encode($ce);
        }
	}
?>