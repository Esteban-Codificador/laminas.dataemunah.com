<?php

namespace Auth\Service;

use Laminas\Session\Container;
use Auth\Model\UsuarioMapper;

class AuthService
{
    protected $usuarioMapper;
    protected $session;

    public function __construct(UsuarioMapper $usuarioMapper)
    {
        $this->usuarioMapper = $usuarioMapper;
        $this->session = new Container('auth');
    }

    /**
     * Check if user is authenticated
     */
    public function isAuthenticated()
    {
        return isset($this->session->usuario_id) && $this->session->usuario_id > 0;
    }

    /**
     * Get current user data
     */
    public function getCurrentUser()
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return [
            'id' => $this->session->usuario_id,
            'username' => $this->session->username,
            'role' => $this->session->rol,
            'email' => $this->session->correo,
            'name' => $this->session->nombre,
            'document' => $this->session->documento,
            'login_time' => $this->session->login_time ?? null,
            'ip' => $this->session->ip ?? null,
        ];
    }

    /**
     * Get current user ID
     */
    public function getCurrentUserId()
    {
        return $this->isAuthenticated() ? $this->session->usuario_id : null;
    }

    /**
     * Get current user role
     */
    public function getCurrentUserRole()
    {
        return $this->isAuthenticated() ? $this->session->rol : null;
    }

    /**
     * Check if current user has specific role
     */
    public function hasRole($role)
    {
        $currentRole = $this->getCurrentUserRole();
        return $currentRole === $role;
    }

    /**
     * Check if current user has any of the specified roles
     */
    public function hasAnyRole(array $roles)
    {
        $currentRole = $this->getCurrentUserRole();
        return in_array($currentRole, $roles);
    }

    /**
     * Clear authentication
     */
    public function clearAuth()
    {
        $this->session->getManager()->getStorage()->clear();
        
        // También limpiar sesión legacy
        $legacySession = new Container('caudataauth');
        if ($legacySession->getManager()) {
            $legacySession->getManager()->getStorage()->clear();
        }
    }

    /**
     * Get session container
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Refresh user data from database
     */
    public function refreshUserData()
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        $usuario = $this->usuarioMapper->findById($this->session->usuario_id);
        
        if (!$usuario || !$usuario->getId() || !$usuario->isActive()) {
            $this->clearAuth();
            return false;
        }

        // Update session with fresh data
        $this->session->username = $usuario->getUsername();
        $this->session->rol = $usuario->getRole();
        $this->session->correo = $usuario->getUseremail();
        $this->session->nombre = $usuario->getUsernombres();
        $this->session->documento = $usuario->getUserdocumento();

        return true;
    }

    /**
     * Check if session is still valid (not expired)
     */
    public function isSessionValid($maxIdleTime = 3600) // 1 hour default
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        $lastActivity = $this->session->last_activity ?? $this->session->login_time ?? time();
        
        if ((time() - $lastActivity) > $maxIdleTime) {
            $this->clearAuth();
            return false;
        }

        // Update last activity
        $this->session->last_activity = time();
        return true;
    }
}