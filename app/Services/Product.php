<?php

namespace App\Services;

class Product
{
    private $name = null;
    private $brand = null;
    private $price = null;
    private $image = null;

    public function isComplete(): bool
    {
        return isset($this->name, $this->brand, $this->price, $this->image);
    }


    public function toArray(): array
    {
        return [
            "name" => $this->name,
            "brand" => $this->brand,
            "price" => $this->price,
            "image" => $this->image,
        ];
    }

    public function setName($value)
    {
        $this->name = $this->checkValue($value, $this->name);
    }

    public function setBrand($value)
    {
        $this->brand = $this->checkValue($value, $this->brand);
    }

    public function setPrice($value)
    {
        $this->price = $this->checkValue($value, $this->price);
    }

    public function setImage($value)
    {
        $this->image = $this->checkValue($value, $this->image);
    }

    private function checkValue($newValue, $oldValue)
    {
        return (!is_null($newValue) && $newValue !== '') ? $newValue : $oldValue;
    }
}
