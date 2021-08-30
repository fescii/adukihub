<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= $this->selected_lang->short_form ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?></title>
    <meta name="description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta name="keywords" content="<?= xss_clean($keywords); ?>"/>
    <meta name="author" content="<?= xss_clean($this->settings->application_name); ?>"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:site_name" content="<?= xss_clean($this->settings->application_name); ?>"/>
<?php if (isset($post_type)): ?>
    <meta property="og:type" content="<?= $og_type; ?>"/>
    <meta property="og:title" content="<?= $og_title; ?>"/>
    <meta property="og:description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta property="og:url" content="<?= $og_url; ?>"/>
    <meta property="og:image" content="<?= $og_image; ?>"/>
    <meta property="og:image:width" content="<?= $og_width; ?>"/>
    <meta property="og:image:height" content="<?= $og_height; ?>"/>
    <meta property="article:author" content="<?= $og_author; ?>"/>
    <meta property="fb:app_id" content="<?= $this->general_settings->facebook_app_id; ?>"/>
<?php foreach ($og_tags as $tag): ?>
    <meta property="article:tag" content="<?= xss_clean($tag->tag); ?>"/>
<?php endforeach; ?>
    <meta property="article:published_time" content="<?= $og_published_time; ?>"/>
    <meta property="article:modified_time" content="<?= $og_modified_time; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?= xss_clean($this->settings->application_name); ?>"/>
    <meta name="twitter:creator" content="@<?= xss_clean($og_creator); ?>"/>
    <meta name="twitter:title" content="<?= xss_clean($post->title); ?>"/>
    <meta name="twitter:description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta name="twitter:image" content="<?= $og_image; ?>"/>
<?php else: ?>
    <meta property="og:image" content="<?= get_logo($this->visual_settings); ?>"/>
    <meta property="og:image:width" content="240"/>
    <meta property="og:image:height" content="90"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="<?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?>"/>
    <meta property="og:description" content="<?= addslashes(xss_clean($description)); ?>"/>
    <meta property="og:url" content="<?= base_url(); ?>"/>
    <meta property="fb:app_id" content="<?= $this->general_settings->facebook_app_id; ?>"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@<?= xss_clean($this->settings->application_name); ?>"/>
    <meta name="twitter:title" content="<?= xss_clean($title); ?> - <?= xss_clean($this->settings->site_title); ?>"/>
    <meta name="twitter:description" content="<?= xss_clean($description); ?>"/>
<?php endif; ?>
<?php if ($this->general_settings->pwa_status == 1): ?>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<?= xss_clean($this->settings->application_name); ?>">
    <meta name="msapplication-TileImage" content="<?= base_url(); ?>assets/img/pwa/144x144.png">
    <meta name="msapplication-TileColor" content="#2F3BA2">
    <link rel="manifest" href="<?= base_url(); ?>manifest.json">
    <link rel="apple-touch-icon" href="<?= base_url(); ?>assets/img/pwa/144x144.png">
<?php endif; ?>
    <link rel="shortcut icon" type="image/png" href="<?= get_favicon($this->visual_settings); ?>"/>
    <link rel="canonical" href="<?= current_full_url(); ?>"/>
    <link rel="alternate" href="<?= current_full_url(); ?>" hreflang="<?= $this->selected_lang->language_code; ?>"/>
    <link href="<?= base_url(); ?>assets/vendor/font-icons/css/font-icon.min.css" rel="stylesheet"/>
    <?= !empty($this->fonts->primary_font_url) ? $this->fonts->primary_font_url : ''; ?>
    <?= !empty($this->fonts->secondary_font_url) ? $this->fonts->secondary_font_url : ''; ?>
    <?= !empty($this->fonts->tertiary_font_url) ? $this->fonts->tertiary_font_url : ''; ?>
    <link href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?= base_url(); ?>assets/css/style-1.9.min.css" rel="stylesheet"/>
    <link href="<?= base_url(); ?>assets/css/plugins-1.9.css" rel="stylesheet"/>
<?php if ($this->dark_mode == 1) : ?>
    <link href="<?= base_url(); ?>assets/css/dark-1.9.min.css" rel="stylesheet"/>
<?php endif; ?>
    <script>var rtl = false;</script>
<?php if ($this->selected_lang->text_direction == "rtl"): ?>
    <link href="<?= base_url(); ?>assets/css/rtl-1.9.min.css" rel="stylesheet"/>
    <script>var rtl = true;</script>
<?php endif; ?>
    <?php $this->load->view('partials/_css_js_header'); ?>
    <?= $this->general_settings->custom_css_codes; ?>
    <?= $this->general_settings->adsense_activation_code; ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->load->view('partials/_json_ld'); ?>
</head>
<body>
<header id="header">
    <?php $this->load->view('nav/_nav_top'); ?>
    <div class="logo-banner">
        <div class="container">
            <div class="col-sm-12">
                <div class="row">
                    <div class="left">
                        <a href="<?= lang_base_url(); ?>">
                            <img src="<?= $this->dark_mode == 1 ? get_logo_footer($this->visual_settings) : get_logo($this->visual_settings); ?>" alt="logo" class="logo" width="190" height="60">
                        </a>
                    </div>
                    <div class="right">
                        <div class="pull-right">
                            <!--Include banner-->
                            <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "header"]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/.container-->
    </div><!--/.top-bar-->
    <?php $this->load->view('nav/_nav_main'); ?>

    <div class="mobile-nav-container">
        <div class="nav-mobile-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="nav-mobile-header-container">
                        <div class="menu-icon">
                            <a href="javascript:void(0)" class="btn-open-mobile-nav"><i class="icon-menu"></i></a>
                        </div>
                        <div class="mobile-logo">
                            <a href="<?= lang_base_url(); ?>">
                                <img src="<?= $this->dark_mode == 1 ? get_logo_footer($this->visual_settings) : get_logo($this->visual_settings); ?>" alt="logo" class="logo" width="150" height="50">
                            </a>
                        </div>
                        <div class="mobile-search">
                            <a class="search-icon"><i class="icon-search"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
<div id="overlay_bg" class="overlay-bg"></div>

<div class="mobile-nav-search">
    <div class="search-form">
        <?= form_open(generate_url('search'), ['method' => 'get']); ?>
        <input type="text" name="q" maxlength="300" pattern=".*\S+.*"
               class="form-control form-input"
               placeholder="<?= trans("placeholder_search"); ?>" required>
        <button class="btn btn-default"><i class="icon-search"></i></button>
        <?= form_close(); ?>
    </div>
</div>
<?php $this->load->view('nav/_nav_mobile'); ?>
<?php $this->load->view('partials/_modals'); ?>


