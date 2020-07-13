<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('icon_from_bool', [$this, 'iconFromBoolean']),
        ];
    }

    /**
     * Return an HTML icon form boolean value
     *
     * @param string $bool
     * @return Markup
     */
    public function iconFromBoolean(string $bool): Markup
    {
        $bool = (int) $bool;

        if ($bool === 1) return new Markup('<span class="status-icon check-icon"><i class="far fa-check-circle" aria-hidden="true"></i></span>', 'UTF-8');
        else return new Markup('<span class="status-icon cross-icon"><i class="far fa-times-circle" aria-hidden="true"></i></span>', 'UTF-8');
    }
}