<?php
    require('../php/connect.php');
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
//--------------------------------//só pegando o token do data base
//obtem todas as categorias de produto
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/categorias/produtos?pagina=1&limite=100',
        CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer '.$token
        ),
        ));
        $response = curl_exec($curl);
        print_r($response);
        
        curl_close($curl);
?>