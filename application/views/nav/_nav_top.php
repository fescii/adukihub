<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="top-bar">
    <div class="container">
        <div class="col-sm-12">
            <div class="row">
                <ul class="top-menu top-menu-left">
                    <?php if (!empty($this->menu_links)):
                        foreach ($this->menu_links as $item): ?>
                            <?php if ($item->item_visibility == 1 && $item->item_location == "top"): ?>
                                <li><a href="<?php echo generate_menu_item_url($item); ?>"><?php echo html_escape($item->item_name); ?></a></li><?php endif;
                        endforeach;
                    endif; ?>
                </ul>
                <ul class="top-menu top-menu-right">
                    <?php if (check_user_permission('add_post')): ?>
                        <li>
                            <a href="#" data-toggle="modal" data-target="#modal_add_post"><?php echo trans("add_post"); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->auth_check): ?>
                        <li class="dropdown profile-dropdown">
                            <a class="dropdown-toggle a-profile" data-toggle="dropdown" href="#" aria-expanded="false">
                                <img src="<?php echo get_user_avatar($this->auth_user->avatar); ?>" alt="<?php echo html_escape($this->auth_user->username); ?>">
                                <?php echo html_escape($this->auth_user->username); ?>
                                <span class="icon-arrow-down"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (check_user_permission('admin_panel')): ?>
                                    <li>
                                        <a href="<?php echo admin_url(); ?>">
                                            <i class="icon-dashboard"></i>
                                            <?php echo ($this->auth_user->role == "admin" || $this->auth_user->role == "moderator") ? trans("admin_panel") : trans("dashboard"); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?php echo generate_profile_url($this->auth_user->slug); ?>">
                                        <i class="icon-user"></i>
                                        <?php echo trans("profile"); ?>
                                    </a>
                                </li>
                                <?php if ($this->auth_user->reward_system_enabled == 1): ?>
                                    <li>
                                        <a href="<?php echo generate_url('earnings'); ?>">
                                            <i class="icon-coin-bag"></i>
                                            <?php echo trans("earnings"); ?>
                                            (<strong class="lbl-earnings"><?= price_formatted($this->auth_user->balance); ?></strong>)
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?php echo generate_url('reading_list'); ?>">
                                        <i class="icon-star-o"></i>
                                        <?php echo trans("reading_list"); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo generate_url('settings'); ?>">
                                        <i class="icon-settings"></i>
                                        <?php echo trans("settings"); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo generate_url('logout'); ?>" class="logout">
                                        <i class="icon-logout-thin"></i>
                                        <?php echo trans("logout"); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <?php if ($this->general_settings->registration_system == 1): ?>
                            <li class="top-li-auth">
                                <a href="#" data-toggle="modal" data-target="#modal-login" class="btn_open_login_modal"><?php echo trans("login"); ?></a>
                                <span>/</span>
                                <a href="<?php echo generate_url('register'); ?>"><?php echo trans("register"); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($this->general_settings->multilingual_system == 1 && count($this->languages) > 1): ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="icon-language"></i>&nbsp;
                                <?php echo html_escape($this->selected_lang->name); ?> <span class="icon-arrow-down"></span>
                            </a>
                            <ul class="dropdown-menu lang-dropdown">
                                <?php
                                foreach ($this->languages as $language):
                                    $lang_url = base_url() . $language->short_form . "/";
                                    if ($language->id == $this->general_settings->site_lang) {
                                        $lang_url = base_url();
                                    } ?>
                                    <li>
                                        <a href="<?php echo $lang_url; ?>" class="<?php echo ($language->id == $this->selected_lang->id) ? 'selected' : ''; ?> ">
                                            <?php echo $language->name; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="li-dark-mode-sw">
                        <?php echo form_open('vr-switch-mode'); ?>
                        <?php if ($this->dark_mode == 1): ?>
                            <button type="submit" name="dark_mode" value="0" class="btn-switch-mode">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sun-fill" viewBox="0 0 16 16">
                                    <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                                </svg>
                            </button>
                        <?php else: ?>
                            <button type="submit" name="dark_mode" value="1" class="btn-switch-mode">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon-fill dark-mode-icon" viewBox="0 0 16 16">
                                    <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                                </svg>
                            </button>
                        <?php endif; ?>
                        <?= form_close(); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
