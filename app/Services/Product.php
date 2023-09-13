<?php

namespace App\Services;

class Product
{
    private $name = null;

    private $brand = null;

    private $price = null;

    private $image = null;

    public function getMissingFields(): array
    {
        $fields = ['name', 'brand', 'price', 'image'];
        $missingFields = array_filter($fields, function ($field) {
            return is_null($this->$field);
        });

        return $missingFields;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'brand' => $this->brand,
            'price' => $this->price,
            'image' => $this->image,
            'hasMissedFields' => count($this->getMissingFields()) > 0,
        ];
    }

    private function parsePrice($value)
    {
        $value = preg_replace('/[^0-9.]/', '', $value);

        if (is_numeric($value)) {
            return number_format(floatval($value), 2, '.', '');
        }

        return null;
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
        $value = $this->parsePrice($value);
        $this->price = $this->checkValue($value, $this->price);
    }

    public function setImage($value)
    {
        $this->image = $this->checkValue($value, $this->image);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getImage()
    {
        return $this->image;
    }

    private function checkValue($newValue, $oldValue)
    {
        return (! is_null($newValue) && $newValue !== '') ? $newValue : $oldValue;
    }
}
