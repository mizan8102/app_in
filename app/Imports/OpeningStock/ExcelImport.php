<?php

namespace App\Imports\OpeningStock;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExcelImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $result=[];
        $i=0;
        foreach ($rows as $row)
        {

                $result[$i]['name'] = $row[0];
//                $result[$i]['name'] = $row[0];
            $i++;

        }
    }
}
