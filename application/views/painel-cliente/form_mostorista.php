<?php $this->load->view('header.php'); ?>
<div class="main-content">
    <div class="row">
        <div class="col-lg-12">
            <form class="card shadow-material-1 form-type-combine card-round" method="POST" action="#" id="form-motorist" >
                <header class="card-header">
                    <h5 class="card-title"><strong>CADASTRAR UM NOVO MOTORISTA </strong></h5>
                </header>
                <div class="card-body">
                    <div class="row">
                        <div class=" col-md-6 col-xl-6">
                            <div class="form-group require">
                                <label>Nome:</label>
                                <input class="form-control text-uppercase" autocomplete="off" autofocus type="text" name="name_motorist" id="name_motorist" value="<?=empty($dados_edit->name_motorist) ? "":$dados_edit->name_motorist?>">
                            </div>
                        </div>
						<div class=" col-md-6 col-xl-6">
							<div class="form-group require">
								<label>Senha:</label>
								<input class="form-control" autocomplete="off" autofocus type="password" name="password" id="password">
							</div>
						</div>
                    </div>
                    <div class="divider fs-14"> <span class="ti-truck"></span> &nbsp; Dados do Veiculo</div>
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="form-group require">
                                <label>Tipo Veiculo:</label>
                                <select class="form-control" data-provide="selectpicker" id="type_automobile" name="type_automobile">
                                    <option value=''>Selecione um automovel</option>
                                    <option <?=empty($dados_edit->type_automobile) ? "":selected_current($dados_edit->type_automobile,  "car")?>  value='car'>Carro</option>
                                    <option <?=empty($dados_edit->type_automobile) ? "":selected_current($dados_edit->type_automobile,  "motorcycle")?>  value='motorcycle'>Moto</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group require">
                                <label>Placa do Veiculo:</label>
                                <input class="form-control" autocomplete="off" autofocus type="text" name="car_board" id="car_board" value="<?= (empty($dados_edit->car_board)) ? "" : $dados_edit->car_board ?>">
                                <small id="small" style="font-size: 15px;"></small>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>Descrição do Veiculo:</label>
                                <input class="form-control" autocomplete="off" autofocus type="text" name="description_automobile" id="description_automobile" value="<?= (empty($dados_edit->description_automobile)) ? "" : $dados_edit->description_automobile ?>">
                                <small id="small" style="font-size: 15px;"></small>
                            </div>
                        </div>
                    </div>
                </div>
<!--                <div id="card-loading"></div>-->
                <footer class="card-footer text-right">
                    <button class="btn btn-info" id="btnInserir">Salvar Motorista</button>
                </footer>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('footer.php'); ?>
<script type="text/javascript">
    app.ready(function () {

        $('#form-motorist').validate({
            rules: {
                name_motorist: {required: true},
				password: {required: true, minlength: 6},
                type_automobile: {required: true},
                car_board: {required: true}
            },
            messages: {
                name_motorist: {required: 'Campo Obrigatório'},
				password: {required: 'Campo Obrigatório', minlength: jQuery.validator.format("A senha deve conter no minimo {0} caracteres")},
                type_automobile: {required: 'Campo Obrigatório'},
                car_board: {required: 'Campo Obrigatório'}
            },
            submitHandler: function (form) {
                var dados = $(form).serialize();
                $.ajax({
                    type: "POST",
                    url: "<?= current_url(); ?>",
                    data: dados,
                    dataType: 'JSON',
                    beforeSend: function() {
                        show_hide_card_loading();
                    },
                    success: function (data) {
                        if (!data.erro) {
                            show_hide_messagem(data.success);
							document.getElementById('form-motorist').reset('');
                        } else {
                            show_hide_messagem(data.erro, false);
                        }
                        show_hide_card_loading(false);
                    }
                });
                return false;
            },
            errorClass: "text-danger",
            errorElement: "span",
            highlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('text-danger');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('text-danger');
                $(element).parents('.control-group').addClass('success');
            }
        });
    });
</script>
</body>
</html>
