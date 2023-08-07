<?php

namespace App\Exports\OpeningStock;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OpeningStockExport implements FromCollection,WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = collect($data);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data['item_row']);
    }
    public function headings(): array
    {
        return [
            'opening_bal_qty',
            'uom_id',
            'item_information_id',
            'opening_bal_amount',
            'opening_bal_rate',
        ];
    }
}