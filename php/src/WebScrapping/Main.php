<?php

namespace Chuva\Php\WebScrapping;

error_reporting(E_ERROR | E_PARSE);

/**
 * Runner for the Webscraping exercise.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    $idArray = [];
    $id = $dom->getElementsByTagName('div');
    foreach ($id as $ids) {
      if ($ids->getAttribute('class') === 'volume-info') {
        $idArray[] = $ids->textContent;
      }
    }
    print_r($idArray);

    $titleArray = [];
    $titles = $dom->getElementsByTagName('h4');
    foreach ($titles as $title) {
      if ($title->getAttribute('class') === 'my-xs paper-title') {
        $titleArray[] = $title->textContent;
      }
    }
    print_r($titleArray);

    $authorArray = [];
    $authors = $dom->getElementsByTagName('div');
    foreach ($authors as $author) {
      if($author->getAttribute('class') === 'authors') {
        $authorNames = explode(';', $author->textContent);
        $cleanedAuhtorNames = array_map('trim', $authorNames);
        $authorArray[] = $cleanedAuhtorNames;
      }
    }
    print_r($authorArray);
    // Write your logic to save the output file below.
    print_r($data);
  }

}
