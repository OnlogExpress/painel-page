<?php $this->load->view('header.php'); ?>
<div class="main-content">
    <div class="row">
        <div class=" col-xl-12 card card-round shadow-material-1">
            <header class="card-header">
                <h4 class="card-title text-left text-uppercase fw-500">Lista de Motorista</h4>
                <a class="btn btn-sm  btn-info" href="#" data-href="<?= base_url('motoristas/adicionar')?>" id="cliquei"><i class="fa fa-plus" ></i> Novo Motorista</a>
            </header>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-xl-12 card shadow-material-1 card-round">    
            <div class="card-body">
            <div id="atualizar">
                    <?php if ($dados_datatable->result()):?>
                        <table class="table table-separated" data-provide="datatables">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Motorista</th>
                                    <th>Placa</th>
                                    <th class="table-actions text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dados_datatable->result() as $motoris): ?>
                                <tr>
                                    <td> <?php echo $motoris->id ?></td>
                                    <td>
                                        <?php
                                            if($motoris->type_automobile == 'car'):
                                                echo "<span data-i8-icon='in_transit' style='width:40px;height:40px;'></span>";
                                           	else:
                                                echo "<img src='/assets/img/vespa-512.png' style='width:40px;height:40px;'>";
                                            endif;
                                        ?>
                                    </td>
                                    <td> <?php echo $motoris->name_motorist ?> </td>
                                    <td> <?php echo $motoris->car_board ?></td>
                                    <td class="table-actions text-right">
                                        <a class="table-action hover-info" data-provide="tooltip" title="Editar dados" data-tooltip-color="info"  href="<?php echo base_url('motoristas/editar/' . $motoris->id); ?>"><i class="ti-pencil"></i></a>
                                        <a class="table-action hover-danger" data-provide="tooltip" title="Excluir Dados" data-tooltip-color="danger" href="javascript:;" onclick="postData('<?php echo base_url('motoristas/delete/'.$motoris->id); ?>')"><i class="ti-close"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php  else: echo "Nenhum registro foi encontrado!"; endif;?>
                </div>
                <div id="card-loading"></div>
            </div>
            </div>
            

    </div>
</div>  
<?php $this->load->view('footer.php'); ?>
