<?php
//essa parte é quem garante a segurança do sistema, e define o que pode ou não ser visto,
//atenção às alterações aqui, copie e cole em TODAS as páginas
session_start();
ini_set('error_reporting', E_ALL); // mesmo resultado de: error_reporting(E_ALL);
ini_set('display_errors', 1);
    if(isset($_SESSION['usuarioId'])&&isset($_SESSION['usuarioNome'])&&isset($_SESSION['usuarioNivelAcesso'])&&
    isset($_SESSION['usuarioLogin'])){
        //ta autenticado, só deixa continuar brother
    }else{
        header("Location:login.php?logado=semacesso");
        echo('<span>não autenticado</span>');
    }
?>
<!-- fim security place -->

<!-- pegando os dados que vou precisar -->
<?php
    include_once("php/connect.php"); //conexão 
    //retangulo 1 ========================================================================================================================================($totalGeraldeProdutosMês)
    
    //retangulo 2 ========================================================================================================================================
    $query_date = date('Y-m-d');
    // First day of the month.
    $primeiroDiaMes = date('Y-m-01', strtotime($query_date));
    // Last day of the month.
    $ultimoDiaMes = date('Y-m-t', strtotime($query_date));
    //echo($primeiroDiaMes.'//'.$ultimoDiaMes);
    $pegaIdQuery01 = "SELECT * FROM `Esteira` WHERE `setorAtual`!='16' AND `setorAtual`!='9'";
    $resultado_pegaId01 = mysqli_query($conn, $pegaIdQuery01);
    $totalLinhas01 = mysqli_num_rows($resultado_pegaId01);
    
    
    //retangulo 3 ========================================================================================================================================
    $query_date03 = date('Y-m-d');
    // First day of the month.
    $primeiroDiaMes03 = date('Y-m-01', strtotime($query_date03));
    // Last day of the month.
    $ultimoDiaMes03 = date('Y-m-t', strtotime($query_date03));
    
    $pegaIdQuery03 = "SELECT Quantidades FROM `Esteira` WHERE (`setorAtual`='16' OR `setorAtual`='9') AND`dataEntrega` between '$primeiroDiaMes' AND '$ultimoDiaMes'";
    $resultado_pegaId03 = mysqli_query($conn, $pegaIdQuery03);
    $totalLinhas03 = mysqli_num_rows($resultado_pegaId03);
    
    //retangulo 4 ========================================================================================================================================
    $totalGeraldeProdutosMês04 = 0;
        $queryP21 = "SELECT Quantidades FROM `Esteira` WHERE (`setorAtual`='16' || `setorAtual`='9') AND `dataEntrega` between '$primeiroDiaMes' AND '$ultimoDiaMes'"; //primeiro faz a query de cada string (filtrada anteriormente)
        //echo($queryP21);
        $resultado_pegaId21 = mysqli_query($conn, $queryP21);
        while ($row21 = mysqli_fetch_assoc($resultado_pegaId21)){
            $totalProdutosAtual04 = 0;
            $dadosu04 = json_decode($row21['Quantidades']);
            //print_r($dadosu);
                foreach ($dadosu04 as &$linhas04) {
                     //print_r($linhas2->quantidade);
                     $totalProdutosAtual04 += intval($linhas04->quantidade);
                     //echo('<br>');
                }
        $totalGeraldeProdutosMês04 += $totalProdutosAtual04;//apenas aproveitei o loop pra já pegas todos que estão em produção        
        }
    
    
    //gráfico 1===============================================================================================================================================================================================
    $grafico1 = array();
    $setores = [10,20,15,2,3,25,11,12,13,14,23,5,6,7,8,9];
    
    foreach ($setores as &$value) {
        $pegaIdQuery = "SELECT * FROM `Esteira` WHERE `setorAtual`='$value'";
        $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
        $totalLinhas = mysqli_num_rows($resultado_pegaId);
        array_push($grafico1,$totalLinhas);
    }
    
    
    //gáfico de aplicações =====================================================================================================================================================================================
   
    $dadoApp = array();
    //pegando por quantidade vinilpers
    $dadoAppQtdPecas = array();
    $totalGeraldeProdutosApp = 0;//cria a variável que recebe o total de cada setor
    $pegaIdQueryApp = "SELECT Quantidades,pedidoJson FROM `Esteira` WHERE `pedidoJson` LIKE '%Aplicação vinilpers%' AND `setorAtual` != '16' AND `setorAtual` != '9'";//aqui não foi usado data pois que o total em prod e se ta no setor ta em prod
    $resultado_pegaApp = mysqli_query($conn, $pegaIdQueryApp); //faz a query sql
        while ($rowApp = mysqli_fetch_assoc($resultado_pegaApp)){ //percorre a query sql feita
            $totalProdutosAtualApp = 0;  //cria a variável q vai receber o total do setor
            $dadosAppQtd = json_decode($rowApp['Quantidades']); //desencode do json recebido no loop de js pra php
            //print("<span style='color:white'>".mb_strtoupper($rowApp['pedidoJson'])."</span>");
            
            $totalRepeticoesSbtr = substr_count(mb_strtoupper($rowApp['pedidoJson']),'APLICAÇÃO VINILPERS');
            
                foreach ($dadosAppQtd as &$linhasAppQtd) { //percorre cada quantidade do json recebido e faz a soma
                     $totalProdutosAtualApp += intval($linhasAppQtd->quantidade);
                }
        //echo("substring:".$totalRepeticoesSbtr." total produtos atual: ".$totalProdutosAtualApp."<br>");        
        $totalGeraldeProdutosApp += $totalProdutosAtualApp*$totalRepeticoesSbtr;//apenas aproveitei o loop pra já pegas todos que estão em produção 
        }

        array_push($dadoAppQtdPecas,$totalGeraldeProdutosApp);
        
     //============================================================================================   
    // echo('=====================bordado=======================');
    $totalGeraldeProdutosApp = 0;//cria a variável que recebe o total de cada setor
    $pegaIdQueryApp = "SELECT Quantidades,pedidoJson FROM `Esteira` WHERE `pedidoJson` LIKE '%Aplicação bordado%' AND `setorAtual` != '16' AND `setorAtual` != '9'";//aqui não foi usado data pois que o total em prod e se ta no setor ta em prod
    $resultado_pegaApp = mysqli_query($conn, $pegaIdQueryApp); //faz a query sql
        while ($rowApp = mysqli_fetch_assoc($resultado_pegaApp)){ //percorre a query sql feita
            $totalProdutosAtualApp = 0;  //cria a variável q vai receber o total do setor
            $dadosAppQtd = json_decode($rowApp['Quantidades']); //desencode do json recebido no loop de js pra php
            //print("<span style='color:white'>".mb_strtoupper($rowApp['pedidoJson'])."</span>");
            
            $totalRepeticoesSbtr = substr_count(mb_strtoupper($rowApp['pedidoJson']),'APLICAÇÃO BORDADO');
            
                foreach ($dadosAppQtd as &$linhasAppQtd) { //percorre cada quantidade do json recebido e faz a soma
                     $totalProdutosAtualApp += intval($linhasAppQtd->quantidade);
                }
        //echo("substring:".$totalRepeticoesSbtr." total produtos atual: ".$totalProdutosAtualApp."<br><br>");        
        $totalGeraldeProdutosApp += $totalProdutosAtualApp*$totalRepeticoesSbtr;//apenas aproveitei o loop pra já pegas todos que estão em produção 
        }

        array_push($dadoAppQtdPecas,$totalGeraldeProdutosApp);
        
    //============================================================================================   
    //aplicação dtf=====================================================================================
    $totalGeraldeProdutosApp = 0;//cria a variável que recebe o total de cada setor
    $pegaIdQueryApp = "SELECT Quantidades,pedidoJson FROM `Esteira` WHERE `pedidoJson` LIKE '%Aplicação DTF%' AND `setorAtual` != '16' AND `setorAtual` != '9'";//aqui não foi usado data pois que o total em prod e se ta no setor ta em prod
    $resultado_pegaApp = mysqli_query($conn, $pegaIdQueryApp); //faz a query sql
        while ($rowApp = mysqli_fetch_assoc($resultado_pegaApp)){ //percorre a query sql feita
            $totalProdutosAtualApp = 0;  //cria a variável q vai receber o total do setor
            $dadosAppQtd = json_decode($rowApp['Quantidades']); //desencode do json recebido no loop de js pra php
            //print("<span style='color:white'>".mb_strtoupper($rowApp['pedidoJson'])."</span>");
            
            $totalRepeticoesSbtr = substr_count(mb_strtoupper($rowApp['pedidoJson']),'APLICAÇÃO DTF');
            
                foreach ($dadosAppQtd as &$linhasAppQtd) { //percorre cada quantidade do json recebido e faz a soma
                     $totalProdutosAtualApp += intval($linhasAppQtd->quantidade);
                }
        //echo("substring:".$totalRepeticoesSbtr." total produtos atual: ".$totalProdutosAtualApp."<br><br>");        
        $totalGeraldeProdutosApp += $totalProdutosAtualApp*$totalRepeticoesSbtr;//apenas aproveitei o loop pra já pegas todos que estão em produção 
        }

        array_push($dadoAppQtdPecas,$totalGeraldeProdutosApp);   
        //print_r($dadoAppQtdPecas);

    //gráfico 5===============================================================================================================================================================================================
    $grafico05 = array(); //nesse gráfico ele vai pegar a quantidade de peças por setor (usei 05 por que foi o último gráfico feito mas ele na view é o 2°)
    $setores05 = [10,20,15,2,3,25,11,12,13,14,23,5,6,7,8,9]; //id de todos os setores
    
    foreach ($setores05 as &$value05) {//faz o loop pelo id de cada setor
        $totalGeraldeProdutosMês05 = 0;//cria a variável que recebe o total de cada setor
        $pegaIdQuery05 = "SELECT Quantidades FROM `Esteira` WHERE `setorAtual`='$value05'";//aqui não foi usado data pois que o total em prod e se ta no setor ta em prod
        $resultado_pegaId05 = mysqli_query($conn, $pegaIdQuery05); //faz a query sql
        while ($row05 = mysqli_fetch_assoc($resultado_pegaId05)){ //percorre a query sql feita
            $totalProdutosAtual05 = 0;  //cria a variável q vai receber o total do setor
            $dadosu05 = json_decode($row05['Quantidades']); //desencode do json recebido no loop de js pra php
            
                foreach ($dadosu05 as &$linhas05) { //percorre cada quantidade do json recebido e faz a soma
                     $totalProdutosAtual05 += intval($linhas05->quantidade);
                }
        $totalGeraldeProdutosMês05 += $totalProdutosAtual05;//apenas aproveitei o loop pra já pegas todos que estão em produção 
        }
        array_push($grafico05,$totalGeraldeProdutosMês05);
    }
    //gráfico 4 (op's por dia) ===========================================================================================================================
    $dataAtual = date('Y-m-d'); //pegando a data de hoje
    $diasParaBuscar8 = array($dataAtual); //já inserindo o dia atual
    
    for ($i = 1; $i <= 25; $i++){
       $dataAtualSomada = date('Y-m-d', strtotime($dataAtual . " + ".$i." day"));
       array_push($diasParaBuscar8,$dataAtualSomada); //capturando os dias futuros para buscar depois
    }
    $dadosGrafico8 = array();
    foreach ($diasParaBuscar8 as &$subString8){ 
         $totalProdutosAtual8 = 0;
         $queryP8 = "SELECT Quantidades FROM `Esteira` WHERE `dataEntrega`='$subString8'"; //primeiro faz a query de cada string (filtrada anteriormente)
         //echo($queryP21);
         $resultado_pegaId8 = mysqli_query($conn, $queryP8);
         while ($row8 = mysqli_fetch_assoc($resultado_pegaId8)){ 
            $dadosu8 = json_decode($row8['Quantidades']);
            //print_r($dadosu);
                foreach ($dadosu8 as &$linhas8) {
                     //print_r($linhas2->quantidade);
                     $totalProdutosAtual8 += intval($linhas8->quantidade);
                     //echo('<br>');
                }
         }
         array_push($dadosGrafico8,$totalProdutosAtual8);
    }  
    
    
    //gráfico 3 (quantidade de produtos) ============================================================================================================================================================
     $produtosGrafico2 = array();
     $pegaIdQuery2 = "SELECT DISTINCT `nomeProduto` FROM `Esteira` WHERE `setorAtual`!='16' AND `setorAtual`!='9'";
     $resultado_pegaId2 = mysqli_query($conn, $pegaIdQuery2);
     
     while ($row2 = mysqli_fetch_assoc($resultado_pegaId2)){ 
        array_push($produtosGrafico2,mb_strtoupper((explode(' ',str_replace('S ', '', $row2['nomeProduto']))[0])));
     } 
    $produtosGrafico2 = array_values(array_unique($produtosGrafico2));//peguei o nome dos produtos, e agora vou ver todas as quantidades =========================================================
    $dadosGrafico2 = array();
    $totalGeraldeProdutosMês = 0; //apenas aproveitando o loop para pegar o dado do primeiro quadrado
    foreach ($produtosGrafico2 as &$subString){ 
         $totalProdutosAtual = 0;
         $queryP21 = "SELECT Quantidades FROM `Esteira` WHERE `setorAtual`!='16' AND `setorAtual`!='9' AND (upper(`nomeProduto`) LIKE '$subString%')"; //primeiro faz a query de cada string (filtrada anteriormente)
         //echo($queryP21);
         $resultado_pegaId21 = mysqli_query($conn, $queryP21);
         while ($row21 = mysqli_fetch_assoc($resultado_pegaId21)){ 
            $dadosu = json_decode($row21['Quantidades']);
            //print_r($dadosu);
                foreach ($dadosu as &$linhas2) {
                     //print_r($linhas2->quantidade);
                     $totalProdutosAtual += intval($linhas2->quantidade);
                     //echo('<br>');
                }
         }
         $totalGeraldeProdutosMês +=$totalProdutosAtual;//apenas aproveitei o loop pra já pegas todos que estão em produção
         array_push($dadosGrafico2,$totalProdutosAtual);
    }  
    
    
    //Último gráfico, o de quantidade de produtos em produção por cliente ==============================================================================================================================
    $produtosGrafico7 = array();
    $pegaIdQuery7 = "SELECT DISTINCT `cliente` FROM `Esteira` WHERE `setorAtual`!='16' AND `setorAtual`!='9'";
    $resultado_pegaId7 = mysqli_query($conn, $pegaIdQuery7);
     
    while ($row7 = mysqli_fetch_assoc($resultado_pegaId7)){ 
        array_push($produtosGrafico7,mb_strtoupper($row7['cliente']));
    } 
    $produtosGrafico7 = array_values(array_unique($produtosGrafico7));//peguei o nome dos produtos, e agora vou ver todas as quantidades ====================================================
    $dadosGrafico7 = array();
    $totalGeraldeProdutosMês7 = 0; //apenas aproveitando o loop para pegar o dado do primeiro quadrado
    foreach ($produtosGrafico7 as &$subString7){ 
         $totalProdutosAtual7 = 0;
         $queryP7 = "SELECT Quantidades FROM `Esteira` WHERE `setorAtual`!='16' AND `setorAtual`!='9' AND (upper(`cliente`) LIKE '$subString7%')"; //primeiro faz a query de cada string (filtrada anteriormente)
         //echo($queryP21);
         $resultado_pegaId7 = mysqli_query($conn, $queryP7);
         while ($row7 = mysqli_fetch_assoc($resultado_pegaId7)){ 
            $dadosu7 = json_decode($row7['Quantidades']);
            //print_r($dadosu);
                foreach ($dadosu7 as &$linhas7) {
                     //print_r($linhas2->quantidade);
                     $totalProdutosAtual7 += intval($linhas7->quantidade);
                     //echo('<br>');
                }
         }
         $totalGeraldeProdutosMês7 +=$totalProdutosAtual7;//apenas aproveitei o loop pra já pegas todos que estão em produção
         array_push($dadosGrafico7,$totalProdutosAtual7);
    }
    
    //gráfico último atrasados(9)=============================================================================================================================================================================
    $dataAtual = date('Y-m-d'); //pegando a data de hoje
    $primeiroDia = date('Y-m-d', strtotime($dataAtual . " - ".'10'." day"));
    $diasParaBuscar9 = array($primeiroDia); //já inserindo o dia atual
    $qtdTotalFinal9 = array();
    
    for ($i = 1; $i <= 10; $i++){
       $dataAtualSomada9 = date('Y-m-d', strtotime($primeiroDia . " + ".$i." day"));
       array_push($diasParaBuscar9,$dataAtualSomada9); //capturando os dias passados para buscar depois
    }
    
    foreach ($diasParaBuscar9 as &$linhas9) {
        $pegaIdQuery09 = "SELECT * FROM `Esteira` WHERE `dataEntrega`='$linhas9' AND `setorAtual`!='16' AND `setorAtual`!='9'";
        $resultado_pegaId09 = mysqli_query($conn, $pegaIdQuery09);
        $totalLinhas09 = mysqli_num_rows($resultado_pegaId09);
        array_push($qtdTotalFinal9,$totalLinhas09);
    }    
    
    

