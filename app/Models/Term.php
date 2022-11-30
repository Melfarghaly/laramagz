<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];

    /**
     * @return HasOne
     */
    public function taxonomy()
    {
        return $this->hasOne('App\Models\TermTaxonomy');
    }

    /**
     * @param $query
     */
    public function scopeCategory($query)
    {
        $query->whereHas('taxonomy', function($q) {
            $q->where('taxonomy', 'category');
        });
    }

    /**
     * @param $query
     */
    public function scopeTag($query)
    {
        $query->whereHas('taxonomy', function($q) {
            $q->where('taxonomy', 'tag');
        });
    }

    /**
     * @param $query
     * @param $name
     */
    public function scopeOfName($query, $name)
    {
        $query->where('name', $name);
    }

    /**
     * @param $query
     * @param $name
     */
    public function scopeSearchName($query, $name)
    {
        $query->where("name", "LIKE", "%$name%");
    }
}
