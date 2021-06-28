<?php

class Cupon
{
    public $pedido;
    public $causa;
    public $cupon;
    public $imagen;

    const RUTAJSON = "./archivos/devoluciones.json";
    // const RUTAID = "./archivos/ultimoIDDevolucion.txt";

    function __construct($pedido, $causa, $cupon, $imagen)
    {
        $this->pedido = $pedido;
        $this->causa = $causa;
        $this->cupon = $cupon;
        $this->imagen = $imagen;

        // $this->id = Devolucion::RetornarUltimoID();
    }
    
    public static function Alta($pedido, $causa, $cupon, $nombreImagen)
    {
        $vector = self::TraerArray();

        $vector[] = new Devolucion($pedido, $causa, $cupon, $nombreImagen);

        self::ActualizarJSON($vector);
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