<?php
namespace Controllers;
use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginController{
    public static function login(Router $router){
        $auth = new Usuario($_POST);
        $alertas=[];
        

        if($_SERVER['REQUEST_METHOD']==='POST'){
            
            $alertas = $auth->validarLogin();
            if(empty($alertas)){
                //comprobar que el usuario exista
                $usuario =Usuario::where('email', $auth->email);
                if($usuario){
                    //verificar el usuario
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //autenticar el usuario
                        if(!isset($_SESSION)){
                            session_start();
                        }
                        $_SESSION['id']= $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre. " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        //redireccionamiento
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }


                    }
                }else{
                    Usuario::setAlerta('error','el usuario no existe');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login',[
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }
    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    public static function olvide(Router $router){
        $alertas =[];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)){
                $usuario = Usuario::where('email',$auth->email);
                if($usuario && $usuario->confirmado === "1"){
                    //generar un token 
                    $usuario->crearToken();
                    $usuario->guardar();
                    //enviar email
                    Usuario::setAlerta('exito', 'Se envio un link de recuperacion al Email');
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas(); 

        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false; 
        $token = s($_GET['token']);
        //buscar usuario por su token
        $usuario = Usuario::where('token',  $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error = true; 
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password; 
                $usuario->hashpassword(); 
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas'=> $alertas,
            'error' => $error
        ]);
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

                    //crear usuario 
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }
                    


                }
            }
        }
        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' =>$alertas
        ]);
    }
    public static function confirmar(Router $router){
        $alertas =[];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            //mostrar mensaje de error
            Usuario::setAlerta('error','token no valido');
        }else{
            //modificar a usuario confirmado
            $usuario->confirmado="1";
            $usuario->token=null;
            $usuario->guardar();
            Usuario::setAlerta('exito','cuenta Verificada');
        }
        $alertas = Usuario::getAlertas();
        //renderizar las vistas
        $router->render('auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);
    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

}