?>
<!doctype html>
<html lang="en">
<head>
<link rel="icon" href="img/icon.png">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Personal Produção</title>
<link rel="icon" href="img/favicon.png">
<!-- meu css --> 
<link rel="stylesheet" href="css/style.css">
<!-- vuejs e jquery para controle ajax e js -->  
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script>
  <script src="js/jquery.js"></script>
<!-- api que gera pdf -->
  <script src="js/xepOnline.jqPlugin.js"></script>
<!-- bootstrap e css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<!-- font awersome -->
<script src="https://kit.fontawesome.com/704200b128.js" crossorigin="anonymous"></script>
</head>
<body>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!- chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- menu -->
<?php include 'menu.php';?>

<div class="" style="padding: 3px 0px 3px 0px;background-image: url('img/wall1.jpg');background-position: center top;background-size: 100% auto;">
<div class='row'>
    <div class='col-sm-3' style='color:white;text-align:center;padding:8px'>
        <div style='background-color:#00000085;border-radius:10px'>
            PEÇAS EM PRODUÇÃO <br>
            <span style='color:#00FF00;font-size:70px;font-weight:bold'><?php echo(array_sum($grafico05));?></span>
        </div>
    </div>
    <div class='col-sm-3' style='color:white;text-align:center;padding:8px'>
        <div style='background-color:#00000085;border-radius:10px'>
            PEDIDOS EM PRODUÇÃO <br>
            <span style='color:#00FF00;font-size:70px;font-weight:bold'><?php echo($totalLinhas01); ?></span>
        </div>
    </div>
    <div class='col-sm-3' style='color:white;text-align:center;padding:8px'>
        <div style='background-color:#00000085;border-radius:10px'>
            TOTAL DE PEDIDOS DO MÊS <br>
            <span style='color:#00FF00;font-size:70px;font-weight:bold'><?php echo($totalLinhas03);?></span>
        </div>
    </div>
    <div class='col-sm-3' style='color:white;text-align:center;padding:8px'>
        <div style='background-color:#00000085;border-radius:10px'>
            PEÇAS FABRICADAS NO MÊS <br>
            <span style='color:#00FF00;font-size:70px;font-weight:bold'><?php echo($totalGeraldeProdutosMês04);?></span>
        </div>
    </div>
    <!-- gráfico 1 -->
    <div class='col-sm-4'>
        <div class="child" >
            <canvas id="pieChart" style="background:#00000085;padding:15px;border-radius:12px" width="100%" height="100%"></canvas>
        </div>
    </div>
    <!-- gráfico de aplicações -->    
    <div class='col-sm-4'>
        <canvas id="doughnut-chart-aplicacoes" width="150" height="150" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
    </div>
    <!-- gráfico 2-->    
    <div class='col-sm-4'>
        <canvas id="doughnut-chart" height='350' style="background:#00000085;padding:15px;border-radius:12px"></canvas>
    </div>

    <div class='col-sm-4' style="padding:15px;border-radius:12px;">
        <div class="child2" style='background-color:#0000002e;border-radius:12px'>
            <canvas id="pie-chart" width="900" height="550" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
        </div>
    </div>
    
    <!-- gráfico de produtos atrasados -->
    <div class='col-sm-4' style="padding:15px; border-radius:12px;">
        <canvas id="grafico-atrasados" width="600" height="350" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
    </div>
    
    <div class='col-sm-4' style="padding:15px;border-radius:12px;">
        <div class="child3" style='background-color:#0000002e;border-radius:12px'>
            <canvas id="line-chart" width="600" height="550" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
        </div>
    </div>
