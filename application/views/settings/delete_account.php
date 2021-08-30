<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo generate_url('settings'); ?>"><?php echo trans("settings"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
                    </ol>
                </nav>

                <h1 class="page-title"><?php echo trans("settings"); ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="row-custom">
                    <!-- load profile nav -->
                    <?php $this->load->view("settings/_setting_tabs"); ?>
                </div>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="row-custom">
                    <div class="profile-tab-content">
                        <!-- include message block -->
                        <?php $this->load->view('partials/_messages'); ?>

                        <?php echo form_open("delete-account-post", ['id' => 'form_validate', 'class' => 'validate_terms']); ?>
                        <div class="form-group">
                            <label><?php echo trans("form_password"); ?></label>
                            <input type="password" name="password" class="form-control form-input" value="<?php echo old("password"); ?>" placeholder="<?php echo trans("form_password"); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="custom-checkbox">
                                <input type="checkbox" name="confirm" class="checkbox_terms_conditions" required>
                                <span class="checkbox-icon"><i class="icon-check"></i></span>
                                <?php echo trans("delete_account_confirm"); ?></a>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-md btn-custom"><?php echo trans("delete_account") ?></button>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->

