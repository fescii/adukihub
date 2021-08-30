<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row table-filter-container">
    <div class="col-sm-12">
        <?php echo form_open(admin_url() . 'users', ['method' => 'GET']); ?>

        <div class="item-table-filter" style="width: 80px; min-width: 80px;">
            <label><?php echo trans("show"); ?></label>
            <select name="show" class="form-control">
                <option value="15" <?php echo ($this->input->get('show', true) == '15') ? 'selected' : ''; ?>>15</option>
                <option value="30" <?php echo ($this->input->get('show', true) == '30') ? 'selected' : ''; ?>>30</option>
                <option value="60" <?php echo ($this->input->get('show', true) == '60') ? 'selected' : ''; ?>>60</option>
                <option value="100" <?php echo ($this->input->get('show', true) == '100') ? 'selected' : ''; ?>>100</option>
            </select>
        </div>

        <div class="item-table-filter">
            <label><?php echo trans("status"); ?></label>
            <select name="status" class="form-control">
                <option value=""><?php echo trans("all"); ?></option>
                <option value="1" <?php echo ($this->input->get('status', true) == 1) ? 'selected' : ''; ?>><?php echo trans("active"); ?></option>
                <option value="0" <?php echo $this->input->get('status', true) != null && $this->input->get('status', true) != 1 ? 'selected' : ''; ?>><?php echo trans("banned"); ?></option>
            </select>
        </div>

        <div class="item-table-filter">
            <label><?php echo trans("role"); ?></label>
            <select name="role" class="form-control">
                <option value=""><?php echo trans("all"); ?></option>
                <option value="moderator" <?php echo ($this->input->get('role', true) == 'moderator') ? 'selected' : ''; ?>><?php echo @$this->auth_model->get_role_by_key('moderator')->role_name; ?></option>
                <option value="author" <?php echo ($this->input->get('role', true) == 'author') ? 'selected' : ''; ?>><?php echo @$this->auth_model->get_role_by_key('author')->role_name; ?></option>
                <option value="user" <?php echo ($this->input->get('role', true) == 'user') ? 'selected' : ''; ?>><?php echo @$this->auth_model->get_role_by_key('user')->role_name; ?></option>
            </select>
        </div>

        <div class="item-table-filter">
            <label><?php echo trans("email_status"); ?></label>
            <select name="email_status" class="form-control">
                <option value=""><?php echo trans("all"); ?></option>
                <option value="1" <?php echo ($this->input->get('email_status', true) == 1) ? 'selected' : ''; ?>><?php echo trans("confirmed"); ?></option>
                <option value="0" <?php echo $this->input->get('email_status', true) != null && $this->input->get('email_status', true) != 1 ? 'selected' : ''; ?>><?php echo trans("unconfirmed"); ?></option>
            </select>
        </div>
        <div class="item-table-filter">
            <label><?php echo trans("reward_system"); ?></label>
            <select name="reward_system" class="form-control">
                <option value=""><?php echo trans("all"); ?></option>
                <option value="1" <?php echo ($this->input->get('reward_system', true) == 1) ? 'selected' : ''; ?>><?php echo trans("active"); ?></option>
                <option value="0" <?php echo $this->input->get('reward_system', true) != null && $this->input->get('reward_system', true) != 1 ? 'selected' : ''; ?>><?php echo trans("inactive"); ?></option>
            </select>
        </div>

        <div class="item-table-filter item-table-filter-long">
            <label><?php echo trans("search"); ?></label>
            <input name="q" class="form-control" placeholder="<?php echo trans("search") ?>" type="search" value="<?php echo html_escape($this->input->get('q', true)); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
        </div>


        <div class="item-table-filter md-top-10" style="width: 65px; min-width: 65px;">
            <label style="display: block">&nbsp;</label>
            <button type="submit" class="btn bg-purple"><?php echo trans("filter"); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>