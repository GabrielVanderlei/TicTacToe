<?php
    session_start();
    ob_start( 'ob_gzhandler' );
    flush();

    if($_GET['end'] && $_GET['end'] == "true"){
        foreach($_COOKIE as $key=>$ck){
           setcookie($key, $ck, time()-3600); //seta o cookie com vencimento no passado, invalidando-o
        }    

        foreach($_SESSION as $key=>$ck){
          $_SESSION[$key] = ""; 
        }   
        session_unset();
        session_unregister();
        exit("Finalizado");
    }

    $i1 = 1;
    $i2 = 1;

    //Verify
    if($_COOKIE['player'] && $_COOKIE['player'] == md5(1 + session_id())){$player = 1;}
    if($_COOKIE['player'] && $_COOKIE['player'] == md5(2 + session_id())){ $player = 2;}
    if($_COOKIE['2p'] && $_COOKIE['2p'] == 1){ $_SESSION['data']['2P'] = 1; $player=$_SESSION['data']['TUR'];}

    $_SESSION['data']['PT'.$player] = time();
    
    $_SESSION['data']['CON'] = "Esperando jogada do player ". $_SESSION['data']['TUR'];
    if((time() - $_SESSION['data']['PT1']) > 10){ $_SESSION['data']['CON'] = "Jogador 1 offline....";$_SESSION['data']['P1'] = 0;}
    if((time() - $_SESSION['data']['PT2']) > 10){ $_SESSION['data']['CON'] = "Jogador 2 offline....";$_SESSION['data']['P2'] = 0;}


    if($player == $_SESSION['data']['TUR'] && $_GET['position']){
        if($_SESSION['data'][$_GET['position']] == 0){
            $_SESSION['data'][$_GET['position']] = $player;
        }
        if($player == 1){ $_SESSION['data']['TUR'] = 2; }
        else{ $_SESSION['data']['TUR'] = 1; }
    }

    if($_GET['chat']){
        $_SESSION['data']['chat'] = "Player " . $player . ": " . $_GET['chat'] . "<br />". $_SESSION['data']['chat'];
    }

    
    $_SESSION['data']['P'.$player] = 1; 

    //H1H1H1
    if($_SESSION['data'][11] == 1 && $_SESSION['data'][12] == 1 && $_SESSION['data'][13] == 1  ){$_SESSION['data']['CON'] = "Player 1 ganhou!";$_SESSION['data']['WT']=1;}
    if($_SESSION['data'][11] == 2 && $_SESSION['data'][12] == 2 && $_SESSION['data'][13] == 2  ){$_SESSION['data']['CON'] = "Player 2 ganhou!";$_SESSION['data']['WT']=2;}

    //H2H2H2
    if($_SESSION['data'][21] == 1 && $_SESSION['data'][22] == 1 && $_SESSION['data'][23] == 1  ){$_SESSION['data']['CON'] = "Player 1 ganhou!";$_SESSION['data']['WT']=1;}
    if($_SESSION['data'][21] == 2 && $_SESSION['data'][22] == 2 && $_SESSION['data'][23] == 2  ){$_SESSION['data']['CON'] = "Player 2 ganhou!";$_SESSION['data']['WT']=2;}

    //H3H3H3
    if($_SESSION['data'][31] == 1 && $_SESSION['data'][32] == 1 && $_SESSION['data'][33] == 1  ){$_SESSION['data']['CON'] = "Player 1 ganhou!";$_SESSION['data']['WT']=1;}
    if($_SESSION['data'][31] == 2 && $_SESSION['data'][32] == 2 && $_SESSION['data'][33] == 2  ){$_SESSION['data']['CON'] = "Player 2 ganhou!";$_SESSION['data']['WT']=2;}

    //H1|H1|H1
    if($_SESSION['data'][11] == 1 && $_SESSION['data'][21] == 1 && $_SESSION['data'][31] == 1  ){$_SESSION['data']['CON'] = "Player 1 ganhou!";$_SESSION['data']['WT']=1;}
    if($_SESSION['data'][11] == 2 && $_SESSION['data'][21] == 2 && $_SESSION['data'][31] == 2  ){$_SESSION['data']['CON'] = "Player 2 ganhou!";$_SESSION['data']['WT']=2;}

    //H2|H2|H2
    if($_SESSION['data'][12] == 1 && $_SESSION['data'][22] == 1 && $_SESSION['data'][32] == 1  ){$_SESSION['data']['CON'] = "Player 1 ganhou!";$_SESSION['data']['WT']=1;}
    if($_SESSION['data'][12] == 2 && $_SESSION['data'][22] == 2 && $_SESSION['data'][32] == 2  ){$_SESSION['data']['CON'] = "Player 2 ganhou!";$_SESSION['data']['WT']=2;}

    //H3|H3|H3
    if($_SESSION['data'][13] == 1 && $_SESSION['data'][23] == 1 && $_SESSION['data'][33] == 1  ){$_SESSION['data']['CON'] = "Player 1 ganhou!";$_SESSION['data']['WT']=1;}
    if($_SESSION['data'][13] == 2 && $_SESSION['data'][23] == 2 && $_SESSION['data'][33] == 2  ){$_SESSION['data']['CON'] = "Player 2 ganhou!";$_SESSION['data']['WT']=2;}

    //H1\H1\H1
    if($_SESSION['data'][11] == 1 && $_SESSION['data'][22] == 1 && $_SESSION['data'][33] == 1  ){$_SESSION['data']['CON'] = "Player 1 ganhou!";$_SESSION['data']['WT']=1;}
    if($_SESSION['data'][11] == 2 && $_SESSION['data'][22] == 2 && $_SESSION['data'][33] == 2  ){$_SESSION['data']['CON'] = "Player 2 ganhou!";$_SESSION['data']['WT']=2;}

    //H1/H1/H1
    if($_SESSION['data'][13] == 1 && $_SESSION['data'][22] == 1 && $_SESSION['data'][31] == 1  ){$_SESSION['data']['CON'] = "Player 1 ganhou!";$_SESSION['data']['WT']=1;}
    if($_SESSION['data'][13] == 2 && $_SESSION['data'][22] == 2 && $_SESSION['data'][31] == 2  ){$_SESSION['data']['CON'] = "Player 2 ganhou!";$_SESSION['data']['WT']=2;}
    $i = 0;


?>
{<?php
    $json = "";

    foreach ($_SESSION['data'] as $key => $value){
        $json .= '"' . $key . '":"' . $value . '" ,';
        if((is_numeric($key)) && (($value == 1) or ($value == 2))){
            $i++;
        }
    }

    echo substr($json, 0, -1);

    if($i >= 9){
        $_SESSION['data']['WT'] = 3;
        $_SESSION['data']['CON'] = "Empate!";
    }

    if($_SESSION['data']['WT']>=1 && $_GET['position'] == "RES"){
        // Reseta
        $_SESSION['data']['W'.$_SESSION['data']['WT']]++;

        $_SESSION['data'] = array(
            11 => 0,12 => 0,13 => 0,
            21 => 0,22 => 0,23 => 0,
            31 => 0,32 => 0,33 => 0,
            "P1" => 1, "P2" => 0, "TUR" => 1,
            "PT1" => time(), "PT2" => time(),
            "W1" => $_SESSION['data']['W1'], "W2" => $_SESSION['data']['W2'], "WT" => 0,
            "CON" => "Reiniciando jogo..."
        );
    }

    
?> }