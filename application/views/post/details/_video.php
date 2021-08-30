<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<link href="<?php echo base_url(); ?>assets/vendor/plyr/plyr.css" rel="stylesheet"/>
<div class="show-on-page-load">
    <?php if (!empty($post->video_path)):
        $video_base_url = base_url();
        if ($post->video_storage == "aws_s3") {
            $video_base_url = $this->aws_base_url;
        } ?>
        <div class="video-player">
            <video id="player" playsinline controls>
                <source src="<?= $video_base_url . $post->video_path; ?>" type="video/mp4">
                <source src="<?= $video_base_url . $post->video_path; ?>" type="video/webm">
            </video>
        </div>
    <?php elseif (strpos($post->video_url, 'www.facebook.com') !== false): ?>
        <div class="post-image post-video">
            <!-- Load Facebook SDK for JavaScript -->
            <div id="fb-root"></div>
            <script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>
            <div class="fb-video"
                 data-href="<?php echo $post->video_url; ?>"
                 data-allowfullscreen="true"
                 data-autoplay="false"
                 data-show-captions="true"></div>
        </div>
    <?php elseif (!empty($post->video_embed_code)): ?>
        <div class="post-image post-video">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="<?php echo $post->video_embed_code; ?>" allowfullscreen></iframe>
            </div>
        </div>
    <?php endif; ?>
</div>