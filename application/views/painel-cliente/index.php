<?php $this->load->view('header'); ?>
<div class="main-content">
	<div class="row">
		<div class="col-xl-12">
			<div class="card card-round shadow-material-1">
				<div class="card-header text-right">
					<h4 class="card-title text-left text-uppercase fw-500"><span class="text-muted">&nbsp;Check List
					</h4>
					<div class="card-header-actions">
						<a class="btn btn-default" href="#" data-toggle="modal" data-target="#modal-left"> <i
									class="fa fa-filter"></i> </a>
					</div>
				</div>
				<div class="card-body">
					<table class="table table-separated table-sm" data-provide="datatables">
						<thead>
						<tr>
							<th>Objeto</th>
							<th>Status</th>
							<th>Data Postagem</th>
							<th class="text-right"></th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<th scope="row">AA004103456BR</th>
							<th scope="row">Aberto</th>
							<th scope="row">-----</th>
							<td class="text-right table-actions">
								<button type="submit" class="btn btn-square btn-outline btn-info" id="integration" href="#"><i class="ti-truck"></i></button>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('footer'); ?>
