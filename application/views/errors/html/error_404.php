<?php

defined('BASEPATH') or exit('Acesso não permitido');
header('Content-Type: application/json');

$retorno = [
	MSG_ERRO => 'Função não encontrada',
	STATUS_RETORNO_API => "Erro",
	LABEL_CODIGO_ERRO => 5
];

echo json_encode($retorno);

