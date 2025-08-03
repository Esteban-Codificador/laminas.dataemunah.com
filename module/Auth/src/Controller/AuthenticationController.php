<?php

namespace Auth\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Session\Container;
use Auth\Form\LoginForm;
use Auth\Model\UsuarioMapper;

class AuthenticationController extends AbstractActionController
{
    protected $usuarioMapper;
    
    public function __construct(UsuarioMapper $usuarioMapper)
    {
        $this->usuarioMapper = $usuarioMapper;
    }
    
    public function loginAction()
    {
        // Cambiar el layout para las páginas de auth
        $this->layout('layout/auth-layout');

        // Verificar si ya está logueado
        $session = new Container('auth');
        if (isset($session->usuario_id) && $session->usuario_id > 0) 
        {
            return $this->redirect()->toRoute('home');
        }

        $form           = new LoginForm();
        $request        = $this->getRequest();
        $errorMessage   = '';

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $username = $data['username'];
                $password = $data['password'];
                $remember = isset($data['remember']) ? $data['remember'] : false;

                // Intentar autenticar al usuario
                $usuario = $this->usuarioMapper->authenticate($username, $password);
                if ($usuario) {
                    // Usuario autenticado correctamente
                    $this->createUserSession($usuario, $remember);
                    
                    // Log del acceso (similar al código original)
                    $this->logUserAccess($usuario);
                    
                    // Redireccionar al dashboard/index
                    return $this->redirect()->toRoute('home');

                    
                } else {
                    // Verificar si el usuario existe pero está inactivo
                    $usuarioInactivo = $this->usuarioMapper->findByUsername($username);
                    
                    if ($usuarioInactivo && $usuarioInactivo->getId() && !$usuarioInactivo->isActive()) {
                        $errorMessage = 'Usuario Inactivo. Consulte con el administrador.';
                    } else {
                        $errorMessage = 'Usuario o contraseña incorrectos.';
                    }
                }
            } else {
                $errorMessage = 'Por favor complete todos los campos correctamente.';
            }
        }

        $viewModel = new ViewModel([
            'title' => 'Iniciar Sesión',
            'form' => $form,
            'errorMessage' => $errorMessage
        ]);
        
        return $viewModel;
    }
    
    public function logoutAction()
    {
        // Limpiar la sesión
        $session = new Container('auth');
        $session->getManager()->getStorage()->clear();
        
        // También limpiar otras sesiones si existen
        $authSession = new Container('caudataauth');
        if ($authSession->getManager()) {
            $authSession->getManager()->getStorage()->clear();
        }
        
        // Redireccionar al login
        return $this->redirect()->toRoute('auth', ['action' => 'login']);
    }

    /**
     * Crear sesión del usuario (similar al código original de ZF1)
     */
    protected function createUserSession($usuario, $remember = false)
    {
        $session = new Container('auth');
        $session->getManager()->regenerateId();
        
        // Guardar datos principales del usuario en sesión
        $session->usuario_id = $usuario->getId();
        $session->username = $usuario->getUsername();
        $session->rol = $usuario->getRole();
        $session->correo = $usuario->getUseremail();
        $session->nombre = $usuario->getUsernombres();
        $session->documento = $usuario->getUserdocumento();
        $session->login_time = time();
        $session->ip = $this->getClientIp();

        // Si se marca "recordarme", extender la sesión
        if ($remember) {
            $session->getManager()->rememberMe(86400 * 30); // 30 días
        }

        // Crear también la sesión compatible con el sistema anterior
        $legacySession = new Container('caudataauth');
        $legacySession->usuario_id = $usuario->getId();
        $legacySession->rol = $usuario->getRole();
        $legacySession->correo = $usuario->getUseremail();
        $legacySession->nombre = $usuario->getUsernombres();
        $legacySession->documento = $usuario->getUserdocumento();
    }

    /**
     * Registrar el acceso del usuario (similar al historial del código original)
     */
    protected function logUserAccess($usuario)
    {
        $ip = $this->getClientIp();
        $fecha = date('Y-m-d H:i:s');
        
        // Aquí podrías insertar en la tabla de historial si existe
        // Por ahora solo guardamos en sesión para referencia
        $session = new Container('auth');
        $session->last_access = $fecha;
        $session->access_ip = $ip;
        
        // Si tienes la tabla aclhistorialusuarios, podrías hacer:
        /*
        try {
            $historialData = [
                'aclhusuario' => $usuario->getId(),
                'aclhip' => $ip,
                'aclhfecha' => $fecha
            ];
            // $this->historialMapper->add($historialData);
        } catch (\Exception $e) {
            // Log error but don't interrupt login process
            error_log("Error logging user access: " . $e->getMessage());
        }
        */
    }

    /**
     * Obtener IP del cliente
     */
    protected function getClientIp()
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                // En caso de múltiples IPs, tomar la primera
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * Verificar si el usuario está autenticado (método auxiliar)
     */
    public function isAuthenticated()
    {
        $session = new Container('auth');
        return isset($session->usuario_id) && $session->usuario_id > 0;
    }

    /**
     * Obtener datos del usuario actual
     */
    public function getCurrentUser()
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $session = new Container('auth');
        return [
            'id' => $session->usuario_id,
            'username' => $session->username,
            'role' => $session->rol,
            'email' => $session->correo,
            'name' => $session->nombre,
            'document' => $session->documento
        ];
    }
}