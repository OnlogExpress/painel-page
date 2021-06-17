<div class="msg-result lead animated zoom-in" style="position: fixed; bottom: 0; width: 100%; z-index: 99999;"></div>
<?php if ($this->session->flashdata('error')) { ?>
	<div class="msg-result bg-danger p-20 lead"
		 style="position: fixed; bottom: 0; width: 100%; z-index: 99999;"><?= $this->session->flashdata('error') ?></div>
<?php } ?>
<?php if ($this->session->flashdata('success')) { ?>
	<div class="msg-result bg-success p-20 lead"
		 style="position: fixed; bottom: 0; width: 100%; z-index: 99999;"><?= $this->session->flashdata('success') ?></div>
<?php } ?>
<!-- Footer -->
<footer class="site-footer">
	<div class="row">
		<div class="col-md-6">
			<p>
                    <span class="text-md-left">
                         Copyright © 2020 <a href="http://onpostlog.com.br">My Manager</a>. All rights reserved.
                    </span>
			</p>
		</div>
	</div>
</footer>
<!-- END Footer -->
</main>
<!-- END Main container -->
<!-- Scripts -->
<script src="<?php echo base_url(); ?>assets/js/core.min.js" data-provide="typeahead sweetalert fullcalendar jqueryui"></script>
<script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/script.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/main.js"></script>
<script src="<?php echo base_url(); ?>assets/js/validar.js"></script>
<script>
	// if ('serviceWorker' in navigator) {
	// 	console.log("Will the service worker register?");
	// 	navigator.serviceWorker.register('service-worker.js').then(function (reg) {
	// 		console.log("Service worker registrado.");
	// 	});
	// }
	$("#notificacao-modal").modal('show');

	function removeMsg() {
		setTimeout(function () {
			$(".msg-result").css('display', 'none');
		}, 5000);
		$(".msg-result").show();
	}

	$(document).on('click', '#carregaDadosLote', function (e) {
		var id = $(this).data('id');
		var type = $(this).data('aberto'+id);
		if(!type) {
			carregaDadosLote(id);
		}
		$(this).data('aberto'+id, 'true')
	})

	function carregaDadosLote(id) {

		var load = $(".ajax_load");
		$.ajax({
			type: "POST",
			url: "<?php echo base_url('carregar/dados/lote/'); ?>"+id,
			dataType: 'json',
			beforeSend: function () {
				load.fadeIn(200).css("display", "flex");
			},
			success: function (response) {

				document.getElementById("responseData-"+id).innerHTML = response.data;
				load.fadeOut(200);
			}, error: function (e) {
				load.fadeOut(200);
			}
		});
		return false
	}

</script>
<?php
//mostra os script javascripts
if(isset($this->scriptJS)){
	echo $this->scriptJS;
}
?>
