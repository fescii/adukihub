<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $social_array = get_social_links_array($this->settings);
$i = 0; ?>
<script type="application/ld+json">[{
        "@context": "http://schema.org",
        "@type": "Organization",
        "url": "<?= base_url(); ?>",
        "logo": {"@type": "ImageObject","width": 190,"height": 60,"url": "<?= get_logo($this->visual_settings); ?>"}<?= !empty($social_array) ? ',' : ''; ?>

<?php if (!empty($social_array) && item_count($social_array)): ?>
        "sameAs": [<?php foreach ($social_array as $item):if (isset($item['url'])): ?><?= $i != 0 ? ',' : ''; ?>"<?= $item['url']; ?>"<?php endif;
        $i++;endforeach; ?>]
<?php endif; ?>
    },
    {
        "@context": "http://schema.org",
        "@type": "WebSite",
        "url": "<?= base_url(); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?= base_url(); ?>search?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }]
    </script>
<?php if (!empty($post)):
    $date_modified = $post->updated_at;
    if (empty($date_modified)) {
        $date_modified = $post->created_at;
    }
if ($post->post_type == "video"):?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "VideoObject",
            "name": "<?= html_escape($post->title); ?>",
            "description": "<?= html_escape($post->summary); ?>",
            "thumbnailUrl": [
                "<?= get_post_image($post, "big"); ?>"
              ],
            "uploadDate": "<?= date(DATE_ISO8601, strtotime($post->created_at)); ?>",
            "contentUrl": "<?= $post->video_url; ?>",
            "embedUrl": "<?= $post->video_embed_code; ?>"
        }
    </script>
<?php else: ?>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "NewsArticle",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?= generate_post_url($post); ?>"
            },
            "headline": "<?= html_escape($post->title); ?>",
            "name": "<?= html_escape($post->title); ?>",
            "articleSection": "<?= $post->category_name; ?>",
            "image": {
                "@type": "ImageObject",
                "url": "<?= get_post_image($post, "big"); ?>",
                "width": 750,
                "height": 500
            },
            "datePublished": "<?= date(DATE_ISO8601, strtotime($post->created_at)); ?>",
            "dateModified": "<?= date(DATE_ISO8601, strtotime($date_modified)); ?>",
            "inLanguage": "<?= $this->selected_lang->language_code; ?>",
            "keywords": "<?= $post->keywords; ?>",
            "author": {
                "@type": "Person",
                "name": "<?= $post->author_username; ?>"
            },
            "publisher": {
                "@type": "Organization",
                "name": "<?= $this->settings->application_name; ?>",
                "logo": {
                "@type": "ImageObject",
                "width": 190,
                "height": 60,
                "url": "<?= get_logo($this->visual_settings); ?>"
                }
            },
            "description": "<?= html_escape($post->summary); ?>"
        }
    </script>
    <?php endif; ?>
<?php endif; ?>
<?php if (!empty($category)):
    if (!empty($parent_category)):?>
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
            "@type": "ListItem",
            "position": 1,
            "name": "<?= html_escape($parent_category->name); ?>",
            "item": "<?= generate_category_url($parent_category); ?>"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "<?= html_escape($category->name); ?>",
            "item": "<?= generate_category_url($category); ?>"
        }]
    }
    </script>
    <?php else: ?>
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "name": "<?= html_escape($category->name); ?>",
                "item": "<?= generate_category_url($category); ?>"
            }]
        }
    </script>
    <?php endif; ?>
<?php endif; ?>
