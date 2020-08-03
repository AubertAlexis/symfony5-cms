<?php

namespace App\Twig;

use App\Entity\Nav;
use App\Entity\NavLink;
use App\Entity\SubNav;
use App\Repository\NavLinkRepository;
use App\Repository\NavRepository;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var NavRepository
     */
    private $navRepository;

    /**
     * @var NavLinkRepository
     */
    private $navLinkRepository;

    public function __construct(NavRepository $navRepository, NavLinkRepository $navLinkRepository)
    {
        $this->navRepository = $navRepository;
        $this->navLinkRepository = $navLinkRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('icon_from_bool', [$this, 'iconFromBoolean']),
            new TwigFunction('get_main_navigation', [$this, 'getMainNavigation']),
            new TwigFunction('render_main_navigation', [$this, 'renderMainNavigation']),
            new TwigFunction('get_navigation_by_key', [$this, 'getNavigationByKey']),
            new TwigFunction('render_navigation_by_key', [$this, 'renderNavigationByKey'])
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

        if ($bool === 1) return $this->renderAsHtml('<span class="status-icon check-icon"><i class="far fa-check-circle" aria-hidden="true"></i></span>');
        else return $this->renderAsHtml('<span class="status-icon cross-icon"><i class="far fa-times-circle" aria-hidden="true"></i></span>');
    }

    /**
     * Return the main navigation if exist
     *
     * @return Nav|null
     */
    public function getMainNavigation()
    {
        return $this->navRepository->findOneBy([
            "main" => true,
            "enabled" => true
        ]);
    }

    /**
     * Render HTML of the main navigation
     *
     * @return Markup
     */
    public function renderMainNavigation()
    {
        $mainNavigation = $this->getMainNavigation();

        if (null === $mainNavigation) {
            return null;
        }

        $htmlNavigation = "<ul class='navbar-nav'>";

        /**
         * @var NavLink $navLink
         */
        foreach ($mainNavigation->getNavLinks() as $navLink) {
            $htmlNavigation = $this->renderSubNavs($navLink, $htmlNavigation);
        }

        $htmlNavigation .= "</ul>";

        return $this->renderAsHtml($htmlNavigation);
    }

    /**
     * Return a navigation by key
     *
     * @return Nav|null
     */
    public function getNavigationByKey(string $key)
    {
        return $this->navRepository->findOneBy([
            "keyname" => $key,
            "enabled" => true
        ]);
    }

    /**
     * Render HTML of a navigation by key
     *
     * @return Markup
     */
    public function renderNavigationByKey(string $key)
    {
        $navigationByKey = $this->getNavigationByKey($key);

        if (null === $navigationByKey) {
            return null;
        }

        $htmlNavigation = "<ul class='navbar-nav {$key}'>";

        /**
         * @var NavLink $navLink
         */
        foreach ($navigationByKey->getNavLinks() as $navLink) {
            $htmlNavigation = $this->renderSubNavs($navLink, $htmlNavigation);
        }

        $htmlNavigation .= "</ul>";

        return $this->renderAsHtml($htmlNavigation);
    }

    /**
     * Return as HTML element
     *
     * @return Markup
     */
    private function renderAsHtml(string $data): Markup
    {
        return new Markup($data, "UTF-8");
    }

    private function renderSubNavs(NavLink $navLink, string $htmlNavigation)
    {
        $link = $navLink->getPage() !== null ? "/{$navLink->getPage()->getSlug()}" : $navLink->getLink();
        $title = $navLink->getTitle();

        $dropdown = ($navLink->getSubNavs()->count() !== 0);
        
        if (!$dropdown || $navLink->getPage() && !$navLink->getPage()->getEnabled()) {
            $htmlNavigation .= "<li class='nav-item'><a href='{$link}' class='nav-link'>{$title}</a></li>";
        } else {
            $subNavParentTitle = $navLink->getTitle();
            $subNavParentLink = $navLink->getPage() !== null ? "/{$navLink->getPage()->getSlug()}" : $navLink->getLink();

            $htmlNavigation .= "<li class='nav-item dropdown-submenu'><i class='fas fa-caret-down'></i><a href='{$subNavParentLink}' class='nav-link dropdown-hover'>{$subNavParentTitle}</a><ul class='dropdown-menu'>";
            
            /**
             * @var SubNav $subNav
             */
            foreach ($navLink->getSubNavs() as $subNav) {

                /**
                 * @var NavLink $subNavItem
                 */
                foreach ($subNav->getNavlinks() as $subNavItem) {
                    $subNavLink = $subNavItem->getPage() !== null ? "/{$subNavItem->getPage()->getSlug()}" : $subNavItem->getLink();
                    $subNavTitle = $subNavItem->getTitle();



                    if ($subNavItem->getPage() && !$subNavItem->getPage()->getEnabled()) continue;
                    else if ($subNavItem->getSubNavs()->count() !== 0) $htmlNavigation = $this->renderSubNavs($subNavItem, $htmlNavigation);
                    else $htmlNavigation .= "<li class='nav-item dropdown'><a class='dropdown-item' href='{$subNavLink}'>{$subNavTitle}</a></li>";
                }
                
            }

            $htmlNavigation .= "</ul></li>";
        }

        return $htmlNavigation;
    }
}