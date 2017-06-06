<?php
    session_start();
    ob_start( 'ob_gzhandler' );
    flush();
    if(!$_COOKIE['player']){ exit("Opa! Algo errado por aqui."); }

    //Verify
    if($_COOKIE['player'] == md5(1 + session_id())){$player = 1;}
    if($_COOKIE['player'] == md5(2 + session_id())){ $player = 2;}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>TicTacToe-P</title>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <script>
            var you = <?php echo $player;?>
        </script>
        <script>
            var xhp = new XMLHttpRequest();
            xs = 0;
            r = 0;

            function read(j) {
                ix = 1;
                iy = 1;

                for (i = 1; i <= 9; i++) {
                    if (document.getElementById(ix + "" + iy).getAttribute("data-o") != 1) {

                        if (rest[ix + "" + iy] == 1) {
                            document.getElementById(ix + "" + iy).innerHTML += "<div id='c1' class='circle'></div>";
                            setTimeout(function () {
                                document.getElementById('c1').style.transform = "scale(0.6)";
                                document.getElementById('c1').setAttribute("id", ".");
                            }, 400);

                            document.getElementById(ix + "" + iy).style.border = "2px solid rgba(0, 100, 200, 0.6)";
                            document.getElementById(ix + "" + iy).setAttribute("data-o", "1");
                        }

                        if (rest[ix + "" + iy] == 2) {
                            document.getElementById(ix + "" + iy).innerHTML += "<div id='x1' class='x1'></div><div id='x2' class='x2'></div>";
                            setTimeout(function () {
                                document.getElementById('x1').style.transform = "scale(0.6) rotate(45deg)";
                                document.getElementById('x2').style.transform = "scale(0.6) rotate(-45deg)";
                                document.getElementById('x1').setAttribute("id", ".");
                                document.getElementById('x2').setAttribute("id", ".");
                            }, 400);

                            document.getElementById(ix + "" + iy).setAttribute("data-o", "1");
                            document.getElementById(ix + "" + iy).style.border = "2px solid rgba(100, 200, 100, 0.6)";
                        }
                    }

                    else {
                        if (rest[ix + "" + iy] == 0) {
                            document.getElementById(ix + "" + iy).innerHTML = "";
                            document.getElementById(ix + "" + iy).style.border = "2px solid black";
                            document.getElementById(ix + "" + iy).setAttribute("data-o", 0)
                        }
                    }

                    iy++;
                    if (i % 3 == 0) {
                        ix++;
                        iy = 1;
                    };
                }
            }

            function verify() {
                xhp.onreadystatechange = function () {

                    if (xhp.readyState == 4 && xhp.status == 200) {
                        rest = JSON.parse(xhp.responseText);
                        document.getElementById("log").innerHTML = xhp.responseText;
                        read(rest); r = 1;

                        document.getElementById("TUR").innerHTML = "Vez do player " + rest["TUR"];
                        document.getElementById("P").innerHTML = "Você é o player " + you;
                        document.getElementById("p1").innerHTML = rest['W1'];
                        document.getElementById("p2").innerHTML = rest['W2'];
                        document.getElementById("ch").innerHTML = rest['chat'];
                        document.getElementById("CON").innerHTML = rest["CON"];

                        if (rest["WT"] >= 1) {
                            document.getElementById("next").style.opacity = 1;
                        }

                        else {
                            document.getElementById("next").style.opacity = 0;
                        }

                        if (rest["TUR"] != you) {
                            ix = 1;
                            iy = 1;

                            for (i = 1; i <= 9; i++) {
                                document.getElementById(ix + "" + iy).className = "option disabled";
                                iy++;
                                if (i % 3 == 0) {
                                    ix++;
                                    iy = 1;
                                };
                            }
                        }

                        else {
                            ix = 1;
                            iy = 1;

                            for (i = 1; i <= 9; i++) {
                                document.getElementById(ix + "" + iy).className = "option";
                                iy++;
                                if (i % 3 == 0) {
                                    ix++;
                                    iy = 1;
                                };
                            }
                        }
                    }

                }
            };


            function load(position) {
                var insp = "";
                if (position) { insp = "?position=" + position; }
                xhp.open("GET", "interaction.php" + insp, true);
                xhp.send();
            }

            setInterval(load, 1000);
            verify();

            function makeTab() {

                var h = (window.innerHeight) / 3,
                    w = ((window.innerWidth * 0.7) - 15) / 3,
                    c = document.getElementById("content"),
                    x = 0, ix = 1,
                    y = 0, iy = 1;

                for (i = 1; i <= 9; i++) {

                    c.innerHTML += ["<div class='option' id='" + ix + "" + iy + "' style='width:" + w + "px;height:" + h + "px;" +
                                    "top:" + y + "px;left:" + x + "px' onclick='edit(this.id)'></div>"];
                    iy++;
                    x = x + w;

                    if (i % 3 == 0) {
                        y = y + h;
                        x = 0;
                        ix++;
                        iy = 1;
                    };
                }
            }

            function chat(val) {
                xhp.open("GET", "interaction.php?chat=" + val, true);
                xhp.send();
            }

            function end(val) {
                xhp.open("GET", "interaction.php?end=true", true);
                xhp.send();

                window.location = "/";
            }

            function edit(id) {
                if (document.getElementById(id).getAttribute("data-o") != 1) {
                    if (rest["TUR"] == you) {
                        load(id);
                        xs = xs + 20;
                        h = (window.innerHeight) / 3,
                        w = ((window.innerWidth * 0.7) - 15) / 3;

                        if (you == 1) {
                            document.getElementById(id).innerHTML += "<div id='c1' class='circle'></div>";

                            setTimeout(function () {
                                document.getElementById('c1').style.transform = "scale(0.6)";
                                document.getElementById('c1').setAttribute("id", ".");
                            }, 400);

                            document.getElementById(id).style.border = "2px solid rgba(0, 100, 200, 0.6)";
                        }

                        if (you == 2) {
                            document.getElementById(id).innerHTML += "<div id='x1' class='x1'></div><div id='x2' class='x2'></div>";
                            setTimeout(function () {
                                document.getElementById('x1').style.transform = "scale(0.6) rotate(45deg)";
                                document.getElementById('x2').style.transform = "scale(0.6) rotate(-45deg)";
                                document.getElementById('x1').setAttribute("id", ".");
                                document.getElementById('x2').setAttribute("id", ".");
                            }, 400);

                            document.getElementById(id).style.border = "2px solid rgba(100, 200, 100, 0.6)";
                        }
                    }
                }
            }

        </script>
        <div id="log"></div>
        <div class="content" id="content">
        </div>
        <div class="status">
            <div class="turn" id="TUR">Carregando...</div>
            <div class="player" id="P">Carregando...</div>
            <div class="con" id="CON">Carregando...</div>
        </div>
        <div class="chat">
            <div class="ct">Chat</div>
            <div class="ch" id="ch"></div>
        </div>
        <input type="text" id="chatInp" placeholder="Fale com seu amigo... (Aperte enter pra enviar)" />
        <div class="next" id="next" onclick="load('RES')">Próximo</div>
        <div class="next" style="opacity: 1;display: block;" onclick="end()">Finalizar</div>
        <div class="placar">
            <div class="p1" id="p1">0</div><div class='vs'>VS</div><div class="p2" id="p2">0</div>
        </div>
        <script>document.getElementById("chatInp").onkeyup = function(e){if(e.which == 13){chat(this.value);this.value="";}};makeTab();</script>
    </body>
</html>
