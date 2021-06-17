<?php $this->load->view('header-extra/header') ?>
<div class="row min-h-fullscreen center-vh m-0">
	<div class="col-12">
		<div class="card card-shadowed card-round px-25 py-30 w-400px mx-auto" style="max-width: 100%">
			<h2 class="text-center fw-500">Acesso Restrito</h2>
			<br>
			<?php if ($this->session->flashdata('error')) { ?>
				<div class="row">
					<div class="col-12">
						<div id="resp" class="p-10 px-25 bg-danger text-center"><?= $this->session->flashdata('error') ?></div>
					</div>
				</div>
			<?php } ?>
			<form class="form-type-combine" method="POST" action="#" accept-charset="UTF-8" id="form-logar">
				<div class="form-group">
					<label for="username">E-mail:</label>
					<input type="text" class="form-control" id="type_email" name="type_email">
				</div>
				<div class="form-group">
					<label for="password">Senha:</label>
					<input type="password" class="form-control" name="senha">
				</div>
				<br>
				<button class="btn btn-bold btn-block btn-info btn-lg" type="submit">Entrar</button>
			</form>
		</div>
	</div>
</div>
</body>
    <?php $this->load->view('header-extra/footer') ?>
    <script type="text/javascript">
        $(document).ready(function () {

            $("#form-logar").validate({
                rules: {
                    type_email: {required: true},
                    senha: {required: true, minlength: 4}
                },
                messages: {
                    type_email: {required: 'Campo Obrigatório.'},
                    senha: {required: 'Campo Obrigatório.', minlength: jQuery.validator.format("Pelo menos {0} caracteres são necessários!")}
                },
                submitHandler: function (form) {
                    $("#resposta").html('');
                    $('#resp').hide();
                    var dados = $(form).serialize();
                    $.ajax({
                        type: "POST",
                        url: "./autenticacao",
                        data: dados,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#hidden').css('display', 'block');
                            $('.load-ajax').css('display', 'none');
                            $('#resp').html('');
                        },
                        success: function (data) {
                            if (data.result === true) {
                                var returnUrl = $("#returnUrl").val();
                                window.location.href = '<?php echo base_url(); ?>';
                            } else {
                                $('#hidden').css('display', 'none');
                                $('.load-ajax').css('display', 'block');
                                app.toast(data.mensagem, {
                                    actionUrl: 'something',
                                    bgColor: '#FF1356'
                                });
                            }
                        }
                        ,
                        error: function (data) {
                            $('#hidden').css('display', 'none');
                            $('.load-ajax').css('display', 'block');
                            $("#resposta").html('Ops, erro do sistema!');
                        }
                    });
                    return false;
                },
                errorClass: "text-danger",
                errorElement: "span",
                highlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-control').addClass('text-danger');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-control').removeClass('text-danger');
                    $(element).parents('.form-control').addClass('text-success');
                }
            });
        });
    </script>
</html>


