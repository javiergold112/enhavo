<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 24.08.18
 * Time: 02:11
 */

namespace Enhavo\Bundle\SearchBundle\Util;

class HighlighterTest extends \PHPUnit_Framework_TestCase
{
    public function testHighlightSimple()
    {
        $highlighter = new Highlighter();
        $text = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.";
        $highligtText = $highlighter->highlight($text, ['sadipscing'], 50, '[open]', '[close]', '[concat]');
        $this->assertEquals('dolor sit amet, consetetur [open]sadipscing[close] elitr, sed diam nonumy', $highligtText);
    }

    public function testHighlightEndSentence()
    {
        $highlighter = new Highlighter();
        $text = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr. Sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.";
        $highligtText = $highlighter->highlight($text, ['sadipscing'], 50, '[open]', '[close]', '[concat]');
        $this->assertEquals('ipsum dolor sit amet, consetetur [open]sadipscing[close] elitr.', $highligtText);
    }

    public function testHighlightStartSentence()
    {
        $highlighter = new Highlighter();
        $text = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr. Sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.";
        $highligtText = $highlighter->highlight($text, ['tempor'], 50, '[open]', '[close]', '[concat]');
        $this->assertEquals('Sed diam nonumy eirmod [open]tempor[close] invidunt ut labore et', $highligtText);
    }

    public function testHighlightMultiple()
    {
        $highlighter = new Highlighter();
        $text = "Lorem ipsum dolor et amet, consetetur sadipscing elitr. Sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.";
        $highligtText = $highlighter->highlight($text, ['et'], 50, '[open]', '[close]', '[concat]');
        $this->assertEquals('ipsum dolor [open]et[close] amet, consetetur[concat]ut labore [open]et[close] dolore magna', $highligtText);
    }
}