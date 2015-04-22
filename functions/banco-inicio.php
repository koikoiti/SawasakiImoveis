<?php
	class bancoinicio extends banco{
        
        #Destaques
        function MontaDestaques(){
            $Auxilio = parent::CarregaHtml('itens/inicio-destaque-itens');
            $SqlDestaques = "SELECT * FROM t_destaques ORDER BY RAND() LIMIT 6";
            $resultDestaques = parent::Execute($SqlDestaques);
            $cont = 0;
            while($rsDestaques = mysql_fetch_array($resultDestaques, MYSQL_ASSOC)){
                $Linha = $Auxilio;
                
                #Dados imovel
                $Sql = "SELECT I.*, C.nome AS categoria FROM t_imoveis I 
                        INNER JOIN fixo_categorias_imovel C ON C.idcategoria = I.idcategoria 
                        WHERE I.idimovel = " . $rsDestaques['idimovel'];
                $result = parent::Execute($Sql);
                $rs = parent::ArrayData($result);
                 
                #Foto
                $SqlFoto = "SELECT * FROM t_imagens_imovel WHERE idimovel = " . $rs['idimovel'] . " ORDER BY caminho ASC";
                $resultFoto = parent::Execute($SqlFoto);
                $rsFoto = parent::ArrayData($resultFoto);
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%CAMINHO%>', $rsFoto['caminho'], $Linha);
                $Linha = str_replace('<%REFERENCIA%>', $rs['referencia'], $Linha);
                $Linha = str_replace('<%CATEGORIA%>', utf8_encode($rs['categoria']), $Linha);
                $Linha = str_replace('<%BAIRRO%>', $rs['bairro'], $Linha);
                $Linha = str_replace('<%CIDADEESTADO%>', utf8_encode($rs['cidade'] . "/" . $rs['estado']), $Linha);
                $Linha = str_replace('<%VALOR%>', number_format($rs['valor'], 2, ',', '.'), $Linha);
                
                #Colocar 'novo' para imóveis cadastrados a 15 dias
                if(strtotime($rs['data_cadastro']) < strtotime('-15 day') ){
                    $label_novo = '';
                }else{
                    $label_novo = '<span class="sticker sticker-hot">Novo</span>';
                }
                $Linha = str_replace('<%NOVO%>', $label_novo, $Linha);
                
                #Verifica linha
                if($cont == 0){
                    $retorno .= '<div class="row-fluid hotproperties">';
                }
                if($cont == 3){
                    $retorno .= '<div class="clearfix"></div>
                            	</div>
                                <div class="row-fluid hotproperties">';
                }
                $retorno .= $Linha;
                $cont++;
            }
            $retorno .= '<div class="clearfix"></div></div>';
            
            return utf8_encode($retorno);
        }
        
        #Slider
        function MontaSlider(){
            $Auxilio = parent::CarregaHtml('itens/inicio-slider-itens');
            $Sql = "SELECT * FROM t_slider";
            $result = parent::Execute($Sql);
            while($rs = mysql_fetch_array($result, MYSQL_ASSOC)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%CAMINHO%>', $rs['caminho'], $Linha);
                $Linha = str_replace('<%TEXTO1%>', $rs['texto1'], $Linha);
                $Linha = str_replace('<%TEXTO2%>', $rs['texto2'], $Linha);
                $Linha = str_replace('<%LINK%>', $rs['link'], $Linha);
                $Slider .= $Linha;
            }
            return $Slider;
        }
	}
?>