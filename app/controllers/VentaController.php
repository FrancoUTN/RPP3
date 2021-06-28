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
        $extension = array_reverse($extension);    
        
        $usuario = explode("@", $mail)[0];

        $pathFoto = $destino . $tipo . "+" . $sabor . "+" . $usuario . "." . $extension[0];
    
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
    
        $response->getBody()->write($payload);
    }

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos usuario por nombre
    $id = $args['id'];

    // Buscamos por primary key
    $venta = Venta::find($id);

    // Buscamos por attr usuario
    // $usuario = Usuario::where('usuario', $usr)->first();

    $payload = json_encode($venta);

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

  public function TraerTipo($request, $response, $args)
  {
    $tipo = $args["tipo"];

    $lista = Venta::where('tipo', '=', $tipo)->get();

    $payload = json_encode($lista);

    $response->getBody()->write($payload);
    // $response->getBody()->write($tipo);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usrModificado = $parametros['usuario'];
    $usuarioId = $args['id'];

    // Conseguimos el objeto
    $usr = Usuario::where('id', '=', $usuarioId)->first();

    // Si existe
    if ($usr !== null) {
      // Seteamos un nuevo usuario
      $usr->usuario = $usrModificado;
      // Guardamos en base de datos
      $usr->save();
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];

    // Buscamos
    $venta = Venta::find($id);

    // Borramos
    $venta->delete();

    $payload = json_encode(array("mensaje" => "Venta borrada con exito"));

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }
}
