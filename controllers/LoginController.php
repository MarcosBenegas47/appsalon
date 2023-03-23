<?php
namespace Controllers;
use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController{
    public static function login(Router $router){
        $router->render('auth/login',[]);
    }
    public static function logout(){
        echo "desde logout";
    }
    public static function olvide(Router $router){
        $router->render('auth/olvide-password',[]);
    }
    public static function recuperar(){
        echo "desde recuperar";
    }
    public static function crear(Router $router){
        $usuario = new Usuario($_POST);
        //alertas vacias
        $alertas = [];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            //revisar que alertas este vacio
            if(empty($alertas)){
                //verificar que el usuario no este verificado
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //hashear password
                    $usuario->hashPassword();
                    //Generar un token unico
                    $usuario->crearToken();

                    //enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    debuguear($usuario);
                }
            }
        }
        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' =>$alertas
        ]);
    }
    public static function confirmar(){
        echo "desde confimar";
    }
}