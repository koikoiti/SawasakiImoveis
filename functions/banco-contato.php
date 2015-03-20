<?php
	class bancocontato extends banco{
	   
	    #Mail contato
		function SendMailContato($email, $nome){
            require_once './app/PHPMailer/PHPMailerAutoload.php';
            
            $mail = new PHPMailer();
            $mail->SMTPDebug = 2;
            // Charset para evitar erros de caracteres
            $mail->CharSet = 'UTF-8';
            
            // Dados de quem está enviando o email
            $mail->From = 'admin@sawasakiimoveis.com.br';
            $mail->FromName = 'Sawasaki Imóveis';
            
            // Setando o conteudo
            $mail->isHTML(true);
            $mail->Subject = 'Contato através do site da Sawasaki Imóveis!';
            
            #Corpo do e-mail
            $aux = parent::CarregaHtml('Mail/contato-cliente');
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
            $mail->addAddress($email, $nome);
            
            // Enviando o e-mail
            if($mail->send()){
                echo "<script type='text/javascript'>alert('Mensagem enviada com sucesso!')</script>";
				return true;
			}else{
				echo "<script type='text/javascript'>Erro no envio de e-mail.</script>";
			}
		}
        
        #Mail empresa
        function SendMailEmpresa($email, $nome, $sobrenome, $telefone, $comentario){
            require_once './app/PHPMailer/PHPMailerAutoload.php';
            
            $mail = new PHPMailer();
            $mail->SMTPDebug = 3;
            // Charset para evitar erros de caracteres
            $mail->CharSet = 'UTF-8';
            
            // Dados de quem está enviando o email
            $mail->From = 'admin@sawasakiimoveis.com.br';
            $mail->FromName = 'Sawasaki Imoveis';
            
            // Setando o conteudo
            $mail->isHTML(true);
            #$mail->Subject = 'Contato através do site da Sawasaki Imóveis!';
            
            #Corpo do e-mail
            $aux = parent::CarregaHtml('Mail/contato-empresa');
            echo $aux;die;
            $mail->Body     = $aux;
            
            // Validando a autenticação
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host     = "ssl://smtp.googlemail.com";
			$mail->Port     = 465;
			$mail->Username = 'sawasakiimoveis@gmail.com';
			$mail->Password = 'vendas.sawasakitop10';
            
            // Setando o endereço de recebimento
            $mail->addAddress('evandrokoiti@gmail.com', 'Sawasaki Imoveis');
            echo $mail->send();
            // Enviando o e-mail
            if($mail->send()){
                echo 1;die;
				return true;
			}else{
				echo "<script type='text/javascript'>Erro no envio de e-mail.</script>";
			}
		}
	}
?>