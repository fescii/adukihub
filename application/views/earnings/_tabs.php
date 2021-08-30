<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="profile-tabs">
    <ul class="nav">
        <li class="nav-item <?php echo ($active_tab == 'earnings') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url('earnings'); ?>">
                <span><?php echo trans("earnings"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'payouts') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url('payouts'); ?>">
                <span><?php echo trans("payouts"); ?></span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'set_payout_account') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("set_payout_account"); ?>">
                <span><?php echo trans("set_payout_account"); ?></span>
            </a>
        </li>
    </ul>
</div>