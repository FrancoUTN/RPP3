<?php

class Cupon
{
    public $id;
    public $descuento;
    public $estado;

    const RUTAJSON = "./archivos/cupones.json";

    function __construct($id, $descuento, $estado)
    {
        $this->id = $id;
        $this->descuento = $descuento;
        $this->estado = $estado;
    }
    
    public static function Alta($id, $descuento, $estado)
    {
        $vector = self::TraerArray();

        $vector[] = new Cupon($id, $descuento, $estado);

        self::ActualizarJSON($vector);
    }

    public static function VerificarCupon($idCupon)
    {
        $vector = self::TraerArray();

        foreach ($vector as $cupon)
        {
            if ($cupon->id == $idCupon)
            {
                if ($cupon->estado == "usado")
                {
                    return NULL;
                }

                $cupon->estado = "usado";

                self::ActualizarJSON($vector);

                return $cupon->descuento;
            }
        }

        return NULL;
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