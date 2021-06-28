<?php

    include_once "./clases/Pizza.php";
    include_once "./clases/Venta.php";

    if (isset($_POST["pedido"]))
    {
        // Venta::VerificarPedido($_POST["pedido"]);
    }
    else
        echo "Dato/s inválido/s.";

?>