<?php

namespace membership;

class Connect extends Config {
    
    public function PDO()
    {
        return new \PDO("mysql:host=" . $this->config_host . "; dbname=" . $this->config_database, $this->config_username, $this->config_password);
    }
    
}
