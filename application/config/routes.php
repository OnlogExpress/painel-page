<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'autenticacao';
$route['404_override'] = '404';
$route['translate_uri_dashes'] = FALSE;


$route['lote']['GET'] = "api/v1/Lote";
$route['objetos']['GET'] = "api/v1/ConsultaSVP";
$route['operacao-em-conferencia'] = 'api/v1/Conferencias';
$route['conferi-operacao'] = 'api/v1/Conferencias';

$route['autenticacao'] = 'login/autenticacao';
$route['portal-cliente'] = 'portalCliente/index';
$route['portal-cliente/all'] = 'portalCliente/index';
$route['portal-agf'] = 'portalAgf/index';
$route['portal-agf/all'] = 'portalAgf/index';
$route['aplicar/(:any)'] = 'portalCliente/operacoes/$1';
$route['mudarStatus']['POST'] = 'portalCliente/mudaStatusOperacao';


$route['logar'] = 'api/v1/LoginController';
$route['motoristas'] = 'portalCliente/motoristas';
$route['motoristas/adicionar'] = 'portalCliente/motoristasAdd';
$route['motoristas/editar/(:any)'] = 'portalCliente/motoristasEdit/$1';
$route['motoristas/delete/(:any)'] = 'portalCliente/deleteMotorista/$1';

$route['baixa/pendencias/(:any)']['POST'] = 'pendencias/baixa/$1';
$route['finalizar/lote/(:any)']['POST'] = 'pendencias/finalizaLote/$1';


$route['conferencia-web'] = 'ConferenciaWeb/index';
$route['pesquisa'] = 'ConferenciaWeb/pesquisa';
$route['coletor/web'] = 'ConferenciaWeb/coletorWeb';
$route['coletor/web/(:any)'] = 'ConferenciaWeb/coletoWebLoteAberto/$1';
$route['fechalote/(:any)'] = 'ConferenciaWeb/fechaLote/$1';
$route['todas-operacoes'] = 'api/v1/Operacoes';


$route['portal-agf/teste'] = 'portalAgf/teste';
$route['carregar/dados/lote/(:num)'] = 'portalAgf/carregaDadoLote/$1';

//POSTAL
$route['portal'] = 'PortalWeb/index';
$route['lista/objetos'] = 'PortalWeb/listaDados';
$route['soapTeste'] = 'PortalWeb/soapSet';




