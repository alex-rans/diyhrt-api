<?php

namespace App\Service;

class IsCorrectType
{
    function isCorrectType(String $type): bool
    {
        try {
            return match($type) {
                "Estradiol Pills" => true,
                "Estradiol Patches" => true,
                "Estradiol Gel" => true,
                "Estradiol Injections" => true,
                "Progesterone Capsules" => true,
                "Progesterone Gel" => true,
                "Progesterone Injections" => true,
                "Cyproterone Acetate" => true,
                "Bicalutamide" => true,
                "Spironolactone" => true,
                "GnRH Agonists" => true,
                "Finasteride" => true,
                "Dutasteride" => true,
                "Raloxifene" => true,
                "Tamoxifen" => true,
                "Clomifene" => true,
                "Domperidone" => true,
                "Pioglitazone" => true,
                "HydroxyProg Injections" => true,
            };
        } catch (\UnhandledMatchError) {
            return false;
        }
    }
}