<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <!--News Ticker-->
<?php if ($this->general_settings->show_newsticker == 1 && count($breaking_news) > 0): ?>
    <div class="col-sm-12 news-ticker-cnt">
        <div class="row m-0">
            <div class="left">
                <span class="news-ticker-title font-second"><?php echo trans("breaking_news"); ?></span>
            </div>
            <div class="right">
                <div class="news-ticker">
                    <ul class="newsticker">
                        <?php foreach ($breaking_news as $post): ?>
                            <li>
                                <a href="<?php echo generate_post_url($post); ?>"<?php post_url_new_tab($this, $post); ?>>
                                    <?php echo html_escape($post->title); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="news-ticker-btn-cnt">
                <a href="javascript:void(0)" id="btn_newsticker_prev" class="bnt-news-ticker news-prev"><span class="icon-arrow-left"></span></a>
                <a href="javascript:void(0)" id="btn_newsticker_next" class="bnt-news-ticker news-next"><span class="icon-arrow-right"></span></a>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="col-sm-12 news-ticker-sep"></div>
<?php endif; ?>