<!-- gráfico de qtd de produto por cliente -->
    <div class='col-sm-12'>
        <div class="child4" style='background-color:#0000002e;border-radius:12px;margin-left:-155px'>
            <canvas id="curve-chart" width="600" height="550" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
        </div>
    </div>
    
</div>   
</div>
</div></div></div>
<!-- fim teste -->
<!-- footer -->
<div style="height: 3px; background-image: linear-gradient(to right, #FF0009, #EB1E13, #C41910, #FF0009); margin-top: 0px; width: 100%;"></div>
<footer class="bg-body-tertiary text-center text-lg-start">
  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: black">
    © 2024 Copyright PersonalConfeccoes
  </div>
  <!-- Copyright -->
</footer>

<!-- script de click no gráfico 1 -->
<script>
var dadosGrafico1 = <?php echo json_encode($grafico1); ?>;
//criando as posições chaveadas
let setores = new Object();
//Populate data
setores["Fase inicial"] = 10;
setores["Produção"] = 20;
setores["Modelagem"] = 15;
setores["Sublimação"] = 2;
setores["Corte malharia"] = 3;
setores["Corte tecido"] = 25;
setores["Estamparia"] = 11;
setores["Serigrafia"] = 12;
setores["Bordado"] = 13;
setores["Vinilpers"] = 14;
setores["DTF"] = 23;
setores["Atelier"] = 5;
setores["Passadeiras"] = 6;
setores["Revisão"] = 7;
setores["Embalagem"] = 8;
setores["Concluído"] = 9;



