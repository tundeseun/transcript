<?php
// app/Imports/ResultOldImport.php
namespace App\Imports;

use App\Models\ResultOld;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class ResultOldImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
      // Update trans_detail_new where matric = matno
        DB::table('trans_details_new')
            ->where('matric', (string)$row['matno'])
            ->update(['status' => 2]);
            // Save to result_olds table
        return new ResultOld([
            'matno'  => $row['matno'],
            'code'   => $row['code'],
            'status' => $row['status'],
            'score'  => $row['score'],
            'wa'     => $row['wa'],
            'sec'    => $row['sec'],
            'dept'   => $row['dept'],
        ]);
    }
}


?>
