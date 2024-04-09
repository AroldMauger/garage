<?php

namespace App\Listener;

use App\Context\UserApiContext;
use App\Repository\UsersRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ApiAuthenticationListener
{
    public function __construct(private readonly UsersRepository $usersRepository, private readonly UserApiContext $userApiContext)
    {

    }

    private const TOKEN_HEADER_NAME = "authorization";

    private function hasHeader(Request $request) {
        return $request ->headers ->has(self::TOKEN_HEADER_NAME);
    }
    private function getHeader(Request $request) {
        return $request ->headers ->get(self::TOKEN_HEADER_NAME);

    }
    public function onKernelRequest(RequestEvent $event): void {
        $request = $event ->getRequest();
        if (!str_starts_with($request->getRequestUri(), '/api/')) {
            return;
        }
        if($this ->hasHeader($request)) {
            $token = $this->getHeader($request);
            try {
                $decodedToken = JWT::decode($token, new Key("example_key", "HS256"));
                $user = $this -> usersRepository ->findOneBy(["id" => $decodedToken -> userId]);
               if($user){
                   $this -> userApiContext -> setUser($user);
               }
            } catch (\Exception $e) {
            }
        }
    }
}