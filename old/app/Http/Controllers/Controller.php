<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Agent;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $orders
     * @param $table
     * @param $field
     * @return mixed
     */
    protected function orderReArrange($orders, $table, $field)
    {
        $cases = [];
        $ids = [];

        foreach ($orders as $order => $id) {
            $cases[] = "WHEN $id then $order";
            $ids[] = $id;
        }

        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);
        return \DB::update("UPDATE `{$table}` SET `{$field}` = CASE `id` {$cases} END WHERE `id` in ({$ids})");
    }

    /**
     * Get Device info with Browser
     *
     * @return void
     */
    public function getDeviceInfo($browser = true)
    {
        return $browser ? Agent::platform() . ' ' . $this->getBrowserName() : Agent::platform();
    }

    /**
     * Get Browser Name
     *
     * @param boolean $version
     * @return void
     */
    public function getBrowserName($withVersion = true)
    {
        $browserName = Agent::browser();
        return $withVersion ? $browserName . ' ' . (int) Agent::version($browserName) : $browserName;
    }
}
