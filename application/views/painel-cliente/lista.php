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
									<?php $itemOperacao = $this->crud->findAll('item_coleta', "id_coleta = '{$coleta->opID}'")->result(); ?>

									<?php if($itemOperacao): ?>
										<div class="card-body">
											<table class="table table-separated table-sm" data-provide="datatables">
												<thead>
												<tr>
													<th>#</th>
													<th>OBJETO</th>
													<th class="text-center w-100px"></th>
												</tr>
												</thead>
												<tbody>
												<?php foreach ($itemOperacao as  $item): ?>
													<form method="POST" target="_blank" action="https://www2.correios.com.br/sistemas/rastreamento/ctrl/ctrlRastreamento.cfm?">
														<tr>
															<th scope="row"><?=$item->id?></th>
															<td>
																<?=$item->objeto?>
																<input type="hidden" name="objetos" value="<?=$item->objeto?>">
															</td>
															<td class="text-right table-actions">
																<button type="submit" class="btn btn-square btn-outline btn-info" id="integration" href="#"><i class="ti-truck"></i></button>
															</td>
														</tr>
													</form>
												<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									<?php else: ?>
										<div class="text-center">
											<p></p>
											<span class="ti-layers-alt fs-18 text-muted"></span>
											<p class="text-muted fs-18">esse lote não contem itens.</p>
										</div>
									<?php endif; ?>
								</div>
								<footer class="card-footer">

									<div class="row">
										<div class="col-xl-4 col-md-12">
											<strong>Aberto em:</strong>
											<span><?=date('d.m.Y H:i:s', strtotime($coleta->data_abertura))?></span>
										</div>
										<div class="col-xl-4 col-md-12">
											<strong>Embarcado em:</strong>
											<?php if(!empty($coleta->data_embarcacao)): ?>
												<span><?=date('d.m.Y H:i:s', strtotime($coleta->data_embarcacao))?></span>
											<?php else:
												echo "lote aguardando a ser embarcado...";
											endif; ?>
										</div>
										<div class="col-xl-4 col-md-12">
											<strong>Finalizado em:</strong>
											<?php if(!empty($coleta->data_fechamento)): ?>
												<span><?=date('d.m.Y H:i:s', strtotime($coleta->data_fechamento))?></span>
											<?php
											else: echo "lote ainda não finalizado";
											endif; ?>
										</div>
									</div>

									<?php if($this->nivel == 2): ?>
										<?php if(!$tipo): ?>
											<a class="btn btn-xs fs-10 btn-bold btn-info" href="<?=base_url("aplicar/{$coleta->opID}")?>">Aplicar</a>
										<?php endif; ?>
									<?php endif; ?>
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
