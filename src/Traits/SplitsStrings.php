<?php

namespace Drupal\drupalorg_projects\Traits;

trait SplitsStrings {

    /**
     * Split a string.
     *
     * @param string $string
     *   The original string.
     *
     * @return array[]|false|string[]
     *   The result from preg_split.
     */
    public function splitString($string) {
      return preg_split('/\s*[\n|,]\s*/', $string);
    }

}
