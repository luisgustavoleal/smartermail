<?php 

require 'smartermail.php';

############################################################################
# Autenticacao Smartermail
############################################################################

$_HTTPs         = 's';

# Verificar o formato da URI e conta para utilizar a API, existe recursos que 
# é necessário possuir conta ADM no dominio em questão como no exemplo abaixo
# /api/v1/settings/domain/export-users = funcao listmail()
#$_dominio_email = 'host.pt';
#$_dominio_user  = 'user@host.pt';

#$_APIHost       = 'mail9.host.net';
$_APIHost       = 'mail.host.pt';
#$_dominio_email = 'caixiave.es';
#$_dominio_user  = 'user@host.pt';
$_dominio_user  = 'user';
$_dominio_pass  = 'pass';

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
#$_lista = listemails();
#print_r($_lista);
echo "\n\n ===============================";


############################################################################
# Atualizar Language da conta de email
############################################################################
#$_user = PrimaryAuth();

echo "\n\n ===============================";
echo "\n\n Atualizar Language da conta de email ";
echo "\n\n ";

#$_conta = UpdateEmail( 'sys-api@caixiave.es' );
#print_r($_conta);
echo "\n\n ===============================";


############################################################################
# Atualizar Language da conta de email
############################################################################
#$_user = PrimaryAuth();

echo "\n\n ===============================";
echo "\n\n Lista de IPs bloqueados no Servidor ";
echo "\n\n ";

$_listIP = listIPBlocked();
print_r($_listIP);
echo "\n\n ===============================";


?>
