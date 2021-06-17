<?php $this->load->view('header'); ?>
<div class="main-content">

	<div class="row">
		<div class="col-lg-12">
			<div class="col-xl-12 card card-round shadow-material-1">
				<div class="card-header">
					<h4 class="card-title text-left text-uppercase fw-500">
						<span class="text-muted">Objetos pendentes</span><br>
						<small class="text-lowercase">Operações conferida com pendências.</small>
					</h4>
					<div class="card-header-actions">
						<a class="btn btn-info" href="<?=$tipo == 'all' ? base_url("portal-cliente"):base_url("portal-cliente/all")?>">
							<?=$tipo == 'all' ? "Volta":"Mostrar Todos" ?>
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
							<h5 class="card-title">
										<span>
											<b>LOTE: <?=$coleta->opID?> </b> -
											<?php
											$restante = $this->crud->query("SELECT * FROM item_coleta WHERE id_coleta = '{$coleta->opID}' AND status_afericao != 'entregue'")->num_rows();
											$total = $this->crud->query("SELECT * FROM item_coleta WHERE id_coleta = '{$coleta->opID}'")->num_rows();
											echo "$total objeto(s) - {$restante} objeto(s) não encontrado(s) ou não conferido";
											?>
										</span> <br>
								<small class="fs-12"><?=$coleta->name_motorist?></small>
							</h5>
							<span class="fs-14"><?=$coleta->status?></strong></span>
						</div>

						<div id="<?=$coleta->opID?>" class="collapse">

							<!-- RECUPERA OS DADOS DA OPERAÇÃO -->
							<?php $itemOperacao = $this->crud->findAll('item_coleta', "id_coleta = '{$coleta->opID}' AND status_afericao != 'entregue'")->result(); ?>

							<div class="card-body">
								<div id="atualizar">
							<?php if($itemOperacao): ?>

									<table class="table table-separated" data-provide="datatables">
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
												<td><?=$item->objeto?></td>
												<input type="hidden" name="objetos" value="<?=$item->objeto?>">
												<td class="text-right table-actions">
													<?php if($this->nivel == 1): ?>
													<a class="btn btn-square btn-outline btn-success" id="baixa" href="#" onclick="finalizar('<?php echo base_url('baixa/pendencias/'.$item->id); ?>', 'Que esse objeto esta na AGF?')"><i class="ti-check"></i></a>
													<?php endif; ?>
													<button type="submit" class="btn btn-square btn-outline btn-success" id="integration" href="#"><i class="ti-truck"></i></button>
												</td>
											</tr>
										</form>
										<?php endforeach; ?>
										</tbody>
									</table>
								<?php else: ?>
									<div class="text-center">
										<p></p>
										<span class="ti-layers-alt fs-18 text-muted"></span>
										<p class="text-muted fs-18">essa operação não contem itens.</p>
									</div>
								<?php endif; ?>
								</div>
							</div>
						</div>

						<footer class="card-footer">
							<div class="row">
								<div class="col-xl-4 col-md-12">
									<strong>Aberto:</strong>
									<span><?=date('d.m.Y H:i:s', strtotime($coleta->data_abertura))?></span>
								</div>
								<div class="col-xl-4 col-md-12">
									<strong>Embarcado:</strong>
									<?php if(!empty($coleta->data_embarcacao)): ?>
										<span><?=date('d.m.Y H:i:s', strtotime($coleta->data_embarcacao))?></span>
									<?php else:
										echo "lote aguardando a ser embarcado...";
									endif; ?>
								</div>
								<div class="col-xl-4 col-md-12">
									<strong>Finalizado:</strong>
									<?php if(!empty($coleta->data_fechamento)): ?>
										<span><?=date('d.m.Y H:i:s', strtotime($coleta->data_fechamento))?></span>
									<?php
										else: echo "lote ainda não finalizado";
									endif; ?>
								</div>
							</div>

							<?php if($restante == 0): ?>
								<a class="btn btn-xs fs-10 btn-bold btn-success" href="#" onclick="finalizar('<?php echo base_url('finalizar/lote/'.$coleta->opID); ?>', 'que vai finalizar esse lote?')">Finalizar</a>
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
