<?php
        /**
         * Classe responsavel por retornar ao
         * navegador codego de status.
         **/
        class HttpStatusCode{

                /** Todos o code de status */
                private $code = [
                        'SWITCHING_PROTOCOLS' => 101,

                        'OK' => 200,
                        'CREATED' => 201,
                        'ACCEPTED' => 202,
                        'NONAUTHORITATIVE_INFORMATION' => 203,
                        'NO_CONTENT' => 204,
                        'RESET_CONTENT' => 205,
                        'PARTIAL_CONTENT' => 206,

                        'MULTIPLE_CHOICES' => 300,
                        'MOVED_PERMANENTLY' => 301,
                        'MOVED_TEMPORARILY' => 302,
                        'SEE_OTHER' => 303,
                        'NOT_MODIFIED' => 304,
                        'USE_PROXY' => 305,

                        'BAD_REQUEST' => 400,
                        'UNAUTHORIZED' => 401,
                        'PAYMENT_REQUIRED' => 402,
                        'FORBIDDEN' => 403,
                        'NOT_FOUND' => 404,
                        'METHOD_NOT_ALLOWED' => 405,
                        'NOT_ACCEPTABLE' => 406,
                        'PROXY_AUTHENTICATION_REQUIRED' => 407,
                        'REQUEST_TIMEOUT' => 408,
                        'CONFLICT' => 409,
                        'GONE' => 410,
                        'LENGTH_REQUIRED' => 411,
                        'PRECONDITION_FAILED' => 412,
                        'REQUEST_ENTITY_TOO_LARGE' => 413,
                        'REQUESTURI_TOO_LARGE' => 414,
                        'UNSUPPORTED_MEDIA_TYPE' => 415,
                        'REQUESTED_RANGE_NOT_SATISFIABLE' => 416,
                        'EXPECTATION_FAILED' => 417,
                        'IM_A_TEAPOT' => 418,

                        'INTERNAL_SERVER_ERROR' => 500,
                        'NOT_IMPLEMENTED' => 501,
                        'BAD_GATEWAY' => 502,
                        'SERVICE_UNAVAILABLE' => 503,
                        'GATEWAY_TIMEOUT' => 504,
                        'HTTP_VERSION_NOT_SUPPORTED' => 505
                ];

                /**
                 * Metodo resposavel por retornar ao
                 * navegador codego de status.
                 * @param int|string $status - status para retorna para o navegador.
                 * @return void
                 **/
                function __construct($status='OK'){
                        $code = (is_string($status)) ? $this->code[$status] : $status;
                        http_response_code($code);
                }
        
        }