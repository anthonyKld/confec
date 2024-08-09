<?php
//esse script é usado para mover a op entre os setores, muita atenção aqui pois tem controle de visibilidade
include_once("../php/connect.php");
//pegando dados
$idOp = $_POST['idOp'];
$idMolde = $_POST['idMolde_'];
$novoSetor = $_POST['novoSetor'];

//dados de data e hora
date_default_timezone_set('America/Manaus'); //horário
session_start(); //pelo nome do cliente

//pegando a movimentação que já tem
$pegaIdQuery = "SELECT historico FROM `Esteira` WHERE `idGeral`='$idOp' AND `idMolde`='item_$idMolde'";
$resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
$FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
    
    $pessoa = mb_strtoupper($_SESSION['usuarioNome']);
    $setor1 = $novoSetor;
    
    $jsonRecebido = $FinalPedido['historico'];
    
    if($jsonRecebido==''){
        $jsonRecebido = '{"movimentacao":[],"abate":[]}';
    }
    
    $historico = json_decode($jsonRecebido,true);
    
    $envioData = strval(date('d/m/Y'));
    $envioHora = date('H:i:s');

    $historico['movimentacao'] = array_merge($historico['movimentacao'],array($pessoa." moveu para ".$setor1." em ".$envioData." às ".$envioHora));
    
    $final = json_encode($historico,JSON_UNESCAPED_UNICODE);
    //print_r(json_decode($final));
    
$queryAt = "UPDATE `Esteira` SET `setorAtual`='$novoSetor',`historico`='$final' WHERE idGeral='$idOp' AND idMolde='item_$idMolde'";
mysqli_query($conn, $queryAt);
echo("pedido #".$idOp."-".$idMolde."movido para: ".$novoSetor);
?>