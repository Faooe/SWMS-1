<?php

namespace App\Support\Xlsx;

use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

/**
 * Sama seperti XlsxWriter, tapi mendukung BEBERAPA sheet sekaligus dalam
 * satu file .xlsx -- dipakai untuk laporan yang perlu sheet "Ringkasan"
 * terpisah dari sheet "Detail" (mis. EmployeePerformanceController).
 *
 * XlsxWriter (versi 1 sheet) SENGAJA dibiarkan apa adanya & tidak
 * dihapus/diganti supaya export yang sudah ada (AttendanceExport) tidak
 * ikut berubah perilakunya.
 *
 * Format tiap sheet: ['title' => string, 'headings' => array, 'rows' => array[]]
 */
class MultiSheetXlsxWriter
{
    public function __construct(
        private array $sheets,
    ) {
    }

    public static function make(array $sheets): self
    {
        return new self($sheets);
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

        $count = count($this->sheets);

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml($count));
        $zip->addFromString('_rels/.rels', $this->relsXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml($count));
        $zip->addFromString('xl/styles.xml', $this->stylesXml());

        foreach ($this->sheets as $index => $sheet) {
            $sheetNumber = $index + 1;
            $zip->addFromString(
                "xl/worksheets/sheet{$sheetNumber}.xml",
                $this->sheetXml($sheet['headings'] ?? [], $sheet['rows'] ?? [])
            );
        }

        $zip->close();

        return $tmpPath;
    }

    private function contentTypesXml(int $count): string
    {
        $overrides = '';
        for ($i = 1; $i <= $count; $i++) {
            $overrides .= "<Override PartName=\"/xl/worksheets/sheet{$i}.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml\"/>";
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
{$overrides}
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
        $sheetsXml = '';

        foreach ($this->sheets as $index => $sheet) {
            $sheetNumber = $index + 1;
            $title = $this->escape($this->sanitizeSheetName($sheet['title'] ?? "Sheet{$sheetNumber}"));
            // rId untuk tiap sheet dimulai dari rId2 -- rId1 dipakai
            // relationship ke styles.xml di workbookRelsXml().
            $rId = 'rId'.($sheetNumber + 1);
            $sheetsXml .= "<sheet name=\"{$title}\" sheetId=\"{$sheetNumber}\" r:id=\"{$rId}\"/>";
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
<sheets>
{$sheetsXml}
</sheets>
</workbook>
XML;
    }

    private function workbookRelsXml(int $count): string
    {
        // rId1 = styles.xml, rId2..rId(n+1) = tiap worksheet.
        $rels = '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';

        for ($i = 1; $i <= $count; $i++) {
            $rId = 'rId'.($i + 1);
            $rels .= "<Relationship Id=\"{$rId}\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet\" Target=\"worksheets/sheet{$i}.xml\"/>";
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
{$rels}
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

    private function sheetXml(array $headings, array $rows): string
    {
        $rowsXml = $this->rowToXml(1, $headings, bold: true);

        $rowIndex = 2;

        foreach ($rows as $row) {
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
