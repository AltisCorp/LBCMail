<?php

require_once dirname(__FILE__)."/Abstract.php";

class HttpClientCurl extends HttpClientAbstract
{
    protected $_resource;

    public function __construct()
    {
        $this->_resource = curl_init();
        curl_setopt($this->_resource, CURLOPT_HEADER, false);
        curl_setopt($this->_resource, CURLOPT_RETURNTRANSFER, true);
        if (!ini_get("safe_mode")) {
            curl_setopt($this->_resource, CURLOPT_FOLLOWLOCATION, true);
        }
        curl_setopt($this->_resource, CURLOPT_CONNECTTIMEOUT, 30);
    }

    public function request($url = null)
    {
        if (!$this->_url && !$url) {
            throw new Exception("Aucune URL à appeler.");
        }
        if ($url) {
            $this->setUrl($url);
        }
        if (!isset($this->_method) || $this->_method == self::METHOD_GET) {
            curl_setopt($this->_resource, CURLOPT_HTTPGET, true);
        } else {
            curl_setopt($this->_resource, CURLOPT_POST, true);
        }
        if ($this->_proxy_ip) {
            curl_setopt($this->_resource, CURLOPT_PROXY, $this->_proxy_ip);
            if (!$this->_proxy_type || $this->_proxy_type == self::PROXY_TYPE_HTTP) {
                curl_setopt($this->_resource, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            } else {
                curl_setopt($this->_resource, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            }
            if ($this->_proxy_port) {
                curl_setopt($this->_resource, CURLOPT_PROXYPORT, $this->_proxy_port);
            }
        }
        if ($userAgent = $this->getUserAgent()) {
            curl_setopt($this->_resource, CURLOPT_USERAGENT, $userAgent);
        }
        curl_setopt($this->_resource, CURLOPT_NOBODY, !$this->_download_body);
        curl_setopt($this->_resource, CURLOPT_URL, $this->getUrl());
        if (false === $body = curl_exec($this->_resource)) {
            return false;
        }
        $this->_respond_code = curl_getinfo($this->_resource, CURLINFO_HTTP_CODE);
        $this->_body = $body;
        return $this->_body;
    }

    /**
     * Retourne la dernière erreur générée par cURL.
     * @return string
     */
    public function getError()
    {
        return curl_error($this->_resource);
    }

    public function __destruct()
    {
        curl_close($this->_resource);
    }
}