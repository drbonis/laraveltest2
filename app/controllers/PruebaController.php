<?php

class PruebaController extends BaseController {
  public function prueba(){
    $nombre = "Mi variable";
    $mensaje = "Esto es una prueba";
    return View::make('prueba')->with('variable',$nombre)->with('mensaje',$mensaje);
  }
  public function about(){
    $nombre = "Mi about";
    $mensaje = "Esta es la pantalla de about";
    return View::make('prueba')->with('variable', $nombre)->with('mensaje',$mensaje);
  }
}

?>
