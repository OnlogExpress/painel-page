<!-- Sidebar -->
<aside class="sidebar menu-bordery sidebar-color-info sidebar-icons-right sidebar-icons-boxed sidebar-expand-lg sidebar-light">
    <header class="sidebar-header bg-white text-left">
        <span class="logo text-info fw-500 fs-28 text-shadow">
           My Manager
        </span>
    </header>

    <nav class="sidebar-navigation">
        <ul class="menu  menu-bordery">

            <li class="menu-category">Preview</li>

            <li class="menu-item <?php if (isset($menuHome)) {echo 'active';} ?>">
                <a class="menu-link" href="<?= base_url()?>">
                    <span class="icon ti-home"></span>
                    <span class="title">
                        <span>Home</span>
                    </span>
                </a>
            </li>
			<?php if($this->nivel == 2): ?>
			<li class="menu-item <?php if (isset($menuMotorista)) {echo 'active';} ?>">
				<a class="menu-link" href="<?= base_url('motoristas')?>">
					<span class="icon ti-user"></span>
					<span class="title">
                        <span>Motorista</span>
                    </span>
				</a>
			</li>
			<?php endif; ?>

			<?php if($this->nivel == 1): ?>
				<li class="menu-item <?php if (isset($menuConferenciaWeb)) {echo 'active';} ?>">
					<a class="menu-link" href="<?= base_url('conferencia-web')?>">
						<span class="icon ti-layers-alt"></span>
						<span class="title">
                        <span>Conferência</span>
                    </span>
					</a>
				</li>
			<?php endif; ?>

			<?php if($this->nivel == 1): ?>
				<li class="menu-item <?php if (isset($menuPesquisa)) {echo 'active';} ?>">
					<a class="menu-link" href="<?= base_url('pesquisa')?>">
						<span class="icon ti-search"></span>
						<span class="title">
                        <span>Pesquisa</span>
                    </span>
					</a>
				</li>
			<?php endif; ?>

			<?php if($this->nivel == 3 || $this->nivel == 1): ?>
				<li class="menu-item <?php if (isset($menuColetorWeb)) {echo 'active';} ?>">
					<a class="menu-link" href="<?= base_url('coletor/web')?>">
						<span class="icon ti-bar-chart"></span>
						<span class="title">
                        <span>Coletor WEB</span>
                    </span>
					</a>
				</li>
			<?php endif; ?>

			<?php if($this->nivel == 1): ?>
			<li class="menu-item <?php if (isset($menuPendencias)) {echo 'active';} ?>">
				<a class="menu-link" href="<?= base_url('pendencias')?>">
					<span class="icon ti-target"></span>
					<span class="title">
                        <span>Pendencias</span>
                    </span>
				</a>
			</li>
			<?php endif; ?>
        </ul>
    </nav>
	<footer>
		<nav class="sidebar-navigation sidebar-color-danger">
			<ul class="menu menu-bordery">
				<li class="menu-item">
					<a class="menu-link" href="<?= base_url('logout')?>">
						<span class="icon ti-power-off"></span>
						<span class="title"> Desconectar</span>
					</a>
				</li>
			</ul>
		</nav>
	</footer>
</aside>
<!-- Topbar -->
<header class="topbar bg-white">
    <div class="topbar-left">
        <span class="topbar-btn text-white sidebar-toggler"><i>&#9776;</i></span>
    </div>
</header>
<!-- END Topbar -->
<main class="main-container">
