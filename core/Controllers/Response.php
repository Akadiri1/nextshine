<?php
class Response
{
    public function status($code)
    {
        http_response_code($code);
        return $this;
        die;
    }

    public function header($key, $value)
    {
        header("$key: $value");
        return $this;
    }

    public function headers(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->header($key, $value);
        }
        return $this;
    }

    public function json($data)
    {
        $this->header('Content-Type', 'application/json');
        echo json_encode($data);
        return $this;
        die;
    }

    public function jsonp($data, $callback)
    {
        $this->header('Content-Type', 'application/javascript');
        echo $callback . '(' . json_encode($data) . ')';
        return $this;
    }

    public function send($data)
    {
        echo $data;
        return $this;
    }

    public function redirect($url, $statusCode = 302)
    {
        $this->status($statusCode);
        header("Location: $url");
        return $this;
        die;
    }

    public function cookie($name, $value, $expiry = 3600, $path = "/", $domain = "", $secure = false, $httponly = true)
    {
        setcookie($name, $value, time() + $expiry, $path, $domain, $secure, $httponly);
        return $this;
    }

    public function clearCookie($name)
    {
        setcookie($name, '', time() - 3600);
        return $this;
    }

    public function html($html)
    {
        $this->header('Content-Type', 'text/html');
        echo $html;
        return $this;
    }

    public function contentType($type)
    {
        $this->header('Content-Type', $type);
        return $this;
    }

    public function xml($data)
    {
        $this->header('Content-Type', 'application/xml');

        $xml = new SimpleXMLElement('<response/>');
        $this->arrayToXml($data, $xml);
        echo $xml->asXML();

        return $this;
    }

    private function arrayToXml($data, &$xml)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }

    public function download($filePath, $fileName = null)
    {
        if (!file_exists($filePath)) {
            $this->status(404)->send("File not found");
            return $this;
        }

        $fileName = $fileName ?? basename($filePath);
        $this->header('Content-Type', mime_content_type($filePath));
        $this->header('Content-Disposition', "attachment; filename=\"$fileName\"");
        $this->header('Content-Length', filesize($filePath));

        readfile($filePath);
        return $this;
    }

    public function clearHeader($key)
    {
        header_remove($key);
        return $this;
    }

    public function statusMessage($code, $message)
    {
        header("HTTP/1.1 $code $message");
        return $this;
    }

    public function httpVersion($version = '1.1')
    {
        header("HTTP/$version");
        return $this;
    }

    public function cacheControl($cacheType = 'no-cache', $maxAge = 0)
    {
        $this->header('Cache-Control', "$cacheType, max-age=$maxAge");
        return $this;
    }

    public function stream($callback)
    {
        $this->header('Content-Encoding', 'none');
        $this->header('Transfer-Encoding', 'chunked');
        $this->header('Content-Type', 'text/plain');

        ob_start();
        call_user_func($callback);
        ob_end_flush();
        flush();

        return $this;
    }
}
