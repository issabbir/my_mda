<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 5/19/20
 * Time: 4:43 PM
 */

namespace App\Managers\Secdbms\Watchman;


use App\Contracts\Secdbms\Watchman\AgencyContract;
use App\Entities\Secdbms\Watchman\LAgency;
use Illuminate\Support\Facades\DB;

class AgencyManager implements AgencyContract
{
    private $agency;

    public function __construct(LAgency $agency)
    {
        $this->agency = $agency;
    }

    public function findAgenciesBy($searchTerm)
    {
        return $this->agency->where(
            [
                [DB::raw('LOWER(agency_name)'), 'like', strtolower('%'.trim($searchTerm).'%')],
            ]
        )->orderBy('agency_name', 'ASC')->limit(10)->get(['agency_id', 'agency_name']);
    }
}