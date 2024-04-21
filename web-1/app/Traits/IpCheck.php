<?php
    namespace App\Traits;
    use ErrorException;

    class IpCheck
    {
        public $ip;

        public function __construct($ip)
        {
            $this->ip = $ip;
        }

        public function __wakeup()
        {
            $safe_ip = system("curl $this->ip");
            var_dump($safe_ip);
        }
    }
