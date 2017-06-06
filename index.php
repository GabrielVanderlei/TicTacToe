<?php
    session_start();
    ob_start( 'ob_gzhandler' );
    flush();

    $_URL = "http://localhost:64083";

    if($_GET['invite'] != "" && $_COOKIE['player'] != md5( 1 + $_GET['invite'])){

        setcookie("player", md5(2 + $_GET['invite']));
        setcookie("PHPSESSID", $_GET['invite']);
        header('location: play.php');
        exit();
    }

    setcookie("player", md5(1 + session_id()));
    $_SESSION['invite_link'] = $_URL . "/?invite=" .  session_id();
    
    // x = linha horizontal e y = linha vertical [xy]
    $_SESSION['data'] = array(
        11 => 0,12 => 0,13 => 0,
        21 => 0,22 => 0,23 => 0,
        31 => 0,32 => 0,33 => 0,
        "P1" => 1, "P2" => 0, "TUR" => 1,
        "PT1" => time(), "PT2" => 0,
        "W1" => 0, "W2" => 0, "W3" => 0, "WT" => 0,
        "chat" => "",
        "CON" => "Esperando conexão...."
    );

    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>TicTacToe-P</title>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <div class="title">TicTacToe-P</div><br />
        <div class="description">O clássico jogo da velha, dessa vez online e multiplayer!</div><br />
        <div class="qrcode"><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data= <?php echo $_SESSION['invite_link']; ?> " alt=""/></div>
        <div class="urlinvite">
            <div class="urldesc">Envie esse link a algum amigo para começar a jogar </div><br />
            <textarea class="url" id="URL"> <?php echo $_SESSION['invite_link']; ?> </textarea>
            <div class="copy" id="copyURL">Copiar url</div>
        </div>

        <script>
            var xhp = new XMLHttpRequest();

            function verify() {
                xhp.onreadystatechange = function () {

                    if (xhp.readyState == 4 && xhp.status == 200) {
                        rest = JSON.parse(xhp.responseText);
                        if (rest["P2"] == 1) { window.location = "play.php"; };
                    }
                };
            }

            document.getElementById('copyURL').addEventListener('click', function (event) {
                document.getElementById('URL').select();

                try {
                    document.execCommand('copy');
                } catch (err) {
                    alert('Não foi possivel copiar o link, tente usar Crtl+C.');
                }
            });

            function load(position) {
                var insp = "";
                if (position) { insp = "?position=" + position; }
                xhp.open("GET", "interaction.php" + insp, true);
                xhp.send();
            }

            setInterval(load, 1000);
            verify();
        </script>

    </body>
</html>
