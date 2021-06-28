<?php
// require_once './models/Venta.php';
// require_once './interfaces/IApiUsable.php';
include_once "./clases/Pizza.php";

// use \App\Models\Venta as Venta;

// class PizzaController implements IApiUsable
class PizzaController
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

    // $imagen = array("name" => $archivos['imagen']->getClientFilename());
    $imagen = array(
      $archivos['imagen']->getClientFilename(),
      $archivos['imagen']->getStream(),
      $archivos['imagen']->getClientMediaType()
      // $archivos['imagen']->,
      // $archivos['imagen']->,
    );

    // $respuesta = Pizza::Alta($sabor, $precio, $tipo, $cantidad, $archivos["imagen"]);


    // $payload = json_encode(array("mensaje" => "$respuesta"));
    $payload = json_encode($imagen);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');    
  }

	// public function BorrarUno($request, $response, $args)
  // {}
	// public function ModificarUno($request, $response, $args)
  // {}
}
