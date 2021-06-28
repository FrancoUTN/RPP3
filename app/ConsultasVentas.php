<?php

include_once "./clases/Pizza.php";
include_once "./clases/Venta.php";
include_once "./MiLibreria.php";


if ( isset($_GET["consulta"]) )
{
    $consulta = $_GET["consulta"];

    if ($consulta = "cantidad")
        echo "<h1>Pizzas vendidas: " . Venta::CantidadPizzasVendidas() . "</h1>";
}


if ( isset($_GET["fecha1"]) && isset($_GET["fecha2"]) )
{
    $fecha1 = $_GET["fecha1"];
    $fecha2 = $_GET["fecha2"];

    echo "<h2>Ventas entre el $fecha1 y el $fecha2</h2>";

    $ventas = Venta::TraerVentasEntreFechas($fecha1, $fecha2);

    echo ListarVectorConFoto($ventas, "./ImagenesDeLaVenta");
}

if ( isset($_GET["usuario"]) )
{
    $usuario = $_GET["usuario"];
    
    echo "<h2>Ventas al usuario $usuario</h2>";

    $ventas = Venta::TraerVentasDeUnUsuario($usuario);

    echo ListarVectorConFoto($ventas, "./ImagenesDeLaVenta");
}

if ( isset($_GET["sabor"]) )
{
    $sabor = $_GET["sabor"];
    
    echo "<h2>Pizzas de $sabor vendidas</h2>";

    $ventas = Venta::TraerVentasDeUnSabor($_GET["sabor"]);

    echo ListarVectorConFoto($ventas, "./ImagenesDeLaVenta");
}