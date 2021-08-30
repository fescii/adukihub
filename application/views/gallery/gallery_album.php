<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="main">
    <div class="container">
        <div class="row">
            <!-- breadcrumb -->
            <?php if ($page->breadcrumb_active == 1): ?>
                <div class="col-sm-12 page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo lang_base_url(); ?>"><?php echo html_escape(trans("home")); ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo lang_base_url() . $page->slug; ?>"><?php echo html_escape($page->title); ?></a>
                        </li>
                        <li class="breadcrumb-item active"><?php echo html_escape($album->name); ?></li>
                    </ol>
                </div>
            <?php else: ?>
                <div class="col-sm-12 page-breadcrumb"></div>
            <?php endif; ?>

            <div class="col-sm-12">
                <div class="content page-about page-gallery">

                    <!--Check page title active-->
                    <?php if ($page->title_active == 1): ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <h1 class="page-title"><?php echo html_escape($page->title); ?></h1>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h2 class="gallery-category-title"><?php echo html_escape($album->name); ?></h2>
                        </div>
                    </div>
                    <?php if (!empty($gallery_categories)): ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="filters text-center">
                                    <label data-filter="" class="btn btn-primary active">
                                        <input type="radio" name="options"> <?php echo trans("all"); ?>
                                    </label>

                                    <?php foreach ($gallery_categories as $category): ?>
                                        <label data-filter="category_<?php echo $category->id; ?>" class="btn btn-primary">
                                            <input type="radio" name="options"> <?php echo $category->name; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row row-masonry">
                        <div id="masonry" class="gallery">
                            <!--Load Items-->
                            <?php foreach ($gallery_images as $item):
                                $img_base_url = base_url();
                                if ($item->storage == "aws_s3") {
                                    $img_base_url = $this->aws_base_url;
                                } ?>
                                <div data-filter="category_<?php echo $item->category_id; ?>" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 gallery-item">
                                    <div class="item-inner">
                                        <a class="image-popup lightbox" href="<?= $img_base_url . $item->path_big; ?>" data-effect="mfp-zoom-out" title="<?php echo html_escape($item->title); ?>">
                                            <img src="<?= $img_base_url . html_escape($item->path_small); ?>" alt="<?php echo html_escape($item->title); ?>" class="img-responsive"/>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>