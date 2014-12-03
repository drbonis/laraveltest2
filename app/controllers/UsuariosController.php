<?php
class UsuariosController extends BaseController {
  public function mostrarUsuarios()
  {
    $usuarios = Usuario::all();
    return View::make('usuarios/lista', array('usuarios'=> $usuarios));
  }
}
?>
