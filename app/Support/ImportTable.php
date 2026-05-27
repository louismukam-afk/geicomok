<?php

namespace GEICOM\Support;

use ZipArchive;

class ImportTable
{
    public static function readRows($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['csv', 'txt'])) {
            return self::readCsvRows($file->getRealPath());
        }

        if ($extension === 'xlsx') {
            return self::readXlsxRows($file->getRealPath());
        }

        throw new \InvalidArgumentException('Format non supporte. Utilisez un fichier .xlsx ou .csv.');
    }

    public static function createXlsxTemplate($headers, $examples)
    {
        $path = tempnam(sys_get_temp_dir(), 'template_');
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml', self::contentTypesXml());
        $zip->addFromString('_rels/.rels', self::rootRelsXml());
        $zip->addFromString('xl/workbook.xml', self::workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', self::workbookRelsXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', self::sheetXml($headers, $examples));
        $zip->close();

        return $path;
    }

    private static function readCsvRows($path)
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            return [];
        }

        $firstLine = fgets($handle);
        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
        rewind($handle);

        $headers = fgetcsv($handle, 0, $delimiter);
        if (!$headers) {
            fclose($handle);
            return [];
        }

        $headers = self::normalizeHeaders($headers);
        $rows = [];
        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count(array_filter($data, 'strlen')) === 0) {
                continue;
            }

            $rows[] = self::combineRow($headers, $data);
        }

        fclose($handle);
        return $rows;
    }

    private static function readXlsxRows($path)
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        $sharedStrings = self::readSharedStrings($zip);
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetXml) {
            return [];
        }

        $xml = simplexml_load_string($sheetXml);
        if (!$xml || !isset($xml->sheetData->row)) {
            return [];
        }

        $table = [];
        foreach ($xml->sheetData->row as $row) {
            $values = [];
            foreach ($row->c as $cell) {
                $ref = (string) $cell['r'];
                $column = self::columnIndex($ref);
                $values[$column] = self::cellValue($cell, $sharedStrings);
            }

            if (count(array_filter($values, 'strlen')) === 0) {
                continue;
            }

            ksort($values);
            $table[] = $values;
        }

        if (count($table) === 0) {
            return [];
        }

        $headers = self::normalizeHeaders(array_values(array_shift($table)));
        $rows = [];
        foreach ($table as $data) {
            $rows[] = self::combineRow($headers, array_values($data));
        }

        return $rows;
    }

    private static function readSharedStrings($zip)
    {
        $xmlString = $zip->getFromName('xl/sharedStrings.xml');
        if (!$xmlString) {
            return [];
        }

        $xml = simplexml_load_string($xmlString);
        if (!$xml || !isset($xml->si)) {
            return [];
        }

        $strings = [];
        foreach ($xml->si as $item) {
            if (isset($item->t)) {
                $strings[] = (string) $item->t;
                continue;
            }

            $text = '';
            if (isset($item->r)) {
                foreach ($item->r as $run) {
                    $text .= (string) $run->t;
                }
            }
            $strings[] = $text;
        }

        return $strings;
    }

    private static function cellValue($cell, $sharedStrings)
    {
        $type = (string) $cell['t'];

        if ($type === 's') {
            $index = (int) $cell->v;
            return isset($sharedStrings[$index]) ? trim($sharedStrings[$index]) : '';
        }

        if ($type === 'inlineStr' && isset($cell->is->t)) {
            return trim((string) $cell->is->t);
        }

        return isset($cell->v) ? trim((string) $cell->v) : '';
    }

    private static function normalizeHeaders($headers)
    {
        return array_map(function ($header) {
            $header = trim($header);
            $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);
            return strtolower($header);
        }, $headers);
    }

    private static function combineRow($headers, $data)
    {
        $row = [];
        foreach ($headers as $index => $header) {
            $row[$header] = isset($data[$index]) ? trim($data[$index]) : null;
        }
        return $row;
    }

    private static function columnIndex($reference)
    {
        $letters = preg_replace('/[^A-Z]/', '', strtoupper($reference));
        $index = 0;
        for ($i = 0; $i < strlen($letters); $i++) {
            $index = $index * 26 + (ord($letters[$i]) - 64);
        }
        return $index - 1;
    }

    private static function contentTypesXml()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'</Types>';
    }

    private static function rootRelsXml()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
    }

    private static function workbookXml()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Import" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
    }

    private static function workbookRelsXml()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'</Relationships>';
    }

    private static function sheetXml($headers, $examples)
    {
        $rows = array_merge([$headers], $examples);
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';

        foreach ($rows as $rowIndex => $row) {
            $xml .= '<row r="'.($rowIndex + 1).'">';
            foreach (array_values($row) as $columnIndex => $value) {
                $cellRef = self::columnName($columnIndex).($rowIndex + 1);
                $xml .= '<c r="'.$cellRef.'" t="inlineStr"><is><t>'.htmlspecialchars((string) $value, ENT_XML1, 'UTF-8').'</t></is></c>';
            }
            $xml .= '</row>';
        }

        return $xml.'</sheetData></worksheet>';
    }

    private static function columnName($index)
    {
        $name = '';
        $index++;
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $name = chr(65 + $mod).$name;
            $index = (int) (($index - $mod) / 26);
        }
        return $name;
    }
}
