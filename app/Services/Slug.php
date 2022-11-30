<?php

namespace App\Services;

use Illuminate\Support\Str;

use App\Models\Post;
use App\Models\Term;

class Slug
{
    /**
     * @param $title
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function createSlug($title, $id = 0)
    {
        $slug = Str::slug($title,'-');

        $allSlugs = $this->getRelatedSlugs($slug, $id);

        if (!$allSlugs->contains('post_name', $slug)) {
            return $slug;
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('post_name', $newSlug)) {
                return $newSlug;
            }
        }

        // throw new \Exception('Can not create a unique slug');
    }


    /**
     * @param $slug
     * @param int $id
     * @return mixed
     */
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Post::select('post_name')->where('post_name', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }


    /**
     * @param $title
     * @param int $id
     * @return string
     */
    public function createSlugTag($title, $id = 0)
    {
        $slug = Str::slug($title, '-');

        $allSlugs = $this->getRelatedSlugsTag($slug, $id);

        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
    }

    /**
     * @param $slug
     * @param int $id
     * @return mixed
     */
    protected function getRelatedSlugsTag($slug, $id = 0)
    {
        return Term::select('slug')
            ->whereHas('taxonomy', function ($query) {
                $query->where('taxonomy', 'tag');
            })
            ->where('slug', 'LIKE', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * @param $title
     * @param int $id
     * @return string
     */
    public function createSlugCategory($title, $id = 0)
    {
        $slug = Str::slug($title, '-');

        $allSlugs = $this->getRelatedSlugsCategory($slug, $id);

        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
    }

    /**
     * @param $slug
     * @param int $id
     * @return mixed
     */
    protected function getRelatedSlugsCategory($slug, $id = 0)
    {
        return Term::select('slug')
            ->whereHas('taxonomy', function ($query) {
                $query->where('taxonomy', 'category');
            })
            ->where('slug', 'LIKE', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @param  string|null  $language
     * @return string
     */
    public static function slug($title, $separator = '-', $language = 'en')
    {
        $title = $language ? Str::ascii($title, $language) : $title;

        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';

        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Replace @ with the word 'at'
        $title = str_replace('@', $separator.'at'.$separator, $title);

        //Replace dot with separator
        $title = str_replace('.', $separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', Str::lower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }
}
