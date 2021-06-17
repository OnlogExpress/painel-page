<div class="card shadow-material-1 card-round">
	<header class="card-header">
		<h5 class="card-title text-uppercase"><strong>Objetos Recentes</strong></h5>
	</header>
	<div class="card-body">
		<div class="row">
			<div class="col-xl-12">
				<div class="media-list media-list-divided media-list-hover scrollable" style="height:500px">
					<?php if($result): ?>
						<?php foreach ($result as $item): ?>
							<div class="media media-single">
								<img class="avatar avatar-sm" src="<?=base_url('/assets/img/dADi6PLY_400x400.jpg'); ?>" alt="...">
								<span><?php echo $item->objeto; ?></span>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="media media-single">
							<p>Nenhum dados encontrado....</p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
