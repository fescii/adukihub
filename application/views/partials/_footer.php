<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<footer id="footer">
    <div class="container">
        <div class="row footer-widgets">
            <!-- footer widget about-->
            <div class="col-sm-4 col-xs-12">
                <div class="footer-widget f-widget-about">
                    <div class="col-sm-12">
                        <div class="row">
                            <p class="footer-logo">
                                <img src="<?php echo get_logo_footer($this->visual_settings); ?>" alt="logo" class="logo" width="240" height="90">
                            </p>
                            <p>
                                <?php echo html_escape($this->settings->about_footer); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div><!-- /.col-sm-4 -->
            <!-- footer widget random posts-->
            <div class="col-sm-4 col-xs-12">
                <!--Include footer random posts partial-->
                <?php $this->load->view('partials/_footer_random_posts'); ?>
            </div><!-- /.col-sm-4 -->
            <!-- footer widget follow us-->
            <div class="col-sm-4 col-xs-12">
                <div class="col-sm-12 footer-widget f-widget-follow">
                    <div class="row">
                        <h4 class="title"><?php echo trans("footer_follow"); ?></h4>
                        <ul>
                            <!--Include social media links-->
                            <?php $this->load->view('partials/_social_media_links', ['rss_hide' => false]); ?>
                        </ul>
                    </div>
                </div>
                <?php if ($this->general_settings->newsletter_status == 1): ?>
                    <!-- newsletter -->
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="widget-newsletter">
                                <p><?= trans("footer_newsletter"); ?></p>
                                <form id="form_newsletter_footer" class="form-newsletter">
                                    <div class="newsletter">
                                        <input type="email" name="email" class="newsletter-input" maxlength="199" placeholder="<?php echo trans("placeholder_email"); ?>">
                                        <button type="submit" name="submit" value="form" class="newsletter-button"><?php echo trans("subscribe"); ?></button>
                                    </div>
                                    <input type="text" name="url">
                                    <div id="form_newsletter_response"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <!-- .col-md-3 -->
        </div>
        <!-- .row -->
        <!-- Copyright -->
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-bottom-left">
                        <p><?php echo html_escape($this->settings->copyright); ?></p>
                    </div>
                    <div class="footer-bottom-right">
                        <ul class="nav-footer">
                            <?php if (!empty($this->menu_links)):
                                foreach ($this->menu_links as $item):
                                    if ($item->item_visibility == 1 && $item->item_location == "footer"):?>
                                        <li>
                                            <a href="<?php echo generate_menu_item_url($item); ?>"><?php echo html_escape($item->item_name); ?> </a>
                                        </li>
                                    <?php endif;
                                endforeach;
                            endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- .row -->
        </div>
    </div>
</footer>

<a href="#" class="scrollup"><i class="icon-arrow-up"></i></a>

<script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins-1.8.js"></script>

<?php if (check_cron_time(3)): ?>
    <script>$.ajax({type: "POST", url: "<?php echo base_url(); ?>vr-run-internal-cron"});</script>
<?php endif; ?>
<?php if ($this->settings->cookies_warning && empty(helper_getcookie('cookies_warning'))): ?>
    <div class="cookies-warning">
        <div class="text"><?php echo $this->settings->cookies_warning_text; ?></div>
        <a href="javascript:void(0)" onclick="hide_cookies_warning();" class="icon-cl"> <i class="icon-close"></i></a>
    </div>
<?php endif; ?>
<script>
    var sys_lang_id = '<?php echo $this->selected_lang->id; ?>';$('<input>').attr({type: 'hidden', name: 'sys_lang_id', value: sys_lang_id}).appendTo('form');var base_url = "<?php echo base_url(); ?>";var fb_app_id = "<?php echo $this->general_settings->facebook_app_id; ?>";var csfr_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";var csfr_cookie_name = "<?php echo $this->config->item('csrf_cookie_name'); ?>";var is_recaptcha_enabled = false;var sweetalert_ok = "<?php echo trans("ok"); ?>";var sweetalert_cancel = "<?php echo trans("cancel"); ?>";<?php if ($this->recaptcha_status == true): ?>is_recaptcha_enabled = true;<?php endif; ?>
</script>
<script src="<?php echo base_url(); ?>assets/js/script-1.9.min.js"></script>

<?php if ($this->general_settings->pwa_status == 1): ?>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('<?= base_url();?>pwa-sw.js').then(function (registration) {
                }, function (err) {
                    console.log('ServiceWorker registration failed: ', err);
                }).catch(function (err) {
                    console.log(err);
                });
            });
        } else {
            console.log('service worker is not supported');
        }
    </script>
<?php endif; ?>
<?php if(!$this->auth_check && $this->general_settings->newsletter_status == 1 && $this->general_settings->newsletter_popup == 1 && empty(helper_getcookie('vr_news_p'))): ?>
    <script>$(window).on('load', function () {$('#modal_newsletter').modal('show');});</script>
<?php endif; ?>
<?php echo $this->general_settings->google_analytics; ?>
<?php echo $this->general_settings->custom_javascript_codes; ?>
<?php $this->load->view('partials/_js_footer'); ?>

</body>
</html>