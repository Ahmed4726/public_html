<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LegalCertificateType extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($type) {
            // foreach (LegalCertificate::whereTypeId($type->id)->get() as $certificate) {
            //     $certificate->delete();
            // }
        });
    }
}
