<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ApiConfig extends BaseConfig
{
    public string $sioBaseUrl  = '';
    public string $svrdBaseUrl = '';
    public string $slpBaseUrl  = '';

    public function __construct()
    {
        parent::__construct();
        $this->sioBaseUrl  = getenv('API_SIO_GUDANG')   ?: 'http://localhost:8001/api/v1';
        $this->svrdBaseUrl = getenv('API_SVRD_KASIR')   ?: 'http://localhost:8002/api/v1';
        $this->slpBaseUrl  = getenv('API_SLP_LOGISTIK') ?: 'http://localhost:8003/api/v1';
    }
}