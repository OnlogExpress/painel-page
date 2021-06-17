<div  class="card shadow-material-1 card-round">
	<div class="card-body">
		<div class="row">
			<div class="col-xl-12">
				<h5 class="fs-50 fw-900 text-center"><?=$result->objeto?></h5>
				<p class="fs-20"><span class="fw-600">LOTE</span>: <span><?=$result->id_coleta?></span></p>
				<p class="fs-20"><span class="fw-600">PLP</span>: <span><?=$result->numero?></span></p>
				<p class="fs-20"><span class="fw-600">Contrato</span>: <span><?=$result->cartao?></span></p>
				<p class="fs-20"><span class="fw-600">Rementente</span>: <span><?=$result->remetente?></span></p>
				<p class="fs-20"><span class="fw-600">Serviço</span>: <span><?=$result->servico?></span></p>
				<p class="fs-20"><span class="fw-600">CEP Destino</span>: <span><?=$result->cep_destinatario?></span></p>
				<p class="fs-20"><span class="fw-600">Peso</span>: <span><?=$result->peso?></span></p>
				<p class="fs-20"><span class="fw-600">Altura</span>: <span><?=$result->altura?></span></p>
				<p class="fs-20"><span class="fw-600">Largura</span>: <span><?=$result->largura?></span></p>
				<p class="fs-20"><span class="fw-600">Comprimento</span>: <span><?=$result->comprimento?></span></p>
				<p class="fs-20"><span class="fw-600">Diamento</span>: <span><?=$result->diametro?></span></p>
				<p class="fs-20"><span class="fw-600">Data Cadastro</span>: <span><?=$result->data_cadastro?></span></p>
			</div>
		</div>
	</div>
</div>
