<?php

declare(strict_types=1);

namespace Blog\Infrastructure\View;

class Helpers
{
    public static function island(string $name, array $props = [], ?string $ssrContent = null): string
    {
        $propsJson = !empty($props) ? htmlspecialchars(json_encode($props), ENT_QUOTES, 'UTF-8') : '';
        $propsAttr = $propsJson ? " data-props='{$propsJson}'" : '';
        return "<{$name}{$propsAttr}>{$ssrContent}</{$name}>";
    }

    public static function pjaxLink(string $url, string $text, array $attributes = []): string
    {
        $attrs = '';
        foreach ($attributes as $key => $value) {
            $value = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            $attrs .= " {$key}=\"{$value}\"";
        }

        return "<a href=\"{$url}\"{$attrs}>{$text}</a>";
    }

    public static function pjaxComponent(string $selector): string
    {
        // For now this helper is a placeholder for potential future PJAX-specific 
        // component scripts or registration logic.
        return "";
    }
}
