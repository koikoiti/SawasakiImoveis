<?php
	class bancocontato extends banco{
	   
		function SendMailContato($email, $nome){
		  
            require_once './app/PHPMailer/PHPMailerAutoload.php';
            
            $mail = new PHPMailer();
            $mail->SMTPDebug = 0;
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
                echo "<script type='text/javascript'>Mensagem enviada com sucesso!</script>";
				return true;
			}else{
				echo "<script type='text/javascript'>Erro no envio de e-mail.</script>";
			}
		}
        
        function SendMailEmpresa($email, $nome, $sobrenome, $telefone, $comentario){
		  
            require_once './app/PHPMailer/PHPMailerAutoload.php';
            
            $mail = new PHPMailer();
            $mail->SMTPDebug = 0;
            // Charset para evitar erros de caracteres
            $mail->CharSet = 'UTF-8';
            
            // Dados de quem está enviando o email
            $mail->From = 'admin@sawasakiimoveis.com.br';
            $mail->FromName = 'Sawasaki Imóveis';
            
            // Setando o conteudo
            $mail->isHTML(true);
            $mail->Subject = 'Contato através do site da Sawasaki Imóveis!';
            
            #Corpo do e-mail
            $aux = parent::CarregaHtml('Mail/contato-empresa');
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
            $mail->addAddress(emailRecebimento, 'Sawasaki Imóveis');
            
            // Enviando o e-mail
            if($mail->send()){
				return true;
			}else{
				echo "<script type='text/javascript'>Erro no envio de e-mail.</script>";
			}
		}
	}
?>