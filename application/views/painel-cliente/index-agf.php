<?php $this->load->view('header'); ?>
<div class="main-content">

	<div class="row">
		<div class="col-lg-12">
			<div class="col-xl-12 card card-round shadow-material-1">
				<div class="card-header">
					<h4 class="card-title text-left text-uppercase fw-500"><span class="text-muted">Lista de operações <?=$tipo != 'all' ? "em andamento":"" ?></span></h4>
					<div class="card-header-actions">
						<a class="btn btn-info" href="<?=$tipo == 'all' ? base_url("portal-cliente"):base_url("portal-cliente/all")?>">
							<?=$tipo == 'all' ? "Volta":"Realizada" ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="accordion" id="accordion-1">
					<?php
						/** @var TYPE_NAME $coletas */
						$coletas = $coletas;
						if ( $coletas):
					?>
						<?php foreach ($coletas as  $coleta): ?>

							<div class="card card-round shadow-material-1">
								<div class="card-header cursor-pointer" data-toggle="collapse" data-parent="#accordion-1" href="#<?=$coleta->opID?>">
									<h5 class="card-title text-uppercase">
										<span>
											<b><?=$coleta->name_motorist?></b> -
											<?php
												echo $this->crud->query("SELECT * FROM item_coleta WHERE id_coleta = '{$coleta->opID}'")->num_rows();
											?> objeto(s)
										</span> <br>
										<small>Operação <?=$coleta->opID?></small>
									</h5>
									<span class="fs-14"><?=$coleta->status?></strong></span>
								</div>

								<div id="<?=$coleta->opID?>" class="collapse">
									<!-- RECUPERA OS DADOS DA OPERAÇÃO -->
									<?php $itemOperacao = $this->crud->findAll('item_coleta', "id_coleta = '{$coleta->opID}' AND status_afericao != 'entregue'")->result(); ?>

									<?php if($itemOperacao): ?>
										<div id="accordion-2">
										<?php foreach ($itemOperacao as  $item): ?>
											<div class="card-body form-type-combine">
												<div class="card-header cursor-pointer card-round shadow-material-1" data-toggle="collapse" data-parent="#accordion-2" href="#<?=$coleta->opID.'-'.$item->id?>">
													<h5 class="card-title text-uppercase">
															<strong>OBJETO: <?=$item->objeto?></strong>
													</h5>
												</div>
												<div id="<?=$coleta->opID.'-'.$item->id?>" class="collapse">
													<div class="card-body form-type-combine">
														<p class="fs-16"><b>PLP:</b> <span><?=$item->numero?></span></p>
														<p class="fs-16"><b>Cartão:</b> <span><?=$item->cartao?></span></p>
														<p class="fs-16"><b>Remetente:</b> <span><?=$item->remetente?></span></p>
														<p class="fs-16"><b>Servico:</b> <span><?=$item->servico?></span></p>
														<p class="fs-16"><b>CEP Destino:</b> <span><?=$item->cep_destinatario?></span></p>
														<p class="fs-16"><b>Peso/Medidas:</b> <span><?=$item->peso.' - [ '.$item->altura.','.$item->largura.','.$item->comprimento.' ]'?></span></p>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
										</div>
									<?php else: ?>
										<div class="text-center">
											<p></p>
											<span class="ti-layers-alt fs-18 text-muted"></span>
											<p class="text-muted fs-18">essa operação não contem itens.</p>
										</div>
									<?php endif; ?>
								</div>

								<footer class="card-footer flexbox align-items-center">
									<div>
										<strong>Aplicado em:</strong>
										<span><?=date('d.m.Y H:i:s', strtotime($coleta->data_abertura))?></span>
									</div>
									<div class="card-hover">
										<?php if($this->nivel == 2): ?>
											<?php if(!$tipo): ?>
												<a class="btn btn-xs fs-10 btn-bold btn-info" href="<?=base_url("aplicar/{$coleta->opID}")?>">Aplicar</a>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</footer>
							</div>
						<?php endforeach; ?>
				<?php else: ?>
					<div class="text-center">
						<span class="ti-receipt fs-60 text-muted"></span>
						<p class="text-muted fs-22">Nenhum resultado encontrado.</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('footer'); ?>
