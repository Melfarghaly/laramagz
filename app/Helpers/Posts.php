<?php

namespace App\Helpers;

use App\Models\Post;
use App\Models\Term;
use App\Models\TermTaxonomy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Posts
{
    /**
     * @return mixed
     */
    public static function posts() {
        $query = Post::wherePostType('post')->wherePostStatus('publish');

        if (Auth::check()) {
            if (Auth::user()->hasRole('superadmin')) {
                return $query;
            } else {
                if (Auth::user()->can('read-private-post')) {
                    if (Auth::user()->hasRole('admin')) {
                        return $query->where(function($query_post){
                            $posts = $query_post->get();
                            foreach($posts as $post) {
                                if (User::find($post->post_author)->getRoleNames()->first() == 'admin') {
                                    if (Post::where('post_author', Auth::id())->first()->user->roles->first()->name  == 'admin') {
                                        $query_post->where('post_visibility', 'public')
                                            ->orWhere(function($q) {
                                                $q->where('post_visibility', 'private')->where('post_author', Auth::id());
                                            });
                                    } else {
                                        $query_post->wherePostVisibility('public');;
                                    }
                                } else{
                                    $query_post->wherePostVisibility('public');;
                                }
                            }
                        });
                    }
                } else {
                    if(Auth::user()->hasRole(['member'])) {
                        return $query->where(function($query_post){
                            $posts = $query_post->get();
                            foreach($posts as $post) {
                                if (User::find($post->post_author)->getRoleNames()->first() == 'member') {
                                    $query_post->where('post_visibility', 'public')
                                    ->orWhere(function($q) {
                                        $q->where('post_visibility', 'private')->where('post_author', Auth::id());
                                    });
                                } else {
                                    $query_post->wherePostVisibility('public');
                                }
                            }
                        });
                    } else{
                        return $query->where('post_visibility', 'public')
                            ->orWhere(function($query_post) {
                                $query_post->where('post_visibility', 'private')
                                    ->where('post_author', Auth::id());
                            });
                    }
                }
            }
        } else {
            return $query->wherePostVisibility('public');
        }
    }

    /**
     * @return mixed
     */
    public static function postCount() {
        return Post::wherePostType('post')->count();
    }

    /**
     * @return mixed
     */
    public static function postCountAlt() {
        if(Auth::user()->hasRole(['superadmin'])) {
            $countPost = Post::ofType('post')->count();
        }else{
            if(Auth::user()->can('read-private-post')){

                $posts = Post::select('post_author')->ofType('post')
                    ->where('post_author', Auth::id())->get();

                foreach( $posts as $post ) {
                    if (User::findOrFail($post->post_author)->getRoleNames()->first() == 'admin') {
                        if ($post->user->roles->first()->name == 'admin') {
                            $countPost = Post::ofType('post')->where('post_visibility', 'public')
                                ->orWhere(function($query) {
                                    $query->where('post_visibility', 'private')->where('post_author', Auth::id());
                                })->count();
                        }else{
                            $countPost = Post::ofType('post')->count();
                        }
                    }
                }
            }else{
                $countPost = Post::ofType('post')
                    ->where('post_visibility', 'public')
                    ->orWhere(function($query) {
                        $query->where('post_visibility', 'private')->where('post_author', Auth::id());
                    })->count();
            }
        }

        return $countPost;
    }

    /**
     * @return mixed
     */
    public static function pageCount() {
        return Post::wherePostType('page')->count();
    }

    /**
     * @return mixed
     */
    public static function popularPosts() {
        return self::posts()->orderBy('post_hits', 'DESC');
    }

    /**
     * @return mixed
     */
    public static function recentPosts() {
        return self::posts()->latest();
    }

    /**
     * @return mixed
     */
    public static function lastWeekPosts() {
        return self::posts()->whereDate('created_at', '>', \Carbon\Carbon::now()->subWeek())->orderBy('post_hits', 'DESC');
    }

    /**
     * @param $post
     * @return mixed
     */
    public static function next($post) {
        return self::posts()->where('id', '>', $post->id);
    }

    /**
     * @param $post
     * @return mixed
     */
    public static function previous($post) {
        return self::posts()->where('id', '<', $post->id);
    }

    /**
     * @return mixed
     */
    public static function tagCount() {
        return TermTaxonomy::where('taxonomy', 'tag')->withCount('post')->orderBy('post_count', 'desc')->get();
    }

    /**
     * @param $query
     * @return string
     */
    public static function getLinkCategory($query) {
        if ($query->termtaxonomy()->where('taxonomy', 'category')->exists()) {
            return $query->termtaxonomy->where('taxonomy', 'category')->first()->term->slug;
        } else {
            return '';
        }
    }

    /**
     * @param $query
     * @return string
     */
    public static function getCategory($query) {
        if (!empty($query->termtaxonomy->where('taxonomy', 'category')->first()->term->name)) {
            return $query->termtaxonomy->where('taxonomy', 'category')->first()->term->name;
        } else {
            return '';
        }
    }

    /**
     * @param $label
     * @return mixed
     */
    public static function label($label) {
        $term_id = Term::whereSlug($label)->first()->id;
        $term_taxonomy_id = TermTaxonomy::whereTermId($term_id)->first()->id;
        $termTaxonomy = TermTaxonomy::find($term_taxonomy_id);
        return $termTaxonomy->post()->latest();
    }

    /**
     * @param $image
     * @return string
     */
    public static function postThumb($image) {
        $path = storage_path('app/public/images');
        $dimensionWidth = '640';

        $getFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $getFileExtension = pathinfo($image->getClientOriginalExtension(), PATHINFO_FILENAME);
        $fileName = $getFileName . '-' . Str::random(10) . '.' . $getFileExtension;
        $img = Image::make($image);
        $resizeImage = $img->resize($dimensionWidth, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->insert($resizeImage, 'center');
        $img->save($path . '/' . $fileName);

        return $fileName;
    }

    /**
     * @param $image
     * @return string
     */
    public static function getPostThumb($image) {
        if (empty($image)) {
            $file = file_get_contents(public_path('img/noimage.png'));
            $type = File::mimeType(public_path('img/noimage.png'));
        } else {
            $exists = Storage::disk('public')->exists('images/' . $image);
            if (!$exists) {
                $file = public_path('img/noimage.png');
                $type = File::mimeType($file);
            } else {
                $file = Storage::get('public/images/' . $image);
                $type = Storage::disk('public')->mimeType('images/' . $image);
            }
        }
        return 'data:' . $type . ';base64,' . base64_encode($file);
    }

    /**
     * @param $post_content
     * @param $post_image
     * @return string
     */
    public static function getImage($post_content, $post_image) {
        preg_match_all('/src="([^"]*)"/', $post_content, $result);

        if (!empty($post_image)) {
            if (!empty($post_image)){
                $image = route('post.image', $post_image);
            } else {
                $image = asset('img/noimage.png');
            }
        } else {
            if ($result[0]) {
                if(Storage::disk('public')->exists('images/' . last(explode('/', $result[1][0])))){
                    $image = route('post.image', last(explode('/', $result[1][0])));
                }else{
                    $image = asset('img/noimage.png');
                }
            } else {
                $image = asset('img/noimage.png');
            }
        }
        return $image;
    }
}
