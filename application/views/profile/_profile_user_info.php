<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row-custom">
    <div class="profile-details">
        <div class="left">
            <img src="<?= get_user_avatar($user->avatar); ?>" alt="<?= html_escape($user->username); ?>" class="img-profile">
        </div>
        <div class="right">
            <div class="row-custom">
                <h1 class="username"><?= html_escape($user->username); ?></h1>
            </div>
            <div class="row-custom">
                <p class="p-last-seen">
                    <span class="last-seen <?= (is_user_online($user->last_seen)) ? 'last-seen-online' : ''; ?>"> <i class="icon-circle"></i> <?= trans("last_seen"); ?>&nbsp;<?= time_ago($user->last_seen); ?></span>
                </p>
            </div>

            <div class="row-custom">
                <p class="description">
                    <?= html_escape($user->about_me); ?>
                </p>
            </div>

            <div class="row-custom user-contact">
                <span class="info"><?= trans("member_since"); ?>&nbsp;<?= helper_date_format($user->created_at); ?></span>
                <?php if ($this->general_settings->show_user_email_on_profile == 1 && $user->show_email_on_profile == 1): ?>
                    <span class="info"><i class="icon-envelope"></i><?= html_escape($user->email); ?></span>
                <?php endif; ?>
            </div>

            <div class="row-custom profile-buttons">
                <?php if ($this->auth_check): ?>
                    <?php if ($this->auth_user->id != $user->id): ?>
                        <!--form follow-->
                        <?= form_open('follow-unfollow-user', ['class' => 'form-inline']); ?>
                        <input type="hidden" name="profile_id" value="<?= $user->id; ?>">
                        <?php if (is_user_follows($user->id, $this->auth_user->id)): ?>
                            <button class="btn btn-md btn-custom btn-follow"><i class="icon-user-plus"></i><?= trans("unfollow"); ?></button>
                        <?php else: ?>
                            <button class="btn btn-md btn-custom btn-follow"><i class="icon-user-plus"></i><?= trans("follow"); ?></button>
                        <?php endif; ?>
                        <?= form_close(); ?>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn btn-md btn-custom btn-follow" data-toggle="modal" data-target="#modal-login"><i class="icon-user-plus"></i><?= trans("follow"); ?></button>
                <?php endif; ?>
                <div class="social">
                    <ul>
                        <?php if (!empty($user->facebook_url)): ?>
                            <li><a href="<?= html_escape($user->facebook_url); ?>" target="_blank"><i class="icon-facebook"></i></a></li>
                        <?php endif;
                        if (!empty($user->twitter_url)): ?>
                            <li><a href="<?= html_escape($user->twitter_url); ?>" target="_blank"><i class="icon-twitter"></i></a></li>
                        <?php endif;
                        if (!empty($user->instagram_url)): ?>
                            <li><a href="<?= html_escape($user->instagram_url); ?>" target="_blank"><i class="icon-instagram"></i></a></li>
                        <?php endif;
                        if (!empty($user->pinterest_url)): ?>
                            <li><a href="<?= html_escape($user->pinterest_url); ?>" target="_blank"><i class="icon-pinterest"></i></a></li>
                        <?php endif;
                        if (!empty($user->linkedin_url)): ?>
                            <li><a href="<?= html_escape($user->linkedin_url); ?>" target="_blank"><i class="icon-linkedin"></i></a></li>
                        <?php endif;
                        if (!empty($user->vk_url)): ?>
                            <li><a href="<?= html_escape($user->vk_url); ?>" target="_blank"><i class="icon-vk"></i></a></li>
                        <?php endif;
                        if (!empty($user->telegram_url)): ?>
                            <li><a href="<?= html_escape($user->telegram_url); ?>" target="_blank"><i class="icon-telegram"></i></a></li>
                        <?php endif;
                        if (!empty($user->youtube_url)): ?>
                            <li><a href="<?= html_escape($user->youtube_url); ?>" target="_blank"><i class="icon-youtube"></i></a></li>
                        <?php endif;
                        if ($user->show_rss_feeds && $user_posts_count > 0): ?>
                            <li><a href="<?= lang_base_url() . "rss/author/" . $user->slug; ?>"><i class="icon-rss"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>