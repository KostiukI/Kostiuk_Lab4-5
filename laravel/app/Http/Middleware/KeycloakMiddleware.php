<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KeycloakMiddleware
{
    private function getAllRoles(array $payload): array
    {
        // Get realm roles
        $realmRoles = $payload['realm_access']['roles'] ?? [];
        
        // Get client-specific roles
        $clientRoles = [];
        if (isset($payload['resource_access'])) {
            foreach ($payload['resource_access'] as $client) {
                if (isset($client['roles'])) {
                    $clientRoles = array_merge($clientRoles, $client['roles']);
                }
            }
        }

        return array_unique(array_merge($realmRoles, $clientRoles));
    }

    private function hasAccessToSubscription(array $userRoles, string $method): bool
    {
        Log::info('Checking subscription access', [
            'user_roles' => $userRoles,
            'method' => $method
        ]);

        if (in_array('lv-subscriptions-write', $userRoles)) {
            return true;
        }

        if (in_array('lv-subscriptions-read', $userRoles)) {
            return $method === 'GET';
        }

        return false;
    }

    private function hasAccessToSubscriber(array $userRoles, string $method): bool
    {
        Log::info('Checking subscriber access', [
            'user_roles' => $userRoles,
            'method' => $method
        ]);

        if (in_array('lv-subscribers-write', $userRoles)) {
            return true;
        }

        if (in_array('lv-subscribers-read', $userRoles)) {
            return $method === 'GET';
        }

        return false;
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No token provided'
                ], 401);
            }

            // Decode token
            $tokenParts = explode('.', $token);
            $payload = json_decode(base64_decode($tokenParts[1]), true);

            // Get all roles
            $userRoles = $this->getAllRoles($payload);

            // Check access based on route
            $path = $request->path();
            $method = $request->method();

            Log::info('Request details', [
                'path' => $path,
                'method' => $method,
                'all_roles' => $userRoles
            ]);

            $hasAccess = false;
            if (str_contains($path, 'subscribers')) {
                $hasAccess = $this->hasAccessToSubscriber($userRoles, $method);
            } elseif (str_contains($path, 'subscriptions')) {
                $hasAccess = $this->hasAccessToSubscription($userRoles, $method);
            }

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient permissions'
                ], 403);
            }

            // Add user information to request
            $request->merge([
                'user_roles' => $userRoles,
                'user_email' => $payload['email'] ?? null,
                'user_id' => $payload['sub'] ?? null
            ]);

            return $next($request);

        } catch (\Exception $e) {
            Log::error('Token validation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid token',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}