<?php $this->load->view('header.php'); ?>
<div class="main-content">
    <div class="row">
		<?php
		if($operacao->status == 'finalizado' || $operacao->status == 'cancelado'):

		?>
		<div class="col-lg-12">
        <div class="alert <?=$operacao->status == 'finalizado' ? "alert-info":"alert-danger"; ?>">
			<span class="fs-18 fw-500">
				Este lote foi <?php echo $operacao->status; ?>.
			</span>
		</div>
			<a class="btn btn-xs fs-10 btn-bold btn-info" href="<?=base_url()?>">Voltar</a>
		</div>
		<?php
		else:
		?>
			<div class="col-lg-6">
				<div class="card shadow-material-1 card-round">
					<header class="card-header">
						<h5 class="card-title"><strong>aplica acão na operacão: <?=$operacao->id?></strong></h5>
					</header>
					<div class="card-body">
						<div class="row doc-btn-spacing">
							<div class="col-xl-12">
								<?php if($operacao->status == 'embarcado'): ?>
									<button onclick="mudaStatus(<?=$operacao->id?>, 'liberar');" class="btn btn-w-md btn-multiline btn-info"><i class="ti-reload fs-20"></i><br>Liberar</button>
								<?php endif; ?>
								<?php if($operacao->status == 'entregue'): ?>
									<button onclick="mudaStatus(<?=$operacao->id?>, 'finalizar');" class="btn btn-w-md btn-multiline btn-success"><i class="ti-check fs-20"></i><br>Finalizar</button>
								<?php endif; ?>
								<?php if($operacao->status == 'aberto'): ?>
									<button onclick="mudaStatus(<?=$operacao->id?>, 'cancelar');" class="btn btn-w-md btn-multiline btn-danger"><i class="ti-close fs-20"></i><br>Cancelar</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card shadow-material-1 card-round">
					<header class="card-header">
						<h5 class="card-title"><strong>Observações</strong></h5>
					</header>
					<div class="card-body">
						<ol class="timeline timeline-activity timeline-point-sm timeline-content-right text-left w-100 py-20 pl-20">

							<li class="timeline-block">
								<div class="timeline-point timeline-point-info">
									<span class="badge badge-ring badge-info"></span>
								</div>
								<div class="timeline-content">
									<span class="fs-16 fw-500 text-info">Liberar</span>
									<p>
										Após <b>liberar</b> o lote sera modificado para aberto.
									</p>
								</div>
							</li>

							<li class="timeline-block">
								<div class="timeline-point timeline-point-success">
									<span class="badge badge-ring badge-success"></span>
								</div>
								<div class="timeline-content">
									<span class="fs-16 fw-500 text-success">Finalizar</span>
									<p>
										Após <b>finalizar</b> o lote, não sera mais aplicada nenhuma ação.
									</p>
								</div>
							</li>

							<li class="timeline-block">
								<div class="timeline-point timeline-point-danger">
									<span class="badge badge-ring badge-danger"></span>
								</div>
								<div class="timeline-content">
									<span class="fs-16 fw-500 text-danger">Cancelar</span>
									<p>
										Após <b>cancelar</b> o lote, não sera mais visto.
									</p>
								</div>
							</li>
						</ol>
					</div>
				</div>
			</div>
		<?php
		endif;
		?>
    </div>
</div>
<?php $this->load->view('footer.php'); ?>
<script type="text/javascript">
    app.ready(function () {

    });

	function mudaStatus(id, status){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('mudarStatus'); ?>",
			data: {
				id:id,
				status:status
			},
			dataType: 'JSON',
			beforeSend: function() {
				show_hide_card_loading();
			},
			success: function (data) {
				if (!data.erro) {
					show_hide_messagem(data.success);
				} else {
					show_hide_messagem(data.erro, false);
				}
				show_hide_card_loading(false);
			}
		});
		return false;
	}
</script>
</body>
</html>
