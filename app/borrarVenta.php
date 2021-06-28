<?php

include_once "./clases/Pizza.php";
include_once "./clases/Venta.php";

if (isset($_POST["mail"]) &&
     isset($_POST["sabor"]) &&
      isset($_POST["tipo"]) &&
       isset($_POST["cantidad"]) &&
        isset($_FILES["imagen"]) )
{
    if (file_exists($_FILES["imagen"]["tmp_name"]))
    {
        if (@getimagesize($_FILES["imagen"]["tmp_name"]))
        {
            $mail = $_POST["mail"];
            $sabor = $_POST["sabor"];
            $tipo = $_POST["tipo"];
            $cantidad = $_POST["cantidad"];
            $imagen = $_FILES["imagen"];
            
            $pizza = Pizza::VerificarStock($sabor, $tipo, $cantidad);

            if ( $pizza == NULL )
                echo "No existe / no hay stock.";
            
            else
                Venta::Alta($mail, $cantidad, $pizza, $imagen);
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