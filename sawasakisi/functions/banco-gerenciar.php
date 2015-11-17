<?php
	class bancogerenciar extends banco{
		
        #Busca imagens slider
        function BuscaImagensSlider(){
            $Auxilio = parent::CarregaHtml('Slider/slider-itens');
            $Sql = "SELECT * FROM t_slider";
            $result = parent::Execute($Sql);
            while($rs = parent::ArrayData($result)){
                $Linha = $Auxilio;
                $Linha = str_replace('<%ID%>', $rs['idslider'], $Linha);
                $Linha = str_replace('<%CAMINHO%>', UrlFoto.$rs['caminho'], $Linha);
                $Linha = str_replace('<%TEXTO1%>', utf8_encode($rs['texto1']), $Linha);
                $Linha = str_replace('<%TEXTO2%>', utf8_encode($rs['texto2']), $Linha);
                $Linha = str_replace('<%LINK%>', $rs['link'], $Linha);
                $Slider .= $Linha;
            }
            return $Slider;
        }
        
        #Insere novo slide
        function InsereSlide($texto1, $texto2, $link, $file){
            $SqlNxId = "SHOW TABLE STATUS LIKE 't_slider'";
            $resultNxId = parent::Execute($SqlNxId);
            $rsNxId = parent::ArrayData($resultNxId);
            $nextId = $rsNxId['Auto_increment'];
            
            #Verifica SERVER (web/local)
            if (strpos($_SERVER['DOCUMENT_ROOT'], 'public_html') !== false) {
                $caminhoCriar = $_SERVER['DOCUMENT_ROOT'] . "/arq/slider";
            }else{
                $caminhoCriar = $_SERVER['DOCUMENT_ROOT'] . "/sawasakiimoveis/arq/slider";
            }
            $caminho = "arq/slider";
            
            #Pega extensão da imagem
            preg_match("/\.(gif|png|jpg|jpeg){1}$/i", $file["name"], $ext);
            $caminhoMover = "/$nextId" . "." . $ext[1];
            move_uploaded_file($file["tmp_name"], $caminhoCriar.$caminhoMover);
            $Sql = "INSERT INTO t_slider (texto1, texto2, link, caminho) VALUES ('$texto1', '$texto2', '$link', '".$caminho.$caminhoMover."')";
            parent::Execute($Sql);
        }
        
        #Monta destaques
        function MontaDestaques(){
            $Auxilio = utf8_encode(parent::CarregaHtml('Destaques/destaques-itens'));
            $Sql = "SELECT * FROM t_destaques";
            $result = parent::Execute($Sql);
            while($rsD = parent::ArrayData($result)){
                $Linha = $Auxilio;
                $SqlImovel = "SELECT I.*, M.caminho FROM t_imoveis I 
                            INNER JOIN t_imagens_imovel M ON I.idimovel = M.idimovel
                            WHERE I.idimovel = " . $rsD['idimovel'] . " 
                            ORDER BY caminho ASC";
                $resultImovel = parent::Execute($SqlImovel);
                $rs = parent::ArrayData($resultImovel);
                $Linha = str_replace('<%ID%>', $rs['idimovel'], $Linha);
                $Linha = str_replace('<%CAMINHO%>', UrlFoto.$rs['caminho'], $Linha);
                $Linha = str_replace('<%REFERENCIA%>', $rs['referencia'], $Linha);
                $Destaques .= $Linha;
            }
            return $Destaques;
        }
	}
?>