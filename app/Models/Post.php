<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_title',
        'post_summary',
        'post_name',
        'post_content',
        'post_image',
        'post_image_alt',
        'post_hits',
        'post_author',
        'post_type',
        'post_status',
        'post_visibility',
        'post_mime_type',
        'post_guid',
        'post_image_meta',
        'meta_description',
        'meta_keyword'
    ];

    /**
     * @var mixed|string
     */
    private $meta_description;
    private $meta_keyword;
    private $post_author;
    private $post_content;
    private $post_guid;
    private $post_hits;
    private $post_image;
    private $post_image_alt;
    private $post_image_meta;
    private $post_name;
    private $post_mime_type;
    private $post_summary;
    private $post_status;
    private $post_title;
    private $post_type;

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'post_author');
    }

    /**
     * @return BelongsToMany
     */
    public function termtaxonomy()
    {
        return $this->belongsToMany('App\Models\TermTaxonomy','term_relationships','post_id','term_taxonomy_id')->withTimestamps();
    }

    /**
     * @return HasManyThrough
     */
    public function term()
    {
        return $this->hasManyThrough(
            'App\Models\Term',
            'App\Models\TermTaxonomy',
            'term_id',
            'id',
            'id',
            'id'
        );
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePostType($query)
    {
        return $query->wherePostType('post');
    }

    /**
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        return $query->wherePostType($type);
    }
}
