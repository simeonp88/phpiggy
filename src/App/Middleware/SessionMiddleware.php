<?php

declare(strict_types=1);


namespace App\Middleware;


use Framework\Contracts\MiddlewareInterface;
use App\Exceptions\SessionException;


class SessionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session Already active.");
        }
        // ob_end_clean();
        // echo "hello";

        if (headers_sent($filename, $line)) {
            throw new SessionException("header is already sent. Consider enabling output buffering. Data outputPutted from {$filename} - Line: {$line}");
        }

        session_set_cookie_params(
            [
                'secure' => $_ENV['APP_ENV'] === "production",
                'httponly' => 'true',
                'samesite' => 'lax'
            ]
        );

        session_start();

        $next();

        session_write_close();
    }
}