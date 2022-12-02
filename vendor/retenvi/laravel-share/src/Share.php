<?php

namespace Retenvi\Share;

use League\Flysystem\Config;

class Share
{
    /**
     * The url of the page to share
     *
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $theme;

    /**
     * Optional text for Twitter
     * and Linkedin title
     *
     * @var string
     */
    protected $title;

    /**
     * Extra options for the share links
     *
     * @var array
     */
    protected $options = [];

    /**
     * Html to prefix before the share links
     *
     * @var string
     */
    protected $prefix = '<div id="social-links"><ul>';

    /**
     * Html to append after the share links
     *
     * @var string
     */
    protected $suffix = '</ul></div>';

    /**
     * The generated html
     *
     * @var string
     */
    protected $html = '';

    /**
     * Return a string with html at the end
     * of the chain.
     *
     * @return string
     */
    public function __toString()
    {
        $this->html = $this->prefix . $this->html;
        $this->html .= $this->suffix;

        return $this->html;
    }

    /**
     * @param $url
     * @param null $title
     * @param array $options
     * @param null $prefix
     * @param null $suffix
     * @return $this
     */
    public function page($url, $theme = null, $title = null, $options = [], $prefix = null, $suffix = null)
    {
        $this->url = $url;
        $this->theme = $theme;
        $this->title = $title;
        $this->options = $options;

        $this->setPrefixAndSuffix($prefix, $suffix);

        return $this;
    }

    /**
     * @param null $title
     * @param array $options
     * @param null $prefix
     * @param null $suffix
     * @return $this
     */
    public function currentPage($title = null, $options = [], $prefix = null, $suffix = null)
    {
        $url = request()->getUri();
        return $this->page($url, $theme, $title, $options, $prefix, $suffix);
    }

    /**
     * @param null $title
     * @param array $options
     * @param null $prefix
     * @param null $suffix
     * @return $this
     */
    public function sendTo($theme, $title = null, $options = [], $prefix = null, $suffix = null)
    {
        $url = request()->getUri();
        return $this->page($url, $theme, $title, $options, $prefix, $suffix);
    }

    /**
     * Facebook share link
     *
     * @return $this
     */
    public function facebook()
    {
        $url = config('laravel-share.services.facebook.uri') . $this->url;
        $theme = is_null($this->theme) ? null : $this->theme;
        $this->buildLink('facebook', $url, $theme);

        return $this;
    }

    /**
     * Twitter share link
     *
     * @return $this
     */
    public function twitter()
    {
        if (is_null($this->title)) {
            $this->title = config('laravel-share.services.twitter.text');
        }

        $base = config('laravel-share.services.twitter.uri');
        $url = $base . '?text=' . urlencode($this->title) . '&url=' . $this->url;
        $theme = is_null($this->theme) ? null : $this->theme;

        $this->buildLink('twitter', $url, $theme);

        return $this;
    }

    /**
     * Reddit share link
     *
     * @return $this
     */
    public function reddit()
    {
        if (is_null($this->title)) {
            $this->title = config('laravel-share.services.reddit.text');
        }

        $base = config('laravel-share.services.reddit.uri');
        $url = $base . '?title=' . urlencode($this->title) . '&url=' . $this->url;
        $theme = is_null($this->theme) ? null : $this->theme;

        $this->buildLink('reddit', $url, $theme);

        return $this;
    }

    /**
     * Telegram share link
     *
     * @return $this
     */
    public function telegram()
    {
        if (is_null($this->title)) {
            $this->title = config('laravel-share.services.telegram.text');
        }

        $base = config('laravel-share.services.telegram.uri');
        $url = $base . '?url=' . $this->url . '&text=' . urlencode($this->title);
        $theme = is_null($this->theme) ? null : $this->theme;

        $this->buildLink('telegram', $url, $theme);

        return $this;
    }

    /**
     * Whatsapp share link
     *
     * @return $this
     */
    public function whatsapp()
    {
        $url = config('laravel-share.services.whatsapp.uri') . $this->url;
        $theme = is_null($this->theme) ? null : $this->theme;
        $this->buildLink('whatsapp', $url, $theme);

        return $this;
    }

    /**
     * Linked in share link
     *
     * @param string $summary
     * @return $this
     */
    public function linkedin($summary = '')
    {
        $base = config('laravel-share.services.linkedin.uri');
        $mini = config('laravel-share.services.linkedin.extra.mini');
        $url = $base . '?mini=' . $mini . '&url=' . $this->url . '&title=' . urlencode($this->title) . '&summary=' . urlencode($summary);
        $theme = is_null($this->theme) ? null : $this->theme;
        $this->buildLink('linkedin', $url, $theme);

        return $this;
    }

    /**
     * Pinterest share link
     *
     * @return $this
     */
    public function pinterest()
    {
        $url = config('laravel-share.services.pinterest.uri') . $this->url;
        $theme = is_null($this->theme) ? null : $this->theme;
        $this->buildLink('pinterest', $url, $theme);

        return $this;
    }

    /**
     * Build a single link
     *
     * @param $provider
     * @param $url
     */
    protected function buildLink($provider, $url, $theme = null)
    {
        $theme = is_null($theme) ? config('laravel-share.theme', 'fa5') : $theme;
//        $theme = config('laravel-share.theme', 'fa5');

        $this->html .= trans("laravel-share::laravel-share-$theme.$provider",
            [
                'li_class' => key_exists('li_class', $this->options) ? $this->options['li_class'] : '',
                'url' => $url,
                'class' => key_exists('class', $this->options) ? $this->options['class'] : '',
                'id' => key_exists('id', $this->options) ? $this->options['id'] : '',
                'title' => key_exists('title', $this->options) ? $this->options['title'] : '',
                'icon' => key_exists('icon', $this->options) ? $this->options['icon'] : ''
            ]);
    }

    /**
     * Optionally Set custom prefix and/or suffix
     *
     * @param $prefix
     * @param $suffix
     */
    protected function setPrefixAndSuffix($prefix, $suffix)
    {
        if (!is_null($prefix)) {
            $this->prefix = $prefix;
        }

        if (!is_null($suffix)) {
            $this->suffix = $suffix;
        }
    }
}
