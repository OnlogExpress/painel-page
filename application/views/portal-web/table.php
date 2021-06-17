<?php if($data): ?>
<table class="table table-separated table-sm" data-provide="datatables">
	<thead>
	<tr>
		<th>Objeto</th>
		<th>Data Leitura</th>
		<th>Status</th>
		<th>Data Postagem</th>
		<th class="text-right"></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($data as $datum): ?>
		<tr>
			<th scope="row"><?php echo $datum->objeto; ?></th>
			<th scope="row"><?php echo $datum->data_cadastro; ?></th>
			<th scope="row"><?php echo $datum->status_id; ?></th>
			<th scope="row"><?php echo $datum->data_postagem; ?></th>
			<td class="text-right table-actions">
				<button type="submit" class="btn btn-square btn-outline btn-info" id="integration" href="#"><i class="ti-menu"></i></button>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	Nenhum dados encontrado...
<?php endif; ?>
