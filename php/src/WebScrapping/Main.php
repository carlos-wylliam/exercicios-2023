<?php

namespace Chuva\Php\WebScrapping;

use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

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
      if ($author->getAttribute('class') === 'authors') {
        $authorNames = explode(';', $author->textContent);
        $cleanedAuhtorNames = array_map('trim', $authorNames);
        $authorArray[] = $cleanedAuhtorNames;
      }
    }
    print_r($authorArray);

    $typeArray = [];
    $types = $dom->getElementsByTagName('div');
    foreach ($types as $type) {
      if ($type->getAttribute('class') === 'tags mr-sm') {
        $typeArray[] = $type->textContent;
      }
    }
    print_r($typeArray);
    $authorInstitution = [];
    $authorInstitutions = $dom->getElementsByTagName('div');
    foreach ($authorInstitutions as $departaments) {
      if ($departaments->getAttribute('class') === 'authors') {
        $institution = $departaments->getElementsByTagName('span');
        $authorInstitutionNames = [];
        foreach ($institution as $index) {
          if ($index->getAttribute('title') !== '') {
            $authorInstitutionNames[] = $index->getAttribute('title');
          }
        }
        $authorInstitution[] = $authorInstitutionNames;
      }
    }
    print_r($authorInstitution);

    $reader = ReaderEntityFactory::createReaderFromFile('./assets/model.xlsx');

    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->setTempFolder('./assets');
    $writer->openToFile('./assets/model.xlsx');

    $style = (new StyleBuilder())
      ->setFontBold()
      ->build();

    $headerRow = WriterEntityFactory::createRowFromArray([
      'ID',
      'Title',
      'Type',
      'Author 1',
      'Author 1 Institution',
      'Author 2',
      'Author 2 Institution',
      'Author 3',
      'Author 3 Institution',
      'Author 4',
      'Author 4 Institution',
      'Author 5',
      'Author 5 Institution',
      'Author 6',
      'Author 6 Institution',
      'Author 7',
      'Author 7 Institution',
      'Author 8',
      'Author 8 Institution',
      'Author 9',
      'Author 9 Institution',
      'Author 10',
      'Author 10 Institution',
      'Author 11',
      'Author 11 Institution',
      'Author 12',
      'Author 12 Institution',
      'Author 13',
      'Author 13 Institution',
      'Author 14',
      'Author 14 Institution',
      'Author 15',
      'Author 15 Institution',
      'Author 16',
      'Author 16 Institution',
      'Author 17',
      'Author 17 Institution',
      'Author 18',
      'Author 18 Institution',
    ], $style);

    $writer->addRow($headerRow);

    for ($i = 0; $i < count($idArray); $i++) {
      $authors = $authorArray[$i] ?? [];
      $rowArray = [
        $idArray[$i],
        $titleArray[$i],
        $typeArray[$i],
      ];
      for ($j = 0; $j < count($authors); $j++) {
        $authorName = $authors[$j];
        $authorInstitutionName = $authorInstitution[$i][$j] ?? '';

        $rowArray[] = $authorName;
        $rowArray[] = $authorInstitutionName;
      }
      $row = WriterEntityFactory::createRowFromArray($rowArray);
      $writer->addRow($row);
    };
    $writer->close();
    // Write your logic to save the output file below.
    print_r($data);
  }

}
