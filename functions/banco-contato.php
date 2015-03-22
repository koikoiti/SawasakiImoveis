<?php
	class bancocontato extends banco{
	   
	    #Mail contato
		function SendMailContato($email, $nome){
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
            $mail->Subject = 'Contato através do site da Sawasaki Imóveis!';
            
            #Corpo do e-mail
            $aux = parent::CarregaHtml('Mail/contato-cliente');
            $aux = str_replace('<%NOME%>', $nome, $aux);
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
        function SendMailEmpresa($email, $nome, $sobrenome, $telefone, $comentario){
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
            $mail->Subject = 'Contato através do site da Sawasaki Imóveis!';
            
            #Corpo do e-mail
            $aux = parent::CarregaHtml('Mail/contato-empresa');
            $aux = str_replace('<%NOME%>', $nome, $aux);
            $aux = str_replace('<%SOBRENOME%>', $sobrenome, $aux);
            $aux = str_replace('<%TELEFONE%>', $telefone, $aux);
            $aux = str_replace('<%EMAIL%>', $email, $aux);
            $aux = str_replace('<%MENSAGEM%>', $comentario, $aux);
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
	}
?>