<?php

abstract class HttpClientAbstract
{
    protected $_proxy_type;
    protected $_proxy_ip;
    protected $_proxy_port;
    protected $_url;
    protected $_method;
    protected $_user_agent;
    protected $_body;

    const METHOD_GET = "get";
    const METHOD_POST = "post";
    const PROXY_TYPE_HTTP = 1;
    const PROXY_TYPE_SOCKS5 = 2;

    /**
     * @param string $ip
     * @return HttpClientAbstract
     */
    public function setProxyType($type)
    {
        if ($type != self::PROXY_TYPE_HTTP && $type != self::PROXY_TYPE_SOCKS5) {
            throw new Exception("Type de proxy invalide.");
        }
        $this->_proxy_type = $type;
        return $this;
    }

    public function getProxyType()
    {
        return $this->_proxy_type;
    }

    /**
     * @param string $ip
     * @return HttpClientAbstract
     */
    public function setProxyIp($ip)
    {
        $this->_proxy_ip = $ip;
        return $this;
    }

    public function getProxyIp()
    {
        return $this->_proxy_ip;
    }

    /**
    * @param int $proxy_port
    * @return HttpClientAbstract
    */
    public function setProxyPort($proxy_port)
    {
        $this->_proxy_port = $proxy_port;
        return $this;
    }
    
    /**
    * @return int
    */
    public function getProxyPort()
    {
        return $this->_proxy_port;
    }

    /**
    * @param string $user_agent
    * @return HttpClientAbstract
    */
    public function setUserAgent($user_agent)
    {
        $this->_user_agent = $user_agent;
        return $this;
    }
    
    /**
    * @return string
    */
    public function getUserAgent()
    {
        return $this->_user_agent;
    }

    /**
     * @param string $method
     * @throws Exception
     * @return HttpClientAbstract
     */
    public function setMethod($method)
    {
        if ($method != self::METHOD_GET && $method != self::METHOD_POST) {
            throw new Exception("Méthode invalide.");
        }
        $this->_method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Définit l'URL à appeler.
     * @param string $url
     * @return HttpClientAbstract
     */
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    abstract function request();
}