var canvasP = document.getElementById("pieChart");
var ctxP = canvasP.getContext('2d');
  new Chart(ctxP, {
    type: 'bar',
    data: {
      labels: ['Fase inicial','Produção','Modelagem','Sublimação', 'Corte malharia','Corte tecido','Estamparia','Serigrafia','Bordado','Vinilpers','DTF','Atelier', 'Passadeiras','Revisão','Embalagem','Concluído'],
      datasets: [{
        label: '#Ordens de produção',
        data: dadosGrafico1,
        borderColor: '#1fd4f3',
        backgroundColor: ["#0093FF", "#0B00E2","#005AFF","#00B7FF","#00DCFF","#0093FF","#00B7FF","#005AFF","#00B7FF","#005AFF"],
        borderWidth: 1
      }]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      onClick: (event, elements, chart) => {
          if (elements[0]){
             const i = elements[0].index;
             //alert(chart.data.labels[i] + ': ' + chart.data.datasets[0].data[i]); 
             window.location.href = 'movimentacao.php?setorAtual='+setores[chart.data.labels[i]];
          }
      },
      scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
    }
  });
</script>

<!-- gráfico de aplicações -->
<script>
var dadosGraficoApp = <?php echo json_encode($dadoAppQtdPecas); ?>;
console.log(dadosGraficoApp);
new Chart(document.getElementById("doughnut-chart-aplicacoes"),{
    type: 'doughnut',
    data: {
      labels: ["Vinilpers", "Bordado","DTF"],
      datasets: [
        {
          label: "Aplicações (por unidade)",
          backgroundColor:  ["#0093FF", "#0B00E2","#005AFF","#00B7FF","#00DCFF","#0093FF","#00B7FF","#005AFF","#00B7FF","#005AFF"],
          data: dadosGraficoApp
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      title: {
        display: true,
        text: 'Tipos de Aplicações (por peça)'
      },
      scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
    }
});
</script>

<!-- gráfico 2 -->
<script>
    var dadosGrafico05 = <?php echo json_encode($grafico05);?>;
    //console.log(dadosGrafico05);
    new Chart(document.getElementById("doughnut-chart"),{
    type: 'bar',
    data: {
        labels: ['Fase inicial','Produção','Modelagem','Sublimação','Corte malharia','Corte tecido','Estamparia','Serigrafia','Bordado','Vinilpers','DTF','Atelier', 'Passadeiras','Revisão','Embalagem','Concluído'],
        datasets: [{
          label: 'Peças(unidades)',
          backgroundColor: ["#0093FF", "#0B00E2","#005AFF","#00B7FF","#00DCFF","#0093FF","#00B7FF","#FF6166","#00B7FF","#005AFF"],
          data: dadosGrafico05,
        }],
      },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        indexAxis: 'y', //<-- here
        responsive: true,
        scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
      }
    });
</script>
<!-- gráfico 3 -->
<script>
var Grafico2Prd = <?php echo json_encode($produtosGrafico2); ?>;
var Grafico2Qtd = <?php echo json_encode($dadosGrafico2); ?>;

console.log(Grafico2Qtd);
console.log(Grafico2Prd);
new Chart(document.getElementById("pie-chart"),{
    type: 'pie',
    data: {
      labels: Grafico2Prd,
      datasets: [{
        label: "Em produção (unidades)",
        backgroundColor: ["#FF69B4", "#00FF00","#7FFFD4","#00FFFF","#00BFFF","#000080","#808080","#DC143C","#FF8C00","#FFFF00","#87CEEB","#00BFFF","#F0F8FF","#F0E68C"],
        data: Grafico2Qtd
      }]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      title: {
        display: true,
        text: 'QUANTIDADE POR PRODUTO'
      },
      scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
    }
});
</script>




<!-- gráfico 4 -->
<script>
  var diasGrafico3 = <?php echo json_encode($diasParaBuscar8); ?>;
  var dadosGrafico3 = <?php echo json_encode($dadosGrafico8); ?>;
  
  new Chart(document.getElementById("line-chart"), {
  type: 'line',
  data: {
    labels: diasGrafico3,
    datasets: [{ 
        data: dadosGrafico3,
        label: "Qtd. de itens para entregar",
        borderColor: "#00FF00",
        fill: false
      }
    ]
  },
  options: {
    maintainAspectRatio: false,
    responsive: true,
    onClick: (event, elements, chart) => {
          if (elements[0]){
             const i = elements[0].index;
             //alert(chart.data.labels[i] + ': ' + chart.data.datasets[0].data[i]); 
             window.location.href = 'movimentacao.php?dataEntrega='+chart.data.labels[i];
          }
    },
    title: {
      display: true,
      text: 'World population per region (in millions)'
    },
    scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
  }
});
</script>

<!-- gráfico de qtd de produtos por cliente --> 
<script>
  var diasGrafico7 = <?php echo json_encode($produtosGrafico7); ?>;
  var dadosGrafico7 = <?php echo json_encode($dadosGrafico7); ?>;
 
  new Chart(document.getElementById("curve-chart"), {
  type: 'line',
  data: {
    labels: diasGrafico7,
    datasets: [{ 
        data: dadosGrafico7,
        label: "Qtd. de itens por cliente",
        borderColor: "#00FFFF",
        fill: false
      }
    ]
  },
  options: {
    maintainAspectRatio: false,
    responsive: true,
    onClick: (event, elements, chart) => {
          if (elements[0]){
             const i = elements[0].index;
             //alert(chart.data.labels[i] + ': ' + chart.data.datasets[0].data[i]); 
             window.location.href = 'movimentacao.php?nome='+chart.data.labels[i];
          }
    },
    title: {
      display: true,
      text: 'World population per region (in millions)'
    },
    scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    },
    elements: {
        line: {
            tension: 0.25
        }
    }
  }
});
</script>

