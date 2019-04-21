<?php

namespace PrestaShop\Training\Twig;

use Twig\Extension\AbstractExtension;

/**
 * Registers custom Twig functions
 */
class TrainingExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('hello', function () {
                return 'Hello World';
            }),
        ];
    }
}
