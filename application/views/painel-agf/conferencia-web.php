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
			<div  class="card shadow-material-1 form-type-combine card-round" id="form-conf" >
				<header class="card-header">
					<h5 class="card-title"><strong>CONFERÊNCIA WEB </strong></h5>
				</header>
				<div class="card-body">
					<div class="row">
						<!--<div class=" col-md-2 col-xl-2">
							<div class="form-group require">
								<label>LOTE:</label>
								<input style="height: 250px; font-size: 120px" maxlength="13" max="13" class="form-control text-uppercase" autocomplete="off" autofocus type="number" name="lote" id="lote">
							</div>
						</div>-->
						<div class=" col-md-12 col-xl-12">
							<div class="form-group require">
								<label>OBJETO:</label>
								<input style="height: 250px; font-size: 120px" maxlength="13" max="13" class="form-control text-uppercase" autocomplete="off" autofocus type="text" name="leitor" id="leitor">
							</div>
						</div>
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
<script src="<?php echo base_url('assets/js/core.min.js'); ?>" data-provide="typeahead sweetalert fullcalendar jqueryui"></script>
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
				objeto: ticket,
				/*lote: lote*/
			};
			$(".msg-result").hide();
			$("#hide").hide();
			$.ajax({
				type: "POST",
				url: "<?php echo current_url(); ?>",
				data: data,
				dataType: 'json',
				success: function (response) {
					if(response.erro){
						$('body').css('background', 'red');
						$(".msg-result").show();
						$(".msg-result").html('<div style="background: red; color: white" class="fs-45 p-20 fw-700">' + response.erro + '</div>');
					}else{
						$('body').css('background', '#11e511');
						$("#load_data_table").html(response.data);$(".msg-result").show();
						$(".msg-result").html('<div style="background: #11e511; color: white" class="fs-45 p-20 fw-700">' + response.success + '</div>');
					}
					$("#load_data_table").html(response.data);
				}
			});
			return false
		}
	});
</script>
</body>
</html>
