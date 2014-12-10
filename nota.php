<?php
// @autor Pablo Montenegro
// Utiliza as APIs SwiftMailer e NFePHP
// 		-> http://www.nfephp.org/
//		-> http://swiftmailer.org/
// Baseado nos tutoriais
// 		-> http://www.brunoafonso.net/codigo-php-para-gerar-a-danfe/
// 		-> http://coreyworrell.com/blog/article/php-html-email-pdf-attachment
// FALTA FAZER
// -> Adicionar Envio de Multiplos Arquivos - OK
// -> Enviar email em HTML - OK, mas sem imagens
// -> Login / Autenticação / Só aceitar do meu CNPJ - Pendente

?>

<?php 
require_once('libs/DanfeNFePHP.class.php');
require_once('libs/MailNFePHP.class.php');
require_once('swift/swift_required.php');

foreach ($_FILES["nota"]['tmp_name'] as $key => $nota) // Pegando cada XML enviado
{
	move_uploaded_file($_FILES["nota"]["tmp_name"][$key],$_FILES["nota"]["name"][$key]);
	$arq = $_FILES["nota"]["name"][$key];
	echo '<br><br>Gerada nota a partir do arquivo ' . $arq;
	echo '<br><br>';
	// Convertendo XML para PDF
	if ( is_file($arq) ){
	$docxml = file_get_contents($arq);
	$danfe = new DanfeNFePHP($docxml, 'P', 'A4','../images/logo.jpg','I','');
	$id = $danfe->montaDANFE();
	$teste = $danfe->printDANFE($id.'.pdf','S');
	}
	$url = $id . '.pdf';
	
	// Como não consegui ler o XML com o SimplesXML, aqui faz a busca pelo email do cliente no conteudo do XML
	$xml = (string)$docxml;
	$pattern = '/([A-Za-z0-9\.\-\_\!\#\$\%\&\'\*\+\/\=\?\^\`\{\|\}]+)\@([A-Za-z0-9.-_]+)(\.[A-Za-z]{2,5})/';
	preg_match_all($pattern,$xml,$emails);
	$new_emails = array_unique($emails[0]);
	foreach ($new_emails as $mkey => $val)
	{
		  echo 'O email será enviado para ' . $val;
		  echo '<br>...<br><br>';
	}
	// Fim da busca
	
	$cliente = (string)$val;

	$mailer = new Swift_Mailer(new Swift_MailTransport()); // Cria a instância do SwiftMailer

	$message = Swift_Message::newInstance()
				   ->setSubject('Assunto do Email') // Assunto do email
				   ->setTo(array($cliente => ' ')) // Email do cliente que vai receber a nota
				   ->setBcc(array(
							'emailbcc1@site.com.br' => 'Nome 1', // Destinatario Bcc 1
							'emailbcc2@site.com.br' => 'Nome 2', // Destinatario Bcc 2
							'emailbcc3@site.com.br' => 'Nome 3' //  Destinatario Bcc 3+
							)) 
				   ->setFrom(array('remetente@site.com.br' => 'Nome Remetente')) // From:
				   ->setBody("
								<p><strong>Prezado cliente</strong>,<br/>
								aqui vai sua mensagem pro cliente em HTML (porém a tag <img> não funciona).", 'text/html'
							) // Body message
				   ->attach(Swift_Attachment::newInstance($teste, 'nomedanota.pdf', 'application/pdf')) // Anexa a nota em PDF
				   ->attach(Swift_Attachment::newInstance($docxml, 'nomedanota.xml', 'application/xml')); // Anexa o XML

	unlink($arq); // Deleta o xml gerado
	// Agora o envio do email
	if ($mailer->send($message))
		echo 'Email enviado com sucesso para ' . $cliente . '<br><br>-----------------------------------------------------------------------------------------<br>';
	else
		echo 'Ocorreu um erro';
}
?>