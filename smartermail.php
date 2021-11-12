<?php
/**
 * 
 *  Script: Funcoes para Integraçao PHP x SmarterMail
 *                  
 *  Autor : Luis Gustavo Leal
 *          Cloudbyte.pt
 *  E-mail: gustavoleal@cloudbyte.pt
 *  Data  : 03/11/2021
 * 
 */


############################################################################
# Funçao Autenticacao Primaria
#############################################################################

function PrimaryAuth()  {
  
    global $_API;
    global $_uri;
    global $_HTTPs;
    global $_APIHost;

    # Cria o array
    $_APIUpdate = [
        'Uri'  => 'http' . $_HTTPs . '://' . $_APIHost . '/api/v1/auth/authenticate-user',
        'Body' => array(
                        "username" => ''. $_API['authUserName'] . '',
                        "password" => ''. $_API['authPassword'] . '',
                        "language" => null,
                        "twoFactorCode" => "",
                       )
    ];

    # Cria um Json só com o Body do array
    $_API_json = json_encode($_APIUpdate['Body']);
    #print_r($_API_json);
    #echo "\n\n Json: " . $_API_json;
    #echo "\n\n Uri: " . $_APIUpdate['Uri'];

    # Chama a API
    $get_data = callAPI('POST', $_APIUpdate['Uri'], $_API_json, false);
    $Auth = json_decode($get_data, true);
    #print_r($Auth); 
    #echo "\n\n".' Access Token: ' . $Auth['accessToken'];
   
    return $Auth;

};

############################################################################
# Lista contas de email do dominio
#
# ATENÇÃO: 
#   É necessario criar a conta sys-api no dominio com direito administrativo
#
############################################################################

function listemails() {
     
    #global $_user;
    $_user = PrimaryAuth();

    global $_API;
    global $_uri;
    global $_HTTPs;
    global $_APIHost;

    # Cria o array
    $_APIUpdate = array(
            'Uri'    => 'http' . $_HTTPs . '://' . $_APIHost . '/api/v1/settings/domain/export-users',
            'Method' => 'POST',
            'Body'   => array(
                            "username" => ''. $_API['authUserName'] . '',
                            "password" => ''. $_API['authPassword'] . '',
                            "language" => null,
                            "twoFactorCode" => "",
                            )
    );

    # Cria um Json só com o Body do array
    $_API_json = json_encode($_APIUpdate['Body']);
    echo "\n\n Json: " . $_API_json;
    echo "\n\n Uri: " . $_APIUpdate['Uri'];
    echo "\n\n ";

    # SERVER ADMIN AUTH TOCKEN
    $_API["Headers"] = array( 'Authorization' => "Bearer ".$_user['accessToken']  );
    $_headers_value  = $_API['Headers']['Authorization'];
    $_headers_header = $_API['Headers'];

    # REFRESH
    $_API["Refresh"] = array( 'Refresh' => "token ".$_user['refreshToken']  );

    # EXPERATION
    $_API["accessTokenExpiration"] = array( 'DataTime' => $_user['accessTokenExpiration']  );

    # Chama a API
    $get_data = callAPI($_APIUpdate['Method'], $_APIUpdate['Uri'], $_API_json, true );
    $list = json_decode($get_data, true);

    #print_r($get_data); 

    return $get_data;
  
}



############################################################################
#
# Update conta de Email (Language)
#
############################################################################

function UpdateEmail( $conta ) {
     
    global $_API;
    global $_uri;
    global $_HTTPs;
    global $_APIHost;

    #global $_user;
    $_user = PrimaryAuth();

    $localeId='pt';
    $dictionaryLanguage='pt';

    # Cria o array
    $_APIUpdate = array(
        'Uri'    => 'http' . $_HTTPs . '://' . $_APIHost . '/api/v1/settings/domain/user-mail/' . $conta,
        'Method' => 'POST',
        'Body'   => array(
                        "userMailSettings" => array(
                                                    "localeId" => ''. $localeId . '',
                                                    "dictionaryLanguage" => ''. $dictionaryLanguage . '',
                                                    ),
                        )
    );
    # PS = 'Body' = '{userMailSettings:{"localeId":"' + $localeId + '","dictionaryLanguage":"' + $dictionaryLanguage + '"}}'
    
    # Cria um Json só com o Body do array
    $_API_json = json_encode($_APIUpdate['Body']);
    echo "\n\n Json: " . $_API_json;
    echo "\n\n Uri: " . $_APIUpdate['Uri'];
    
    @$_localeId           = $_APIUpdate['Body']['userMailSettings']['localeId'];
    @$dictionaryLanguage  = $_APIUpdate['Body']['userMailSettings']['dictionaryLanguage'];
    echo "\n\n Locale: " . $_localeId . ' | Dictionary Language: ' .  $dictionaryLanguage;
    echo "\n\n ";
    #print_r($_API_json); 

    # SERVER ADMIN AUTH TOCKEN
    $_API["Headers"] = array( 'Authorization' => "Bearer ".$_user['accessToken']  );
    $_headers_value  = $_API['Headers']['Authorization'];
    $_headers_header = $_API['Headers'];

    # REFRESH
    $_API["Refresh"] = array( 'Refresh' => "token ".$_user['refreshToken']  );

    # EXPERATION
    $_API["accessTokenExpiration"] = array( 'DataTime' => $_user['accessTokenExpiration']  );

    # Chama a API
    $get_data = callAPI($_APIUpdate['Method'], $_APIUpdate['Uri'], $_API_json );
    $list = json_decode($get_data, true);

    #print_r($get_data); 

    return $get_data;
  
}


############################################################################
# Funcao para chamar a API
#
# O parametro $header se definido como TRUE habilita a alteracao do header
# pelo cabeçalho.
#
############################################################################


function callAPI($method, $url, $data, $header = null){

    $curl = curl_init();

    switch ($method){
       case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
       case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
          if ($data)
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
          break;
       default:
          if ($data)
             $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);

    if (!is_null($header)) {

        global $_API;
        @$_headers_value  = $_API['Headers']['Authorization'];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: ' .$_headers_value .'',
            'Content-Type: application/json',
        ));
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;

 }





/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://domainregister.international/domainsResellerAPI/api.php");
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSLVERSION, 3);
curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');
$result = curl_exec($ch);
$res    = json_decode($result, true);
print_r($res);
curl_close($ch);
*/

?>
