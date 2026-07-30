<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * Re-exports the rows that failed during a bulk member import, with the
 * original spreadsheet data plus the row number and failure reason, so
 * the admin can fix just those rows and re-upload them.
 */
class BulkImportFailuresExport implements FromArray, WithHeadings
{
    private array $failures;
    private array $headings;

    public function __construct(array $failures)
    {
        $this->failures = $failures;
        $this->headings = $failures === [] ? [] : array_keys($failures[0]['data']);
    }

    public function headings(): array
    {
        return array_merge(['Row', 'Error Reason'], array_map(
            fn ($key) => ucwords(str_replace('_', ' ', $key)),
            $this->headings
        ));
    }

    public function array(): array
    {
        return array_map(function ($failure) {
            $row = [$failure['row'], $failure['reason']];
            foreach ($this->headings as $key) {
                $row[] = $failure['data'][$key] ?? '';
            }
            return $row;
        }, $this->failures);
    }
}
