<?php

class MenuValidator
{
    public static function updateMenuRules()
    {
        return [
            "salad_1" => ['string'],
            "salad_2" => ['string'],
            "salad_3" => ['string'],
            "grains_1" => ['string'],
            "grains_2" => ['string'],
            "grains_3" => ['string'],
            "side_dish" => ['string'],
            "mixture" => ['string'],
            "vegan_mixture" => ['string'],
            "dessert" => ['string'],
        ];
    }

    public static function createMenuRules()
    {
        return [
            "salad_1" => ['required', 'string'],
            "salad_2" => ['required', 'string'],
            "salad_3" => ['required', 'string'],
            "grains_1" => ['required', 'string'],
            "grains_2" => ['required', 'string'],
            "grains_3" => ['required', 'string'],
            "side_dish" => ['required', 'string'],
            "mixture" => ['required', 'string'],
            "vegan_mixture" => ['required', 'string'],
            "dessert" => ['required', 'string'],
            "date" => ['required', 'date', 'unique:menus']
        ];
    }

}
