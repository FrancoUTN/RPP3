<?php

function ListarVector($array)
{
    $tabla = Estilo();
    
    $tabla .= "
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
function ListarVectorConFoto($array)
{
    $tabla = Estilo();
    
    $tabla .= "
        <table>
            <thead>
                <tr>";

    $claves = array_keys($array[0]);
    
    $contador = 0;

    foreach ($claves as $clave)
    {
        $tabla .= "<th>$clave</th>";

        if ($clave == "imagen")
            $imageIndex = $contador;

        $contador++;
    }

    $tabla .= "
                </tr>
            </thead>
            <tbody>";

    foreach ($array as $index)
    {
        $tabla .= "<tr>";

        $contador = 0;

        foreach ($index as $valor)
        {
            if ($contador == $imageIndex)
            {
                // $tabla .= "<td style='padding:0'><img src='$valor'  alt='Sin imagen'></td>";
                $tabla .= "<td style='padding:0'><img src='.$valor'  alt='Sin imagen'></td>";
            }

            else
                $tabla .= "<td>" . $valor . "</td>";
            
            $contador++;
        }

        $tabla .= "</tr>";
    }

    $tabla .= "
            </tbody>
        </table>";

    return $tabla;
}

function ListarConFoto($array)
{
    $tabla = Estilo();

    $tabla .= "
        <table>
            <thead>
                <tr>";

    $claves = array_keys($array);
    
    $contador = 0;

    foreach ($claves as $clave)
    {
        $tabla .= "<th>$clave</th>";

        if ($clave == "imagen")
            $imageIndex = $contador;

        $contador++;
    }

    $tabla .= "
                </tr>
            </thead>
            <tbody>";

    $tabla .= "<tr>";

    $contador = 0;

    foreach ($array as $valor)
    {
        if ($contador == $imageIndex)
            $tabla .= "<td style='padding:0'><img src='.$valor'  alt='Sin imagen'></td>";

        else
            $tabla .= "<td>" . $valor . "</td>";

        $contador++;
    }

    $tabla .= "</tr>";

    $tabla .= "
            </tbody>
        </table>";

    return $tabla;
}

function Estilo()
{
    return "
        <style>
            table, td, th{
                font-size: 1vw;
                border: 1px solid black;
                border-collapse: collapse;
                padding: 1.2vw;
                text-align: center;
            }

            img{
                width: 6vw;
                border: none;
            }
        </style>";
}