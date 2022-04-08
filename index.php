<?php
    session_start();
    ob_start( 'ob_gzhandler' );

    $_URL = "https://" . $_SERVER['HTTP_HOST'];

    if($_GET['invite'] != "" && ($_COOKIE['player'] != md5( "1" . $_GET['invite']) or $_GET['2play'] == "true")){
        setcookie("player", md5("2" . $_GET['invite']));
        if($_GET['2play'] == true){setcookie("2p", 1);}
        setcookie("PHPSESSID", $_GET['invite']);
        header('location: play.php');
        exit("Transferindo");
    }

    setcookie("player", md5("1" . session_id()));
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
        <title>TicTacToe</title>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <div class="header">
            <div class="title">Tic-Tac-Toe</div>
            <div class="description">Jogo da velha multiplayer online</div>
        </div>
        <div class="main">
            <div class="urlinvite">
                <div class="urldesc">Envie esse link para seu amigo e comece a jogar </div><br />
                <input class="url" id="URL" value="<?php echo $_SESSION['invite_link']; ?>"/> 
                <div class="copy" id="copyURL">Copiar url</div>
            </div>
            <div class="urlinvite">
                <div class="urldesc">Ou se preferir, pode jogar o modo de 2 players em um único computador. </div>
                <a href="<?php echo $_SESSION['invite_link']; ?>&2play=true" class="copy">Jogar</a></div>
            </div>
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
