<?php
require_once './models/Venta.php';
require_once './interfaces/IApiUsable.php';
include_once "./clases/Pizza.php";

use \App\Models\Venta as Venta;

class VentaController implements IApiUsable
{

  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $sabor = $parametros['sabor'];
    $tipo = $parametros['tipo'];
    $cantidad = $parametros['cantidad'];

    $pizza = Pizza::VerificarStock($sabor, $tipo, $cantidad);

    if ($pizza == NULL)
    {
        $payload = json_encode(array("mensaje" => "No existe o no hay stock."));
    }
    else
    {
        $mail = $parametros['mail'];
            
        $archivos = $request->getUploadedFiles();
    
        $destino = "./ImagenesDeLaVenta/";
    
        $nombreAnterior = $archivos['imagen']->getClientFilename();
        $extension = explode(".", $nombreAnterior);
        $extension = array_reverse($extension)[0];    
        
        $usuario = explode("@", $mail)[0];

        $fecha = date("Y-m-d");

        $pathFoto = $destino . $tipo . "+" . $sabor . "+" . $usuario . "+" . $fecha . "." . $extension;
    
        $archivos['imagen']->moveTo($pathFoto);
    
    
        $venta = new Venta();
        $venta->sabor = $sabor;
        $venta->tipo = $tipo;
        $venta->cantidad = $cantidad;
        $venta->precio = $pizza->precio;
        $venta->mail = $parametros['mail'];
        $venta->pedido = rand(0, 999);
        $venta->fecha = date("Y-m-d");        
        $venta->imagen = $pathFoto;
        $venta->save();    
    
        $payload = json_encode(array("mensaje" => "Venta creada con exito"));    
    }

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $id = $args['id'];

    $venta = Venta::find($id);

    $payload = json_encode($venta);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerEntreFechas($request, $response, $args)
  {
    $fecha1 = $args["fecha1"];
    $fecha2 = $args["fecha2"];

    $lista = Venta::where('fecha', '>', $fecha1)->where('fecha', '<', $fecha2)->orderBy("sabor")->get();

    $payload = json_encode($lista);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerPorUsuario($request, $response, $args)
  {
    $usuario = $args["usuario"];

    $lista = Venta::all();

    $array = array();

    foreach ($lista as $venta)
    {
      $mail = $venta->mail;

      $mailHastaArroba = explode("@", $mail)[0];

      if ($usuario == $mailHastaArroba)
      {
        array_push($array, $venta);
      }
    }

    $payload = json_encode($array);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerPorSabor($request, $response, $args)
  {
    $sabor = $args["sabor"];

    $lista = Venta::where('sabor', '=', $sabor)->get();

    $payload = json_encode($lista);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Venta::all();

    $payload = json_encode($lista);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $pedido = $args['pedido'];

    $venta = Venta::where('pedido', '=', $pedido)->first();

    if ($venta !== null)
    {
      $mail = $parametros['mail'];
      $sabor = $parametros['sabor'];
      $tipo = $parametros['tipo'];
      $cantidad = $parametros['cantidad'];

      $venta->mail = $mail;
      $venta->sabor = $sabor;
      $venta->tipo = $tipo;
      $venta->cantidad = $cantidad;

      $venta->save();

      $payload = json_encode(array("mensaje" => "Venta modificada con exito!"));
    }
    else
    {
      $payload = json_encode(array("mensaje" => "Venta no encontrada."));
    }

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $pedido = $args['pedido'];

    $venta = Venta::where('pedido', '=', $pedido)->first();

    $venta->delete();

    $foto = $venta->imagen;

    if ($foto != NULL)
    {
      $oldname = $foto;

      $explotado = explode("/", $oldname);

      $revertido = array_reverse($explotado);

      $newname = "./BACKUPVENTAS/" . $revertido[0];

      rename($oldname, $newname);
    }

    $payload = json_encode(array("mensaje" => "Venta borrada con exito"));

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }
}
