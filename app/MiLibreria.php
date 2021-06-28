<?php

function ListarVector($array)
{
    $tabla = "
        <style>
            table, td, th{
                font-size: 1vw;
                border: 1px solid black;
                border-collapse: collapse;
                padding: 1.2vw;
                text-align: center;
            }
        </style>

        <table>
            <thead>
                <tr>";

    $claves = array_keys($array[0]);

    foreach ($claves as $clave)
    {
        $tabla .= "<th>$clave</th>";
    }

    $tabla .= "
                </tr>
            </thead>
            <tbody>";

    foreach ($array as $index)
    {
        $tabla .= "<tr>";

        foreach ($index as $valor)
            $tabla .= "<td>" . $valor . "</td>";

        $tabla .= "</tr>";
    }

    $tabla .= "
            </tbody>
        </table>";

    return $tabla;
}

// Funciona sólo si el array trae una clave "imagen"
function ListarVectorConFoto($arrayVentas, $imageFolder)
{
    $tabla = "
        <style>
            table, td, th{
                font-size: 1vw;
                border: 1px solid black;
                border-collapse: collapse;
                padding: 1.2vw;
                text-align: center;
            }

            img{
                width: 10vw;
                border: none;
            }
        </style>

        <table>
            <thead>
                <tr>";

    $claves = array_keys($arrayVentas[0]);

    foreach ($claves as $clave)
    {
        $tabla .= "<th>$clave</th>";
    }

    $tabla .= "
                </tr>
            </thead>
            <tbody>";

    foreach ($arrayVentas as $index)
    {
        $image = $index["imagen"];

        $tabla .= "<tr>";

        foreach ($index as $valor)
        {
            if ($valor == $image)
                $tabla .= "<td style='padding:0'><img src='$imageFolder/$valor' alt='Sin imagen'></td>";

            else
                $tabla .= "<td>" . $valor . "</td>";
        }

        $tabla .= "</tr>";
    }

    $tabla .= "
            </tbody>
        </table>";

    return $tabla;
}