<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TermTaxonomy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term_id', 'taxonomy', 'description', 'parent'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'term_taxonomies';

    /**
     * Get the term record associated with the term_taxonomy.
     */
    public function term()
    {
        return $this->belongsTo('App\Models\Term');
    }

    /**
     * @return BelongsToMany
     */
    public function post()
    {
        return $this->belongsToMany('App\Models\Post', 'term_relationships','term_taxonomy_id','post_id')->withTimestamps();
    }
}
