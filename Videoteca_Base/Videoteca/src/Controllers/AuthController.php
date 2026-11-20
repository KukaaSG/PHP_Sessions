<?php
declare(strict_types=1);

namespace Controllers;

use Core\Pages;
use Core\Session;
use Models\UserModel;

/**
 * Autenticación: comprueba credenciales y abre/cierra la sesión.
 * NO es el controlador de UserModel (para eso está UserController).
 */
final class AuthController
{
    // Etapa 5: leed los flash 'error' e 'info' con Session::getFlash()
    // y pasadlos a la vista (la vista no accede a Session).
    public function showLogin(): void
    {
        $error = Session::getFlash('error');
        $info = Session::getFlash('info');
        (new Pages())->render('auth/login', ['error' => $error, 'info' => $info]);
    }

    public function login(): void
    {
        // TODO (Etapa 2): leer 'email' y 'password' de $_POST
        // TODO: buscar el usuario con (new UserModel())->findByEmail()
        // TODO: comprobar la contraseña con password_verify()
        // TODO: si es correcta -> Session::set('user_id', ...) y redirect('perfil')
        $email = htmlspecialchars($_POST['email'] ?? '');
        $password = htmlspecialchars($_POST['password'] ?? '');

        $user = (new UserModel())->findByEmail($email);
        if(password_verify($password,$user['hash'])){
            Session::regenerar();
            Session::set('user_id',$user['id']);

            redirect('perfil');
            exit();
        }
        else{
            Session::setFlash('error','Correo o contraseña no validos');
            redirect('login');
            exit();
        }
        //       (Etapa 4: llamad antes a Session::regenerar())
        // TODO: si falla -> redirect('login')
        //       (mismo comportamiento si el correo no existe que si la contraseña es incorrecta)
    }

    public function logout(): void
    {
        // TODO (Etapa 2): Session::destruir() y redirect('login')
        Session::destruir();
        Session::setFlash('info','Ha cerrado la sesión');
        redirect('login');
        exit();
    }
}