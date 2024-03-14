<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $xpath = new \DOMXPath($dom);

    $papers = [];

    foreach ($xpath->query('//a[contains(@class, "paper-card")]') as $value) {
      $id = $xpath->query('div/div/div[contains(@class, "volume-info")]', $value)->item(0)->textContent;
      $title = $xpath->query('h4', $value)->item(0)->textContent;
      $type = $xpath->query('div/div[contains(@class, "tags mr-sm")]', $value)->item(0)->textContent;
      $persons = [];

      foreach ($xpath->query('div[contains(@class, "authors")]/span', $value) as $authorSpan) {
        $institution = $authorSpan->getAttribute('title');
        $authorName = $authorSpan->textContent;
        $persons[] = new Person($authorName, $institution);
      }
      $papers[] = new Paper($id, $title, $type, $persons);
    }

    return $papers;
  }

}
