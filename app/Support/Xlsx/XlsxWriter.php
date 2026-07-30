<?php

namespace App\Support\Xlsx;

use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class XlsxWriter
{
    public function __construct(
        private string $sheetTitle,
        private array $headings,
        private array $rows,
    ) {
    }

    public static function make(string $sheetTitle, array $headings, array $rows): self
    {
        return new self($sheetTitle, $headings, $rows);
    }

    public function download(string $filename): StreamedResponse
    {
        $tmpPath = $this->buildFile();

        return response()->streamDownload(function () use ($tmpPath) {
            readfile($tmpPath);
            @unlink($tmpPath);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function buildFile(): string
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'xlsx_');

        $zip = new ZipArchive();
        $zip->open($tmpPath, ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->relsXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml());
        $zip->addFromString('xl/styles.xml', $this->stylesXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->sheetXml());

        $zip->close();

        return $tmpPath;
    }

    private function contentTypesXml(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>
XML;
    }

    private function relsXml(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>
XML;
    }

    private function workbookXml(): string
    {
        $title = $this->escape($this->sanitizeSheetName($this->sheetTitle));

        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
<sheets>
<sheet name="{$title}" sheetId="1" r:id="rId1"/>
</sheets>
</workbook>
XML;
    }

    private function workbookRelsXml(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>
XML;
    }

    private function stylesXml(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
<fonts count="2">
<font><sz val="11"/><name val="Calibri"/></font>
<font><sz val="11"/><name val="Calibri"/><b/></font>
</fonts>
<fills count="1"><fill><patternFill patternType="none"/></fill></fills>
<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>
<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>
<cellXfs count="2">
<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>
</cellXfs>
</styleSheet>
XML;
    }

    private function sheetXml(): string
    {
        $rowsXml = $this->rowToXml(1, $this->headings, bold: true);

        $rowIndex = 2;

        foreach ($this->rows as $row) {
            $rowsXml .= $this->rowToXml($rowIndex, $row);
            $rowIndex++;
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
<sheetData>
{$rowsXml}
</sheetData>
</worksheet>
XML;
    }

    private function rowToXml(int $rowIndex, array $cells, bool $bold = false): string
    {
        $cellsXml = '';
        $style = $bold ? ' s="1"' : '';

        foreach (array_values($cells) as $colIndex => $value) {
            $ref = $this->columnLetter($colIndex).$rowIndex;

            if ($value === null || $value === '') {
                $cellsXml .= "<c r=\"{$ref}\"{$style}/>";
            } elseif (is_int($value) || is_float($value)) {
                $cellsXml .= "<c r=\"{$ref}\"{$style}><v>{$value}</v></c>";
            } else {
                $text = $this->escape((string) $value);
                $cellsXml .= "<c r=\"{$ref}\" t=\"inlineStr\"{$style}><is><t xml:space=\"preserve\">{$text}</t></is></c>";
            }
        }

        return "<row r=\"{$rowIndex}\">{$cellsXml}</row>";
    }

    private function columnLetter(int $index): string
    {
        $letter = '';
        $index++;

        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)).$letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function sanitizeSheetName(string $name): string
    {
        $name = preg_replace('/[\\\\\/\?\*\[\]:]/', ' ', $name) ?? $name;

        return mb_substr($name, 0, 31);
    }
}