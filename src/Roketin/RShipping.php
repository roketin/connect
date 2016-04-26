<?php

namespace Roketin;

class RShipping extends Roketin
{

    public function __construct()
    {
        parent::__construct();
    }
/**
 * @return mixed
 */
    public function countries()
    {
        $this->routes = "shipping/countries";

        return $this->get();
    }

    /**
     * @return mixed
     */
    public function province($provinceid = null)
    {
        $this->routes = is_null($provinceid) ? "shipping/province" : "shipping/city?province=" . $provinceid;

        return is_null($provinceid) ? $this->get() : $this;
    }

    /**
     * @param $province
     * @return mixed
     */
    public function cities()
    {
        return $this->get();
    }

    /**
     * @param $destination = city id
     * @param $courier = jne/tiki/pos
     * @param $weight = item weight
     * @param $origin = city id
     * @return mixed
     */
    public function costs($destination = 23, $courier = "jne", $weight = 1, $origin = 23)
    {
        $this->routes = "shipping/" . strtolower($courier) . "/cost?origin=" . $origin . "&destination=" . $destination . "&weight=" . $weight;

        return $this->get();
    }
}
