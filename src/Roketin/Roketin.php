<?php

namespace Roketin;

use Config;
use Illuminate\Http\Request;

class Roketin
{

    /**
     * @var mixed
     */
    protected $client;
    /**
     * @var mixed
     */
    protected $encrypter;

    /**
     * @var mixed
     */
    protected $retval;

    /**
     * @var mixed
     */
    protected $routes;

    public function __construct()
    {
        $this->retval    = null;
        $this->client    = new \GuzzleHttp\Client();
        $this->encrypter = new \Illuminate\Encryption\Encrypter(Config::get('roketin.encryption_key'), 'AES-256-CBC');
    }

    /**
     * @param $func
     * @param $args
     */
    public function __call($func, $args)
    {
        $meth   = $this->fetch($this->_camelToSnake($func), $args);
        $result = self::get();
        if (isset($result->errors)) {
            return false;
        }
        return $this;
    }

    /**
     * @param $str
     */
    public function fetch($str, $args)
    {
        $this->routes = empty($args) ? $str . "?" : rtrim($str, "s") . '/' . $args[0];
    }

    /**
     * raw API query
     * @param $params
     */
    public function raw($route, $params = null, $method = 'GET')
    {
        $this->retval = $this->callAPI($route, $params, $method);
        return $this;
    }

    /**
     * @return mixed
     */
    public function where($field, $operation, $value)
    {
        $filter_or = array();
        $temp      = [str_replace("-", " ", $field), $operation, $value];
        array_push($filter_or, $temp);
        $this->routes .= "&filter[]=" . urldecode(json_encode($filter_or));

        return $this;
    }

    /**
     * @param $field
     * @param $operation
     * @param $value
     * @return mixed
     */
    public function orWhere($field, $operation, $value)
    {
        $orWhere    = array();
        $request    = Request::create($this->routes);
        $filter     = $request->get('filter');
        $lastFilter = json_decode(end($filter));
        $temp       = [str_replace("-", " ", $field), $operation, $value];
        array_push($lastFilter, $temp);
        $this->routes = substr($this->routes, 0, strrpos($this->routes, "&filter[]=") + 10) . urldecode(json_encode($lastFilter));

        return $this;
    }

    /**
     * @param $field
     * @param $direction
     * @return mixed
     */
    public function sortBy($field, $direction = "ASC")
    {
        $this->routes .= "&sort=" . ($direction == "ASC" ? $field : "-" . $field);
        return $this;
    }

    /**
     * @param $size
     * @param $page
     * @return mixed
     */
    public function paginate($size = 10, $page = 1)
    {
        $this->routes .= "&page=" . $page . "&size=" . $size;
        return $this;
    }

    /**
     * @return mixed
     */
    public function random()
    {
        $this->routes .= "&random=true";
        return $this;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function groupBy($field)
    {
        $this->routes .= "&group=" . $field;
        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return is_null($this->retval) ? $this->callAPI($this->routes) : $this->retval;
    }

    /**
     * @param $size
     */
    public function take($size)
    {
        $this->routes .= "&size=" . $size;
        return is_null($this->retval) ? $this->callAPI($this->routes) : $this->retval;
    }

    /**
     * @param $email
     * @return mixed
     */
    public function subscribe($email, $bcc = null)
    {
        return $this->callAPI("subscribe", ["email" => $email, "bcc" => $bcc], "POST");
    }

    /**
     * @return mixed
     */
    public function tags($tag = null, $blog = false)
    {
        $this->routes = is_null($tag) ? "tags" : "posts/tag?tags=" . $tag . "&is_blog=" . ($blog ?: 'false');
        return $this;
    }

    /**
     * @return mixed
     */
    public function shipping()
    {
        return new RShipping();
    }

    public function order()
    {
        return new ROrder();
    }

    public function message()
    {
        return new RMessage();
    }

    public function voucher()
    {
        return new RVoucher();
    }

    public function auth()
    {
        return new RAuth();
    }

    public function user()
    {
        return new RUserFunction();
    }

    public function b2b()
    {
        return new RB2b();
    }

    /**
     * @return mixed
     */
    protected function getIP()
    {
        $ip = getenv('HTTP_CLIENT_IP') ?:
        getenv('HTTP_X_FORWARDED_FOR') ?:
        getenv('HTTP_X_FORWARDED') ?:
        getenv('HTTP_FORWARDED_FOR') ?:
        getenv('HTTP_FORWARDED') ?:
        getenv('REMOTE_ADDR');
        return $ip;
    }

    /**
     * @param $route
     * @param $extraParam
     * @param null $method
     */
    protected function callAPI($route, $extraParam = null, $method = "GET")
    {
        try {
            $response = $this->client->request($method, Config::get('roketin.api') . $route, [
                'body'    => json_encode($extraParam),
                'headers' => [
                    "company-token"   => Config::get('roketin.company-token'),
                    "client-username" => Config::get('roketin.client-username'),
                    "client-token-rx" => Config::get('roketin.client-token-rx'),
                    "Content-Type"    => "application/vnd.api+json",
                    "Content-Length"  => 0,
                ],
            ]);
            return json_decode($response->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if (is_null($e->getResponse())) {
                throw new \Exception($e->getMessage(), 422);
            }
            return json_decode($e->getResponse()->getBody()->getContents());
        } catch (\Exception $e) {
            return json_decode($e->getMessage());
        }
    }

    /**
     * @param $val
     */
    protected function _camelToSnake($val)
    {
        return preg_replace_callback('/[A-Z]/',
            create_function('$match', 'return "_" . strtolower($match[0]);'),
            $val);
    }
}
