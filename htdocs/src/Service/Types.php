<?php

namespace App\Service;

class Types
{
    private $choiceArray = [
        "Estradiol Pills",
        "Estradiol Patches",
        "Estradiol Gel",
        "Estradiol Injections",
        "Progesterone Capsules",
        "Progesterone Gel",
        "Progesterone Injections",
        "Cyproterone Acetate",
        "Bicalutamide",
        "Spironolactone",
        "Gonadotropin-Releasing Hormone Agonists",
        "Finasteride",
        "Dutasteride",
        "Raloxifene",
        "Tamoxifen",
        "Clomifene",
        "Domperidone",
        "Pioglitazone",
        "Hydroxyprogesterone Caproate Injections"
    ];

    public function isCorrectType(string $type): bool
    {
        return in_array($type, $this->choiceArray);
    }

    public function getChoices(): array
    {
        return $this->choiceArray;
    }
}