<?php

namespace Tests\Feature;

use Tests\TestCase;
use Cms\Core\Services\ShortcodeParser;

class ShortcodeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure shortcodes are cleared between tests if the parser is a singleton
        $parser = app(ShortcodeParser::class);
        $reflection = new \ReflectionClass($parser);
        $property = $reflection->getProperty('shortcodes');
        $property->setAccessible(true);
        $property->setValue($parser, []);
    }

    public function test_basic_shortcode()
    {
        add_shortcode('foo', function() {
            return 'bar';
        });

        $result = do_shortcode('Hello [foo] World');
        $this->assertEquals('Hello bar World', $result);
    }

    public function test_shortcode_with_attributes()
    {
        add_shortcode('greeting', function($atts) {
            $a = shortcode_atts(['name' => 'Stranger'], $atts);
            return 'Hello ' . $a['name'];
        });

        $result = do_shortcode('[greeting name="John"]');
        $this->assertEquals('Hello John', $result);

        $result2 = do_shortcode('[greeting]');
        $this->assertEquals('Hello Stranger', $result2);
    }

    public function test_enclosing_shortcode()
    {
        add_shortcode('bold', function($atts, $content = null) {
            return '<strong>' . $content . '</strong>';
        });

        $result = do_shortcode('This is [bold]important[/bold] text.');
        $this->assertEquals('This is <strong>important</strong> text.', $result);
    }

    public function test_escaped_shortcode()
    {
        add_shortcode('foo', function() {
            return 'bar';
        });

        $result = do_shortcode('This is escaped [[foo]].');
        $this->assertEquals('This is escaped [foo].', $result);
    }

    public function test_self_closing_slash_shortcode()
    {
        add_shortcode('br', function() {
            return '<br>';
        });

        $result = do_shortcode('Line 1[br/]Line 2');
        $this->assertEquals('Line 1<br>Line 2', $result);
    }

    public function test_strip_shortcodes()
    {
        add_shortcode('foo', function() {
            return 'bar';
        });
        
        add_shortcode('bold', function($atts, $content = null) {
            return '<strong>' . $content . '</strong>';
        });

        $content = 'Test [foo] and [bold]content[/bold] here.';
        $stripped = strip_shortcodes($content);
        
        $this->assertEquals('Test  and content here.', $stripped);
    }
}
