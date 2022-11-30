<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdPlacement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'active', 'ad_id'
    ];

    /**
     * @return BelongsTo
     */
    public function ad() {
        return $this->belongsTo('App\Models\Advertisement', 'ad_id', 'id');
    }
}
