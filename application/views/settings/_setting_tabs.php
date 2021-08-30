<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="profile-tabs">
    <ul class="nav">
        <li class="nav-item <?php echo ($active_tab == 'update_profile') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url('settings'); ?>">
                <span><?php echo trans("update_profile"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'social_accounts') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url('settings', 'social_accounts'); ?>">
                <span><?php echo trans("social_accounts"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'preferences') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url('settings', 'preferences'); ?>">
                <span><?php echo trans("preferences"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'change_password') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url('settings', 'change_password'); ?>">
                <span><?php echo trans("change_password"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'delete_account') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url('settings', 'delete_account'); ?>">
                <span><?php echo trans("delete_account"); ?></span>
            </a>
        </li>
    </ul>
</div>