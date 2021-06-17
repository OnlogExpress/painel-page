<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="Edson Costa" />
        <meta name="keywords" content="onpostlog, frete, contação, transportadora" />
        <meta name="description" content="onpostlog sistema de postagem e cotação de frete" />
        <title>App - Controle de Operação</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i" rel="stylesheet">
        <!-- Styles -->
        <link href="<?php echo base_url(); ?>assets/css/core.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/app.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/style.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/croppie.css" rel="stylesheet">

        <!-- Favicons -->
        <link rel="icon" href="<?php echo base_url('assets/img/log1.png'); ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo base_url('assets/img/log1.png'); ?>" type="image/x-icon">

		<style>
			.ajax_load {
				display: none;
				position: fixed;
				align-items: center;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				background: rgba(0, 0, 0, 0.5);
				z-index: 99999;
			}
			.ajax_load_box {
				margin: auto;
				text-align: center;
				color: #fff;
				font-weight:500;
				text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
			}
			.ajax_load_box_circle {
				border: 16px solid #e3e3e3;
				border-top: 16px solid #1E355E;
				border-radius: 50%;
				margin: auto;
				width: 80px;
				height: 80px;
				-webkit-animation: spin 1.2s linear infinite;
				-o-animation: spin 1.2s linear infinite;
				animation: spin 1.2s linear infinite;
			}
			.ajax_load_box_title {
				margin-top: 15px;
				font-weight: 500;
			}

			/* Safari */
			@-webkit-keyframes spin {
				0% { -webkit-transform: rotate(0deg); }
				100% { -webkit-transform: rotate(360deg); }
			}

			@keyframes spin {
				0% { transform: rotate(0deg); }
				100% { transform: rotate(360deg); }
			}


			.pac-item {
				padding: 8px;
				cursor: pointer;
			}

			.pac-item-query {
				font-size: 18px;
			}
		</style>
        <!-- Fonts -->
    </head>

    <body data-provide="pace">
        <!-- Preloader -->
        <div class="preloader">
            <div class="spinner-dots">
                <span class="dot1"></span>
                <span class="dot2"></span>
                <span class="dot3"></span>
            </div>
        </div>
		<div class="ajax_load">
			<div class="ajax_load_box">
				<div class="ajax_load_box_circle"></div>
				<p class="ajax_load_box_title" id="textAltera">Aguarde, carregando...</p>
			</div>
		</div>
        <?php $this->load->view('menu.php'); ?>

