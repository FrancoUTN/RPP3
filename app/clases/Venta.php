<?php

include_once "./clases/AccesoDatos.php";

class Venta
{
    public static function CantidadPizzasVendidas()
    {
        $objetoAcceso = AccesoDatos::dameUnObjetoAcceso();

        $consulta = $objetoAcceso->RetornarConsulta("SELECT cantidad FROM venta");
        
        $consulta->execute();

        $arrayCantidades = $consulta->fetchAll();

        $acumulador = 0;

        foreach($arrayCantidades as $cantidad)
        {
            $acumulador += $cantidad[0];
        }

        return $acumulador;
    }

    public static function TraerVentasEntreFechas($fecha1, $fecha2)
    {
        $objetoAcceso = AccesoDatos::dameUnObjetoAcceso();
            
        $consulta = $objetoAcceso->RetornarConsulta
            ("SELECT * FROM venta WHERE fecha BETWEEN :fecha1 AND :fecha2 ORDER BY sabor");
        
        $consulta->bindValue(':fecha1', $fecha1, PDO::PARAM_STR);
        $consulta->bindValue(':fecha2', $fecha2, PDO::PARAM_STR);
        
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function TraerVentasDeUnUsuario($mail)
    {
        $objetoAcceso = AccesoDatos::dameUnObjetoAcceso();
            
        $consulta = $objetoAcceso->RetornarConsulta("SELECT * FROM venta WHERE mail = :mail");
        
        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
        
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function TraerVentasDeUnSabor($sabor)
    {
        $objetoAcceso = AccesoDatos::dameUnObjetoAcceso();
            
        $consulta = $objetoAcceso->RetornarConsulta("SELECT * FROM venta WHERE sabor = :sabor");
        
        $consulta->bindValue(':sabor', $sabor, PDO::PARAM_STR);
        
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function Alta($mail, $cantidad, $objetoPizza, $imagen)
    {
        $fecha = date("Y-m-d");

        $nombreImagen = $objetoPizza->tipo;
        $nombreImagen .= "+". $objetoPizza->sabor;
        $nombreImagen .= "+". explode("@", $mail)[0];
        $nombreImagen .= "+". $fecha;
        $nombreImagen .= "." . pathinfo($imagen["name"], PATHINFO_EXTENSION);

        $destino = "./ImagenesDeLaVenta/" . $nombreImagen;

        if (move_uploaded_file($imagen["tmp_name"], $destino))
            echo "Imagen subida.";

        else
            echo "Error al intentar subir la imagen.";

        $pedido = rand(0, 999);

        Venta::InsertarVentaParametros($objetoPizza, $mail, $cantidad, $nombreImagen, $fecha, $pedido);
    }

    public static function InsertarVentaParametros($objetoPizza, $mail, $cantidad, $imagen, $fecha, $pedido)
    {
       $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

       $consulta = $objetoAccesoDato->RetornarConsulta
           ("INSERT into venta (sabor, tipo, cantidad, precio, mail, pedido, fecha, imagen)
            values (:sabor, :tipo, :cantidad, :precio, :mail, :pedido, :fecha, :imagen)");
       
       $consulta->bindValue(':sabor', $objetoPizza->sabor, PDO::PARAM_STR);
       $consulta->bindValue(':tipo', $objetoPizza->tipo, PDO::PARAM_STR);
       $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
       $consulta->bindValue(':precio', $objetoPizza->precio, PDO::PARAM_INT);
       $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
       $consulta->bindValue(':pedido', $pedido, PDO::PARAM_INT);
       $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
       $consulta->bindValue(':imagen', $imagen, PDO::PARAM_STR);

       $consulta->execute();
    }
}