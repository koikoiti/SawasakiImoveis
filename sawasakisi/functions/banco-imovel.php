<?php
	class bancoimovel extends banco{
		#Lista os imoveis
        function ListaImoveis(){
            $Auxilio = parent::CarregaHtml("Imovel/itens/lista-imovel-itens");
            
            $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I
                    INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria
                    ";
            $result = parent::Execute($Sql);
            $num_rows = parent::Linha($result);
            if($num_rows){
                while($rs = parent::ArrayData($result)){
                    $Linha = $Auxilio;
                    $Linha = str_replace("<%ID%>", $rs['idimovel'], $Linha);
                    $Linha = str_replace("<%REFERENCIA%>", $rs['referencia'], $Linha);
                    $Linha = str_replace("<%ENDERECO%>", $rs['endereco'], $Linha);
                    $Linha = str_replace("<%BAIRRO%>", $rs['bairro'], $Linha);
                    $Linha = str_replace("<%CATEGORIA%>", $rs['categoria'], $Linha);
                    $Linha = str_replace("<%VALOR%>", number_format($rs['valor'], 2, ',', '.'), $Linha);
                    $Imoveis .= $Linha;
                }
            }else{
                $Imoveis = '<tr class="odd gradeX">
                               <td colspan="6">Não foram encontrados imóveis cadastrados.</td>
                           <tr>';
            }
            
            return utf8_encode($Imoveis);
        }
        
        #Insere Imovel no banco
        function InsereImovel($referencia, $idcategoria, $cep ,$cidade, $estado, $endereco, $numero, $bairro, $complemento, $ponto_referencia, $area_util, $area_total, $proprietario, $telefone, $dormitorios, $garagem, $sala, $churrasqueira, $piso, $esquadrias, $idade, $valor, $descricao, $averbada, $copa, $cozinha, $lavabo, $lavanderia, $suite, $closet, $hidromassagem, $bwc_social, $lareira, $atico, $armarios, $sacada, $escritorio, $dep_empregada, $playground, $salao_festas, $piscina, $portao_eletronico, $files){
            $Sql = "INSERT INTO t_imoveis (referencia, idcategoria, cep, cidade, estado, endereco, numero, complemento, bairro, ponto_referencia, data_cadastro, proprietario, telefone, area_util, area_total, sala, copa, cozinha, lavabo, dormitorios, suite, closet, hidromassagem, bwc_social, lavanderia, lareira, atico, armarios, sacada, escritorio, piso, esquadrias, garagem, dep_empregada, churrasqueira, playground, salao_festas, piscina, portao_eletronico, averbada, cadastrado_por, valor, descricao, condominio) 
                    VALUES ('$referencia', '$idcategoria', '$cep', '$cidade', '$estado', '$endereco', '$numero', '$complemento', '$bairro', '$ponto_referencia', '".date("Y-m-d H:i:s")."', '$proprietario', '$telefone', '$area_util', '$area_total', '$sala', '$copa', '$cozinha', '$lavabo', '$dormitorios', '$suite', '$closet', '$hidromassagem', '$bwc_social', '$lavanderia', '$lareira', '$atico', '$armarios', '$sacada', '$escritorio', '$piso', '$esquadrias', '$garagem', '$dep_empregada', '$churrasqueira', '$playground', '$salao_festas', '$piscina', '$portao_eletronico', '$averbada', '".$_SESSION['nomeexibicao']."', '$valor', '$descricao', '$condominio')";
            if(parent::Execute($Sql)){
                if($files){
                    $lastID = mysql_insert_id();
                    $this->InsereFotos($lastID, $files);
                }
                return true;
            }else{
                parent::ChamaManutencao();
            }
        }
        
        #Insere caminho das fotos no banco e copia para a pasta do imóvel
        function InsereFotos($idimovel, $files){
            
            #Verifica update
            $SqlVerificaUpdate = "SELECT * FROM t_imagens_imovel WHERE idimovel = $idimovel ORDER BY caminho DESC LIMIT 0, 1";
            $resultVerificaUpdate = parent::Execute($SqlVerificaUpdate);
            $linhaVerificaUpdate = parent::Linha($resultVerificaUpdate);
            if($linhaVerificaUpdate){
                $rsVerificaUpdate = parent::ArrayData($resultVerificaUpdate);
                $ultimo = $rsVerificaUpdate['caminho'];
                $ultimo = explode('/', $ultimo);
                $ultimo = explode(' ', $ultimo[3]);
                $ultimo = explode('.', $ultimo[2]);
                $cont = $ultimo[0] + 1;
            }else{
                $cont = 1;
            }
                        
            #Verifica SERVER (web/local)
            if (strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
                $caminhoCriar = $_SERVER['DOCUMENT_ROOT'] . "/arq/imoveis/$idimovel";
            }else{
                $caminhoCriar = $_SERVER['DOCUMENT_ROOT'] . "/sawasakiimoveis/arq/imoveis/$idimovel";
            }
            $caminho = "arq/imoveis/$idimovel";
            #Cria diretório
        /*
            // Escrita e leitura para o proprietario, nada ninguem mais
            chmod ("/somedir/somefile", 0600);
            
            // Escrita e leitura para o proprietario, leitura para todos os outros
            chmod ("/somedir/somefile", 0644);
            
            // Tudo para o proprietario, leitura e execucao para os outros
            chmod ("/somedir/somefile", 0755);
            
            // Tudo para o proprietario, leitura e execucao para o grupo do prop
            chmod ("/somedir/somefile", 0750);
        */
			
            mkdir($caminhoCriar, 0755);
            foreach($files as $file){
                #Pega extensão da imagem
                preg_match("/\.(gif|png|jpg|jpeg){1}$/i", $file["name"], $ext);
                $caminhoMover = "/$idimovel - $cont" . "." . $ext[1];
                move_uploaded_file($file["tmp_name"], $caminhoCriar.$caminhoMover);
                $Sql = "INSERT INTO t_imagens_imovel (idimovel, caminho) VALUES ('$idimovel', '".$caminho.$caminhoMover."')";
                parent::Execute($Sql);
                $cont++;
            }
        }
        
        #Atualiza Imovel
        function AtualizaImovel($idimovel, $idcategoria, $referencia, $cep ,$cidade, $estado, $endereco, $numero, $bairro, $complemento, $ponto_referencia, $area_util, $area_total, $proprietario, $telefone, $dormitorios, $garagem, $sala, $churrasqueira, $piso, $esquadrias, $idade, $valor, $descricao, $averbada, $copa, $cozinha, $lavabo, $lavanderia, $suite, $closet, $hidromassagem, $bwc_social, $lareira, $atico, $armarios, $sacada, $escritorio, $dep_empregada, $playground, $salao_festas, $piscina, $portao_eletronico, $files){
            $Sql = "UPDATE t_imoveis SET idcategoria = '$idcategoria', referencia = '$referencia', cep = '$cep', cidade = '$cidade', estado = '$estado', endereco = '$endereco', numero = '$numero', bairro = '$bairro', complemento = '$complemento', 
                    ponto_referencia = '$ponto_referencia', area_util = '$area_util', area_total = '$area_total', proprietario = '$proprietario', telefone = '$telefone', dormitorios = '$dormitorios', garagem = '$garagem', sala = '$sala', 
                    churrasqueira = '$churrasqueira', piso = '$piso', esquadrias = '$esquadrias', idade = '$idade', valor = '$valor', descricao = '$descricao', averbada = '$averbada', copa = '$copa', cozinha = '$cozinha', lavabo = '$lavabo', lavanderia = '$lavanderia', 
                    suite = '$suite', closet = '$closet', hidromassagem = '$hidromassagem', bwc_social = '$bwc_social', lareira = '$lareira', atico = '$atico', armarios = '$armarios', sacada = '$sacada', escritorio = '$escritorio', 
                    dep_empregada = '$dep_empregada', playground = '$playground', salao_festas = '$salao_festas', piscina = '$piscina', portao_eletronico = '$portao_eletronico' WHERE idimovel = '$idimovel'";
            if(parent::Execute($Sql)){
                if($files){
                    $this->InsereFotos($idimovel, $files);
                }
                return true;
            }else{
                parent::ChamaManutencao();
            }
        }
        
        #Remove Imovel
        function RemoveImovel($idimovel){
            $this->RemoveFotos($idimovel);
            $Sql = "DELETE FROM t_imoveis WHERE idimovel = '$idimovel'";
            if(parent::Execute($Sql)){
                return true;
            }else{
                parent::ChamaManutencao();
            }
            
        }
        
        #Remove Fotos
        function RemoveFotos($idimovel){
            $Sql = "SELECT * FROM t_imagens_imovel WHERE idimovel = $idimovel";
            $result = parent::Execute($Sql);
            #Verifica SERVER (web/local)
            if(strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
                $caminhoRemover = $_SERVER['DOCUMENT_ROOT'] . "/";
            }else{
                $caminhoRemover = $_SERVER['DOCUMENT_ROOT'] . "/sawasakiimoveis/";
            }
            while($rs = parent::ArrayData($result)){
                unlink($caminhoRemover.$rs['caminho']);
            }
            rmdir($caminhoRemover."arq/imoveis/$idimovel");
        }
        
        #Busca Imovel por ID
        function BuscaImovelPorId($idimovel){
            $Sql = "SELECT * FROM t_imoveis WHERE idimovel = '$idimovel'";
            $result = parent::Execute($Sql);
            return $result;
        }
        
        #Monta select Categorias
        function SelectCategorias($idcategoria){
			$Sql = "SELECT * FROM fixo_categorias_imovel ORDER BY nome";
			$select_categorias = "<select required class='form-control' name='categoria'>";
			$select_categorias .= "<option selected value=''>Selecione uma Categoria!</option>";
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
        
        #Busca Imóvel por referência
        function BuscaImovelPorReferencia($referencia){
            $Sql = "SELECT * FROM t_imoveis WHERE referencia = '$referencia'";
            $result = parent::Execute($Sql);
            return $result;
        }
        
        #Monta imagens editar
        function MontaImagens($idimovel){
            $Sql = "SELECT * FROM t_imagens_imovel WHERE idimovel = $idimovel";
            $result = parent::Execute($Sql);
            $num_rows = parent::Linha($result);
            if($num_rows){
                while($rs = parent::ArrayData($result)){
                    $imagens .= "<div id='".$rs['idimagemimovel']."' style='float: left; clear: both; width: 20%'><div class='fileinput-preview thumbnail selFile'><img style='max-height: 400px;' src='".UrlFoto.$rs['caminho']."'></div><a href='#' onclick='removeFoto(\"".$rs['idimagemimovel']."\")' class='btn btn-danger'>Remover</a></div>";
                }
            }
            return $imagens;
        }
	}
?>