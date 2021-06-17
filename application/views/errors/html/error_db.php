<?php

defined('BASEPATH') or exit('Acesso não permitido');
header('Content-Type: application/json');

$retorno = [
	MSG_ERRO => "Erro no servidor",
	STATUS_RETORNO_API => $message,
	LABEL_CODIGO_ERRO => 6
];
echo json_encode($retorno);