<!-- atrasados -->
<script>
  var dadosGrafico9 = <?php echo json_encode($diasParaBuscar9); ?>;
  var dadosQtd9 = <?php echo json_encode($qtdTotalFinal9); ?>;
  
    
  new Chart(document.getElementById("grafico-atrasados"), {
  type: 'line',
  data: {
    labels: dadosGrafico9,
    datasets: [{ 
        data: dadosQtd9,
        label: "Qtd. de op's não concluídas",
        borderColor: "#00FF00",
        fill: false
      }
    ]
  },
  options: {
    maintainAspectRatio: false,
    responsive: true,
    onClick: (event, elements, chart) => {
          if (elements[0]){
             const i = elements[0].index;
             //alert(chart.data.labels[i] + ': ' + chart.data.datasets[0].data[i]); 
             window.location.href = 'movimentacao.php?dataEntrega='+chart.data.labels[i];
          }
    },
    title: {
      display: true,
      text: 'World population per region (in millions)'
    },
    scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
  }
});
</script>


</body>
</html>

<style type="text/css">
  @font-face {
  font-family: "Helvetica-bold";
  src: url("font/Helvetica-Bold-Font.ttf");
  }

  body{
    background-color: #18191a; /* <!-- #606060 --> */
    /*background: linear-gradient(-40deg, black 18%,#18191a 48%);
    font-family: 'Helvetica-bold';

  }
  /* Modify the background color */
         
        .navbar-custom {
            background-color: black;
        }
  /* Modify brand and text color */
         
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-text {
            color: White;
        }

  input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
  .child {
  height: 100%;
  }
</style>