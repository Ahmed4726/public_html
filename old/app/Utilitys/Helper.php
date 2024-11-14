<?php

namespace App\Utilitys;

use App\GlobalStatus;
use App\StudentEmailApply;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Helper
{
    /**
     * Get Global Status Row Depends on Model and Status Name
     *
     * @param [type] $model
     * @param [type] $status
     * @return object
     */
    public function allStatus($model, $status = false)
    {
        $query = GlobalStatus::whereModel($model);
        return ($status) ? $query->whereName($status)->first() : $query->get();
    }

    /**
    * Generate Username for email
    *
    * @param [type] $name
    * @param [type] $session
    * @param integer $counter
    * @param integer $increment
    * @return void
    */
    public function userNameGenerator($name, $program, $session, $counter = 1, $increment = 2)
    {
        $basename = Str::of($name)->lower()->replace(' ', '')->replace('.', '');
        (!$program) ?: $basename .= ".$program";
        $basename .= Str::of($session)->substr(0, 4);

        ($counter == 1) ? $username[] = $basename : '';

        $loop = $counter + $increment;
        while ($counter <= $loop) {
            $username[] = $basename . $counter;
            $counter++;
        }

        $existingUsername = StudentEmailApply::whereIn('username', $username)->pluck('username')->all();
        $availableUsername = array_diff($username, $existingUsername);

        return (!empty($availableUsername)) ? current($availableUsername) : $this->userNameGenerator($name, $program, $session, $counter);
    }

    /**
     * Datepicker date range to array.
     *
     * @param string $range
     * @return array
     */
    public function dateRangeTextToArray($rangeText)
    {
        [$from, $to] = explode(' to ', $rangeText);
        $from = Carbon::parse(trim($from))->startOfDay();
        $to = Carbon::parse(trim($to))->endOfDay();

        return [$from, $to];
    }
}
