<?php

namespace Drupal\Tests\drupalorg_projects\Unit;

use Drupal\drupalorg_projects\Traits\SplitsStrings;
use Drupal\Tests\UnitTestCase;

/**
 * Test the SplitsStrings trait.
 */
class SplitsStringsTest extends UnitTestCase {

  use SplitsStrings;

  /**
   * Test that comma-separated strings are split.
   */
  public function testCommaSeparatedStringsAreSplit() {
    $string = 'a,b,c';

    $this->assertEquals(['a', 'b', 'c'], $this->splitString($string));
  }

  /**
   * Test that newline separated strings are split.
   */
  public function testNewLineSeparatedStringsAreSplit() {
    $string = "a\nb\nc";

    $this->assertEquals(['a', 'b', 'c'], $this->splitString($string));
  }

  /**
   * Test that whitespace is removed around delimiters.
   */
  public function testWhitespaceIsRemoved() {
    $string = "a  \n b   , c";

    $this->assertEquals(['a', 'b', 'c'], $this->splitString($string));
  }

}
