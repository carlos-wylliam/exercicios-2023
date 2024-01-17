<?php

namespace Chuva\Php\WebScrapping;

use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

error_reporting(E_ERROR | E_PARSE);

/**
 * Runner for the Webscraping exercise.
 */
class Main
{

    /**
     * Main runner, instantiates a Scrapper and runs.
     */
    public static function run(): void
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

        $data = (new Scrapper())->scrap($dom);

        $reader = ReaderEntityFactory::createReaderFromFile('./assets/model.xlsx');
    
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile('./assets/model.xlsx');
    
        $header = ['Id', 'Titulo', 'Tipo',
        'Autor 1', 'Autor 1 Instituição', 'Autor 2', 'Autor 2 Instituição', 'Autor 3', 'Autor 3 Instituicao',
        'Autor 4', 'Autor 4 Instituição', 'Autor 5', 'Autor 5 Instituição', 'Autor 6', 'Autor 6 Instituição',
        'Autor 7', 'Autor 7 Instituição', 'Autor 8', 'Autor 8 Instituição', 'Autor 9', 'Autor 9 Instituição',
        'Autor 10', 'Autor 10 Instituição', 'Autor 11', 'Autor 11 Instituição', 'Autor 12', 'Autor 12 Instituição',
        'Autor 13', 'Autor 13 Instituição', 'Autor 14', 'Autor 14 Instituição', 'Autor 15', 'Autor 15 Instituição',
        'Autor 16', 'Autor 16 Instituição', 'Autor 17', 'Autor 17 Instituição', 'Autor 18', 'Autor 18 Instituição',
        ];
        $headerRow = WriterEntityFactory::createRowFromArray($header);
    
        $writer->addRow($headerRow);

        foreach ($data as $paper) {
            $row = WriterEntityFactory::createRowFromArray(
                [
                $paper->id,
                $paper->title,
                $paper->type,
                ]
            );

            foreach ($paper->authors as $i => $author) {
                $row->addCell(WriterEntityFactory::createCell($author->name));
                $row->addCell(WriterEntityFactory::createCell($author->institution));
            }

            $writer->addRow($row);
        }
        $writer->close();
        print_r($data);
        // // Write your logic to save the output file below.
    }

}
