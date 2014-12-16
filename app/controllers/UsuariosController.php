<?php
class UsuariosController extends BaseController {
  public function mostrarUsuarios()
  {
    $usuarios = Usuario::all();
    if (Auth::validate($credentials))
      {
          return View::make('usuarios/lista', array('usuarios'=> $usuarios));
      }
      else {
        return View::make('usuarios/lista', array('usuarios'=> array()));
      }

  }
}
?>
