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

<html>
	<body>
		<form action="nota.php" method="post" enctype="multipart/form-data" target="_blank">
		<input type="file" name="nota[]" multiple />
		<input type="submit" value="Enviar Danfe" />
		</form>
	</body>
</html>