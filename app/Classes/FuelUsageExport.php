<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 16/03/21
 * Time: 12:13
 */

namespace App\Classes;

use App\Models\Receipt;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class FuelUsageExport implements FromView
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('sales_report.fuel_usage_excel_format',
            [
                'data' => $this->data
            ]
        );
    }
}
