<?php

    include "./clases/Pizza.php";

    if ( isset($_POST["Sabor"]) && isset($_POST["Tipo"]) )
    {
        $sabor = $_POST["Sabor"];
        $tipo = $_POST["Tipo"];
        
        $vector = Pizza::TraerArray();

        $existe = Pizza::VerificarExistencia($sabor, $tipo, $vector);
        
        if ($existe == -1)
            echo "Existe el sabor, pero no de ese tipo.";

        else if ($existe == -2)
            echo "No existe ese sabor.";            

        else
            echo "Si Hay";
    }
    else
        echo "Dato/s inválido/s.";

?>