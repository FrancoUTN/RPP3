<?php

    include_once "./clases/Pizza.php";

    if ( isset($_POST["Sabor"]) && isset($_POST["precio"]) && isset($_POST["Tipo"]) && isset($_POST["cantidad"]) )
    {
        if (file_exists($_FILES["imagen"]["tmp_name"]))
        {
            if (@getimagesize($_FILES["imagen"]["tmp_name"]))
            {
                $sabor = $_POST["Sabor"];
                $precio = $_POST["precio"];
                $tipo = $_POST["Tipo"];
                $cantidad = $_POST["cantidad"];
                $imagen = $_FILES["imagen"];
                
                echo Pizza::Alta($sabor, $precio, $tipo, $cantidad, $imagen);
            }
            else
                echo "Imagen inválida.";
        }
        else
            echo "No existe el archivo.";
    }
    else
        echo "Dato/s inválido/s.";

?>