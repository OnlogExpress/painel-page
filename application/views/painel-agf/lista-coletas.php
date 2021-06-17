<?php $this->load->view('header'); ?>
<div class="main-content">

	<div class="row">
		<div class="col-lg-12">
			<div class="col-xl-12 card card-round shadow-material-1">
				<div class="card-header">
					<h4 class="card-title text-left text-uppercase fw-500"><span class="text-muted">Lista de operações <?=$tipo != 'all' ? "em andamento":"" ?></span></h4>
					<div class="card-header-actions">
						<a class="btn btn-info" href="<?=$tipo == 'all' ? base_url("portal-agf"):base_url("portal-agf/all")?>">
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
						<div class="card-header cursor-pointer"
							 data-toggle="collapse"
							 data-parent="#accordion-1"
							 href="#<?=$coleta->opID?>"
							 id="carregaDadosLote"
							 data-aberto<?=$coleta->opID?>="false"
							 data-id="<?=$coleta->opID?>"
						>
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
							<div id="responseData-<?=$coleta->opID?>">&nbsp;</div>
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
		<div class="col-xl-12">
			<?php
			if(!empty($registro_por_pagina) || !empty($totalDados)):
				$num_pagina = ceil($totalDados / $registro_por_pagina);
				$maximo_links = 2;
				?>
				<div class="justify-content-lg-end mt-5 text-right">
					<nav>
						<ul class="pagination">
							<li class="page-item">
								<a class="page-link" href="<?=$page?>">
									<span class="ti-arrow-left"></span>
								</a>
							</li>
							<?php
							for($pagina_anterior = $pagina_inicial - $maximo_links; $pagina_anterior <= $pagina_inicial - 1; $pagina_anterior++):
								if($pagina_anterior >= 1):
							?>
								<li class="page-item"><a class="page-link" href="<?=$page?>?pagina=<?=$pagina_anterior?>"><?=$pagina_anterior?></a></li>
							<?php
								endif;
							endfor;
							?>
							<li class="page-item active">
								<a class="page-link" href="#"><?=$pagina_inicial;?></a>
							</li>
							<?php
							for($pagina_proxima = $pagina_inicial + 1; $pagina_proxima <= $pagina_inicial + $maximo_links; $pagina_proxima++):
								if($pagina_proxima <= $num_pagina):
							?>
								<li class="page-item"><a class="page-link" href="<?=$page?>?pagina=<?=$pagina_proxima?>"><?=$pagina_proxima?></a></li>
							<?php
								endif;
							endfor;
							?>
							<li class="page-item">
								<a class="page-link" href="<?=$page?>?pagina=<?=$num_pagina?>">
									<span class="ti-arrow-right"></span>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			<?php
			endif;
			?>
		</div>
	</div>

</div>
<p></p>
<p></p>
<p></p>
<?php $this->load->view('footer'); ?>
