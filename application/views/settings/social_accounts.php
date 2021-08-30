<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= lang_base_url(); ?>"><?= trans("home"); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= generate_url('settings'); ?>"><?= trans("settings"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $title; ?></li>
                    </ol>
                </nav>
                <h1 class="page-title"><?= trans("settings"); ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="row-custom">
                    <?php $this->load->view("settings/_setting_tabs"); ?>
                </div>
            </div>
            <div class="col-sm-12 col-md-9">
                <div class="row-custom">
                    <div class="profile-tab-content">
                        <?php $this->load->view('partials/_messages'); ?>
                        <?= form_open("social-accounts-post"); ?>
                        <div class="form-group">
                            <label class="control-label">Facebook <?= trans('url'); ?></label>
                            <input type="text" class="form-control form-input" name="facebook_url"
                                   placeholder="Facebook <?= trans('url'); ?>" value="<?= html_escape($user->facebook_url); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Twitter <?= trans('url'); ?></label>
                            <input type="text" class="form-control form-input"
                                   name="twitter_url" placeholder="Twitter <?= trans('url'); ?>"
                                   value="<?= html_escape($user->twitter_url); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Instagram <?= trans('url'); ?></label>
                            <input type="text" class="form-control form-input" name="instagram_url" placeholder="Instagram <?= trans('url'); ?>"
                                   value="<?= html_escape($user->instagram_url); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Pinterest <?= trans('url'); ?></label>
                            <input type="text" class="form-control form-input" name="pinterest_url" placeholder="Pinterest <?= trans('url'); ?>"
                                   value="<?= html_escape($user->pinterest_url); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Linkedin <?= trans('url'); ?></label>
                            <input type="text" class="form-control form-input" name="linkedin_url" placeholder="Linkedin <?= trans('url'); ?>"
                                   value="<?= html_escape($user->linkedin_url); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label class="control-label">VK <?= trans('url'); ?></label>
                            <input type="text" class="form-control form-input" name="vk_url"
                                   placeholder="VK <?= trans('url'); ?>" value="<?= html_escape($user->vk_url); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Telegram <?= trans('url'); ?></label>
                            <input type="text" class="form-control form-input" name="telegram_url"
                                   placeholder="Telegram <?= trans('url'); ?>" value="<?= html_escape($user->telegram_url); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Youtube <?= trans('url'); ?></label>
                            <input type="text" class="form-control form-input" name="youtube_url"
                                   placeholder="Youtube <?= trans('url'); ?>" value="<?= html_escape($user->youtube_url); ?>" <?= ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                        </div>
                        <button type="submit" class="btn btn-md btn-custom"><?= trans("save_changes") ?></button>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

