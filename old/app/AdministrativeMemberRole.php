<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdministrativeMemberRole extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param $memberID
     * @param array $roles
     */
    public static function roleModify($memberID, $roles = array())
    {
        self::whereMemberId($memberID)->whereNotIn('role_id', $roles)->delete();

        foreach ($roles as $role){
            self::firstOrCreate([
                'member_id' => $memberID,
                'role_id' => $role,

            ]);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(AdministrativeMember::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(AdministrativeRole::class);
    }
}
