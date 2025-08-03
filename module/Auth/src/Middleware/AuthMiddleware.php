<?php

namespace Auth\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Session\Container;

class AuthMiddleware implements MiddlewareInterface
{
    protected $redirectRoute;
    protected $excludeRoutes;

    public function __construct(string $redirectRoute = '/auth/login', array $excludeRoutes = [])
    {
        $this->redirectRoute = $redirectRoute;
        $this->excludeRoutes = array_merge([
            '/auth/login',
            '/auth/logout',
            '/css/',
            '/js/',
            '/img/',
            '/favicon.ico'
        ], $excludeRoutes);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        // Check if route should be excluded from authentication
        foreach ($this->excludeRoutes as $excludeRoute) {
            if (strpos($path, $excludeRoute) === 0) {
                return $handler->handle($request);
            }
        }

        // Check authentication
        $session = new Container('auth');
        die(var_dump($session));
        if (!isset($session->usuario_id) || $session->usuario_id <= 0) {
            // User not authenticated, redirect to login
            return new RedirectResponse($this->redirectRoute);
        }

        // Check session validity (optional timeout check)
        $maxIdleTime = 3600; // 1 hour
        $lastActivity = $session->last_activity ?? $session->login_time ?? time();
        
        if ((time() - $lastActivity) > $maxIdleTime) {
            // Session expired, clear and redirect
            $session->getManager()->getStorage()->clear();
            return new RedirectResponse($this->redirectRoute);
        }

        // Update last activity
        $session->last_activity = time();

        // User is authenticated, continue with request
        return $handler->handle($request);
    }

    /**
     * Check if current user has required role
     */
    public static function checkRole($requiredRole)
    {
        $session = new Container('auth');
        
        if (!isset($session->usuario_id) || $session->usuario_id <= 0) {
            return false;
        }

        $userRole = $session->rol ?? '';
        
        if (is_array($requiredRole)) {
            return in_array($userRole, $requiredRole);
        }
        
        return $userRole === $requiredRole;
    }

    /**
     * Get current authenticated user data
     */
    public static function getCurrentUser()
    {
        $session = new Container('auth');
        
        if (!isset($session->usuario_id) || $session->usuario_id <= 0) {
            return null;
        }

        return [
            'id' => $session->usuario_id,
            'username' => $session->username ?? '',
            'role' => $session->rol ?? '',
            'email' => $session->correo ?? '',
            'name' => $session->nombre ?? '',
            'document' => $session->documento ?? ''
        ];
    }
}