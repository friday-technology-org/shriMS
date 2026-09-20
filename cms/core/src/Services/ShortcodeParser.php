<?php

namespace Cms\Core\Services;

class ShortcodeParser
{
    protected array $shortcodes = [];

    /**
     * Register a new shortcode tag and handler.
     */
    public function register(string $tag, callable $callback): void
    {
        $this->shortcodes[$tag] = $callback;
    }

    /**
     * Parse shortcodes inside the content.
     */
    public function parse(string $content): string
    {
        if (empty($this->shortcodes)) {
            return $content;
        }

        $pattern = $this->getRegex();

        return preg_replace_callback("~{$pattern}~s", function (array $matches) {
            $escapePrefix = $matches[1] ?? '';
            $tag = $matches[2];
            $attrString = $matches[3] ?? '';
            $enclosedContent = $matches[5] ?? null;
            $escapeSuffix = $matches[6] ?? '';

            // If it's an escaped shortcode like [[tag]], return [tag]
            if ($escapePrefix === '[' && $escapeSuffix === ']') {
                return substr($matches[0], 1, -1);
            }

            if (!isset($this->shortcodes[$tag])) {
                return $matches[0];
            }

            $attributes = $this->parseAttributes($attrString);
            
            // Shortcode callback expects: $attributes, $content, $tag
            return call_user_func($this->shortcodes[$tag], $attributes, $enclosedContent, $tag);
        }, $content);
    }

    /**
     * Strip all registered shortcodes from the content.
     */
    public function strip(string $content): string
    {
        if (empty($this->shortcodes)) {
            return $content;
        }

        $pattern = $this->getRegex();

        return preg_replace_callback("~{$pattern}~s", function (array $matches) {
            // If it's escaped [[tag]], just return the [tag] part
            if (($matches[1] ?? '') === '[' && ($matches[6] ?? '') === ']') {
                return substr($matches[0], 1, -1);
            }

            // Otherwise, replace with empty string
            return '';
        }, $content);
    }

    /**
     * Generate the regex pattern for finding shortcodes.
     * This mimics WordPress's get_shortcode_regex().
     */
    protected function getRegex(): string
    {
        $tagnames = array_keys($this->shortcodes);
        $tagregexp = join('|', array_map('preg_quote', $tagnames));

        return
              '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing bracket for escaping shortcodes: [[tag]]
    }

    /**
     * Parse attributes string into an associative array.
     */
    protected function parseAttributes(string $text): array
    {
        $attributes = [];
        $pattern = '/(\w+)\s*=\s*"([^"]*)"|(\w+)\s*=\s*\'([^\']*)\'|(\w+)\s*=\s*([^\s\'"]+)/';

        if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (!empty($match[1])) {
                    $attributes[$match[1]] = $match[2];
                } elseif (!empty($match[3])) {
                    $attributes[$match[3]] = $match[4];
                } elseif (!empty($match[5])) {
                    $attributes[$match[5]] = $match[6];
                }
            }
        }

        return $attributes;
    }
}
