<?php

namespace Cms\Core\Services;

use Cms\Core\Models\Post;

class SeoHelper
{
    /**
     * Compile meta tags for the current page or post.
     */
    public function getMetaTags(?Post $post = null): array
    {
        $siteTitle = cms_option('site_title', 'LaraCMS');
        $siteDesc = cms_option('site_description', 'Just another LaraCMS site');

        if ($post) {
            $seoTitle = $post->getMeta('seo_title') ?: $post->title;
            $title = $seoTitle . ' - ' . $siteTitle;
            $description = $post->getMeta('seo_description') ?: ($post->excerpt ?: str($post->content)->stripTags()->words(25));
            $robots = $post->getMeta('seo_robots') ?: 'index, follow';
            $url = $post->permalink;

            $image = null;
            if ($post->featuredImage) {
                $image = $post->featuredImage->thumbnailUrl('large');
            } else {
                $image = cms_option('seo_fallback_image');
            }
        } else {
            $title = $siteTitle;
            $description = $siteDesc;
            $robots = 'index, follow';
            $url = url()->current();
            $image = cms_option('seo_fallback_image');
        }

        // Apply filters so plugins can alter SEO tags dynamically
        $meta = [
            'title' => $title,
            'description' => $description,
            'robots' => $robots,
            'keywords' => $post ? $post->getMeta('seo_keywords') : '',
            'url' => $url,
            'image' => $image,
            'og_type' => $post && $post->post_type === 'post' ? 'article' : 'website',
        ];

        return apply_filters('cms_seo_meta_tags', $meta, $post);
    }
}
