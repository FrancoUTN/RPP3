<?php

class Pizza
{
    public $sabor;
    public $precio;
    public $tipo;
    public $cantidad;
    public $imagen;

    const RUTAJSON = "./archivos/Pizza.json";
    const RUTAID = "./archivos/ultimoIDpizza.txt";

    function __construct($sabor, $precio, $tipo, $cantidad, $imagen)
    {
        $this->sabor = $sabor;        
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->imagen = $imagen;

        $this->id = Pizza::RetornarUltimoID();
    }
    
    public static function VerificarStock($sabor, $tipo, $cantidad)
    {
        $vector = Pizza::TraerArray();

        $index = Pizza::VerificarExistencia($sabor, $tipo, $vector);

        if ($index >= 0)
        {
            if ($vector[$index]->cantidad >= $cantidad)
            {
                $vector[$index]->cantidad -= $cantidad;

                Pizza::ActualizarJSON($vector);

                return $vector[$index];
            }
        }

        return NULL;
    }

    public static function VerificarExistencia($sabor, $tipo, $array)
    {
        $existeElSabor = FALSE;

        for ($i = 0; $i < sizeof($array); $i++)
        {
            if ($array[$i]->sabor == $sabor)
            {
                $existeElSabor = TRUE;

                if ($array[$i]->tipo == $tipo)
                    return $i;
            }
        }

        if ($existeElSabor)
            return -1;

        else
            return -2;
    }

    public static function Alta($sabor, $precio, $tipo, $cantidad, $imagen)
    {
        $nuevo = TRUE;

        if ($vector = Pizza::TraerArray())
        {
            $index = Pizza::VerificarExistencia($sabor, $tipo, $vector);

            if ($index >= 0)
            {
                $vector[$index]->precio = $precio;
                $vector[$index]->cantidad += $cantidad;

                $nuevo = FALSE;
            }
        }

        if ($nuevo)
        {            
            $nombreImagen = $tipo;
            $nombreImagen .= "+". $sabor;
            $nombreImagen .= "." . pathinfo($imagen["name"], PATHINFO_EXTENSION);

            $destino = "./ImagenesDePizzas/" . $nombreImagen;

            if (move_uploaded_file($imagen["tmp_name"], $destino))
                echo "Imagen subida.";

            else
                echo "Error al intentar subir la imagen.";

            $vector[] = new Pizza($sabor, $precio, $tipo, $cantidad, $nombreImagen);
        }

        if (Pizza::ActualizarJSON($vector))
        {
            if ($nuevo)
                return "Ingresado";

            return "Actualizado";
        }

        return "No se pudo hacer";
    }

    public static function RetornarUltimoID()
    {
        $ruta = self::RUTAID;

        if (file_exists($ruta))
        {
            $ar = fopen($ruta, "r");

            $id = fread($ar, filesize($ruta));

            $id += 1;

            fclose($ar);

            $ar = fopen($ruta, "w");

            fwrite($ar, $id);
        }
        else
        {
            $ar = fopen($ruta, "w");

            $id = 1;

            fwrite($ar, $id);
        }

        fclose($ar);

        return $id;
    }

    public static function ActualizarJSON($array)
    {
        $vectorJSONeado = json_encode($array);

		$ar = fopen(self::RUTAJSON, "w");

        $cant = fwrite($ar, $vectorJSONeado);

        fclose($ar);

		if($cant > 0)
			return TRUE;

        return FALSE;
    }
    
    public static function TraerArray()
    {
        if (file_exists(self::RUTAJSON))
        {
            $ar = fopen(self::RUTAJSON, "r");
    
            $cadena = fread($ar, filesize(self::RUTAJSON));
    
            fclose($ar);

            return json_decode($cadena);
        }

        return FALSE;
    }
}