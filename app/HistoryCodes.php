<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryCodes extends Model
{
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class,'client_id');
    }

}
