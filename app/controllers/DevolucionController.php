<?php
// require_once './models/Venta.php';
// require_once './interfaces/IApiUsable.php';
include_once "./clases/Pizza.php";

// use \App\Models\Venta as Venta;

// class PizzaController implements IApiUsable
class DevolucionController
{
  public function ConsultarPizza($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    
    if ( isset($parametros["sabor"]) && isset($parametros["tipo"]) )
    {
        $sabor = $parametros["sabor"];
        $tipo = $parametros["tipo"];

        $vector = Pizza::TraerArray();

        $existe = Pizza::VerificarExistencia($sabor, $tipo, $vector);

        if ($existe == -1)
          $payload = json_encode(array("mensaje" => "Existe el sabor, pero no de ese tipo."));

        else if ($existe == -2)
          $payload = json_encode(array("mensaje" => "No existe ese sabor."));

        else
          $payload = json_encode(array("mensaje" => "Si Hay"));
    }
    else
      $payload = json_encode(array("mensaje" => "Dato/s inválido/s."));
    
    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }
  
	// public function TraerUno($request, $response, $args)
  // {}
	// public function TraerTodos($request, $response, $args)
  // {}

	public function CargarUno($request, $response, $args)
  {    
    $parametros = $request->getParsedBody();

    $sabor = $parametros['sabor'];
    $precio = $parametros['precio'];
    $tipo = $parametros['tipo'];
    $cantidad = $parametros['cantidad'];
    $archivos = $request->getUploadedFiles();

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
            $nombreAnterior = $archivos['imagen']->getClientFilename();
            $extension = explode(".", $nombreAnterior);
            $extension = array_reverse($extension)[0];

            $nombreImagen = $tipo;
            $nombreImagen .= "+". $sabor;
            $nombreImagen .= "." . $extension;

            $destino = "./ImagenesDePizzas/" . $nombreImagen;

            $archivos['imagen']->moveTo($destino);

            $vector[] = new Pizza($sabor, $precio, $tipo, $cantidad, $nombreImagen); // nombre y no path completo
        }

        if (Pizza::ActualizarJSON($vector))
        {
            if ($nuevo)
              $payload = json_encode(array("mensaje" => "Ingresada!"));

            else
              $payload = json_encode(array("mensaje" => "Actualizada"));
        }
        else
        {
          $payload = json_encode(array("mensaje" => "No se pudo hacer"));
        }

        $response->getBody()->write($payload);
    
        return $response->withHeader('Content-Type', 'application/json');
  }

	// public function BorrarUno($request, $response, $args)
  // {}
	// public function ModificarUno($request, $response, $args)
  // {}
}
