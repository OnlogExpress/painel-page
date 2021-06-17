<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Acesso Restrito, Area Administrativa">
	<meta name="keywords" content="acesso painel, login">
	<title>Conferencia WEB</title>
	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i" rel="stylesheet">
	<!-- Styles -->
	<link href="<?php echo base_url('assets/css/core.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/app.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/style.min.css'); ?>" rel="stylesheet">
	<!-- Favicons -->
	<link rel="icon" href="<?php echo base_url('assets/img/log1.png'); ?>" type="image/x-icon">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/log1.png'); ?>" type="image/x-icon">
</head>
<body>
<div class="main-content">
	<div class="row">
		<div class="col-lg-8">
			<div class="card shadow-material-1 form-type-combine card-round" id="form-conf">
				<header class="card-header">
					<h5 class="card-title fw-500">CONFERENCIA OBJETO</h5>
					<?php if(!empty($id)) : ?>
						<span class="fs-25 fw-500">Nº LOTE: <?=$id?></span>
					<?php endif; ?>
				</header>
				<div class="card-body">
					<div class="row">
						<div class=" col-md-12 col-xl-12">
							<div class="form-group require">
								<label>OBJETO:</label>
								<input style="height: 250px; font-size: 120px" maxlength="13" max="13"
									   class="form-control text-uppercase" autocomplete="off" autofocus type="text"
									   name="leitor" id="leitor">
							</div>
						</div>
						<?php if(!empty($id)): ?>
						<div class="col-xl-12 col-md-12">
							<div class="row">
								<div class="col-xl-6">
									<a href="#" class="lead text-dark fs-20 fw-500" id="totalObjetos">Total: <?=str_pad($totalObjetos, 4, 0, STR_PAD_LEFT)?></a>
								</div>
								<div class="col-xl-6 text-right">
									<a href="#" class="btn btn-info" id="fecha">Fecha Lote</a>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<span id="load_data_table"></span>
		</div>
	</div>
</div>
<div class="msg-result lead animated zoom-in" style="position: fixed; bottom: 0; width: 100%; z-index: 99999;"></div>
<script src="<?php echo base_url('assets/js/core.min.js'); ?>"
		data-provide="typeahead sweetalert fullcalendar jqueryui"></script>
<script src="<?php echo base_url('assets/js/app.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/script.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/main.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/validar.js'); ?>"></script>

<script type="text/javascript">
	$(document).ready(function () {

		$(document).on('keyup', '#leitor', function () {
			var ticket = $(this).val();
			var lote = $('#lote').val();
			if (ticket.length === 13) {
				confe(ticket, lote)
				$(this).val('');
				return false;
			}
		});

		function confe(ticket, lote) {
			var data = {
				objeto: ticket
			};
			$(".msg-result").hide();
			$("#hide").hide();
			$.ajax({
				type: "POST",
				url: "<?php echo current_url(); ?>",
				data: data,
				dataType: 'json',
				success: function (response) {

					if (response.redirect) {
						window.location.href = response.redirect
					}

					success(response.message);
					$("#load_data_table").html(response.data);
					$("#totalObjetos").html("Total: " + response.total);

				}, error: function (erro) {
					merro(erro.responseJSON.message)
				}
			});
			return false
		}

		$(document).on('click', '#fecha', function () {

			$(".msg-result").hide();
			$("body").css('background', '');

			<?php if($totalObjetos > 0): ?>

			swal({
				title: "<h4 class='text-danger fw-600 text-uppercase'>Fecha lote!</h4>",
				html: "Deseja realmente fecha este lote?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				cancelButtonText: 'Não, Fechar',
				confirmButtonText: 'Sim, Fechar',
				cancelButtonClass: 'btn btn-danger btn-outline btn-flat',
				confirmButtonClass: 'btn btn-info',
				buttonsStyling: false,
				allowEscapeKey: false,
				allowOutsideClick: false,
			}).then(function () {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url("fechalote/{$id}"); ?>",
					dataType: 'json',
					success: function (response) {

						success(response.message);
						$("#load_data_table").html("");

						setTimeout(function (e) {
							window.location.href = "<?php echo base_url('coletor/web'); ?>";
						}, 2000)

					}, error: function (erro) {
						if (erro.responseJSON.mensagem_erro) {
							return merro(erro.responseJSON.mensagem_erro);
						}
						return  merro(erro.responseJSON.message);
						//Ops, erro ao finalizar lote, tente novamente mais tarde por favor...
					}
				});
			}, function (dismiss) {
				if (dismiss === 'cancel') {
					return;
				}
			});
			return;
			<?php else: ?>
			merro('Este lote não pode ser finalizado, por que não contem nenhum objeto lido.')
			<?php endif; ?>
		});
	});

	function merro(message) {
		$('body').css('background', 'red');
		$(".msg-result").show();
		$(".msg-result").html('<div style="background: red; color: white" class="fs-30 p-20 fw-500">' + message + '</div>');
	}

	function success(message) {
		$('body').css('background', '#1ea93f');
		$(".msg-result").show();
		$(".msg-result").html('<div style="background: #1ea93f; color: white" class="fs-30 p-20 fw-500">' + message + '</div>');
	}
</script>
</body>
</html>
