<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\Url;

final class AssetManager
{
    /** @var array<string, string> handle => local path or full URL */
    private static array $styles = [];

    /** @var array<string, array{path: string, module: bool, attributes: array<string, string>}> */
    private static array $scripts = [];

    private static bool $booted = false;

    public static function style(string $handle, string $path): void
    {
        self::$styles[$handle] = $path;
    }

    /** @param array<string, string> $attributes extra HTML attributes (e.g. ['defer' => 'defer']) */
    public static function script(string $handle, string $path, bool $module = false, array $attributes = []): void
    {
        self::$scripts[$handle] = ['path' => $path, 'module' => $module, 'attributes' => $attributes];
    }

    /**
     * Registers the bundle every page needs — confirmed by grep across all
     * views: `.wow` animations, `wa_title_spilt_1` heading split-text, the
     * meanmenu mobile nav, and FontAwesome icons all appear on every page.
     */
    public static function boot(): void
    {
        if (self::$booted) {
            return;
        }
        self::$booted = true;

        self::style('bootstrap', 'css/bootstrap.min.css');
        self::style('fontawesome', 'css/all.min.css');
        self::style('animate', 'css/animate.css');
        self::style('meanmenu', 'css/meanmenu.css');
        self::style('main', 'css/main.css');
        self::style('custom', 'css/custom.css');

        self::script('jquery', 'js/jquery-3.7.1.min.js');
        self::script('viewport', 'js/viewport.jquery.js');
        self::script('bootstrap', 'js/bootstrap.bundle.min.js');
        self::script('gsap', 'js/gsap.min.js');
        self::script('scrolltrigger', 'js/ScrollTrigger.min.js');
        self::script('scrollsmoother', 'js/ScrollSmoother.min.js');
        self::script('scrolltoplugin', 'js/ScrollToPlugin.min.js');
        self::script('splittext', 'js/SplitText.min.js');
        self::script('textplugin', 'js/TextPlugin.js');
        self::script('split-type', 'https://unpkg.com/split-type');
        self::script('meanmenu-js', 'js/jquery.meanmenu.min.js');
        self::script('wow', 'js/wow.min.js');
        self::script('mobile-menu', 'js/mobile-menu.js');
        // main.js must load after every GSAP plugin above (it wires up
        // ScrollSmoother) — registration order is preserved on render.
        self::script('main', 'js/main.js');
    }

    public static function enableSwiper(): void
    {
        self::style('swiper', 'css/swiper-bundle.min.css');
        self::script('swiper', 'js/swiper-bundle.min.js');
    }

    public static function enableRevealText(): void
    {
        self::script('reveal-text', 'js/reveal-text.js');
    }

    public static function enableAppModal(): void
    {
        self::script('app-modal', 'js/app-modal.js');
    }

    public static function enableTabFilter(): void
    {
        self::script('tab-filter', 'js/tab-filter.js');
    }

    public static function enableBlogFilter(): void
    {
        self::script('blog-filter', 'js/blog-filter.js');
    }

    /**
     * Registers GA4 without any inline <script> — analytics.js reads the
     * measurement ID from its own data attribute instead of an embedded
     * gtag() init block.
     */
    public static function enableAnalytics(string $measurementId): void
    {
        self::script('analytics', 'js/analytics.js', false, ['data-ga-id' => $measurementId, 'defer' => 'defer']);
    }

    public static function renderStyles(): string
    {
        $html = '';
        foreach (self::$styles as $path) {
            $html .= '<link rel="stylesheet" href="' . e(self::resolve($path)) . '">' . "\n";
        }
        return $html;
    }

    public static function renderScripts(): string
    {
        $html = '';
        foreach (self::$scripts as $script) {
            $attrs = '';
            foreach ($script['attributes'] as $name => $value) {
                $attrs .= ' ' . $name . '="' . e($value) . '"';
            }
            $typeAttr = $script['module'] ? ' type="module"' : '';
            $html .= '<script' . $typeAttr . ' src="' . e(self::resolve($script['path'])) . '"' . $attrs . '></script>' . "\n";
        }
        return $html;
    }

    private static function resolve(string $path): string
    {
        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        return Url::asset($path);
    }
}
