<?php 

require 'smartermail.php';

############################################################################
# Autenticacao Smartermail
############################################################################

$_HTTPs         = 's';
$_APIHost      = 'mail.caixiave.pt';
$_dominio_email = 'caixiave.es';
$_dominio_user  = 'sys-api@caixiave.es';
$_dominio_pass  = 'xEj.GkU.p8H.3dh.2sB';
$_uri           = 'http' . $_HTTPs . '://' . $_APIHost . '/api/v1/auth/authenticate-user';


$_API = [
    "authUserName"  => $_dominio_user,
    "authPassword"  => $_dominio_pass,
    "Method"        => 'POST',
	"ContentType"   => 'application/json',
    "URI"           => 'http' . $_HTTPs . '://' . $_APIHost . '/',
];


############################################################################
# Pega informação do dominio do Email
############################################################################

$_dominio = 'caixiave.es';
$get_data = callAPI('GET', 'https://mail.caixiave.pt/api/v1/companyinfo/caixiave.es'.$_dominio, false);
$response = json_decode($get_data, true);
print_r($response);
//$errors = $response['response']['errors'];
//$data = $response['response']['data'][0];


############################################################################
# Lista contas de email do dominio
############################################################################
#$_user = PrimaryAuth();

echo "\n\n ===============================";
echo "\n\n Listar E-mails VARIAVEL ";
echo "\n\n ";
$_lista = listemails();
print_r($_lista);



?>