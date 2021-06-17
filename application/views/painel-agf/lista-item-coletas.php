<?php if($resultDados): ?>
	<div class="card-body">
		<table class="table table-separated table-sm" data-provide="datatables">
			<thead>
			<tr>
				<th>#</th>
				<th>OBJETO</th>
				<th class="text-center w-100px"></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($resultDados as  $item): ?>
				<form method="POST" target="_blank" action="https://www2.correios.com.br/sistemas/rastreamento/ctrl/ctrlRastreamento.cfm?">
					<tr>
						<th scope="row"><?=$item->id?></th>
						<td>
							<?=$item->objeto?>
							<input type="hidden" name="objetos" value="<?=$item->objeto?>">
						</td>
						<td class="text-right table-actions">
							<button type="submit" class="btn btn-square btn-outline btn-info" id="integration" href="#"><i class="ti-truck"></i></button>
						</td>
					</tr>
				</form>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<div class="text-center">
		<p></p>
		<span class="ti-layers-alt fs-18 text-muted"></span>
		<p class="text-muted fs-18">esse lote não contem itens.</p>
	</div>
<?php endif; ?>
