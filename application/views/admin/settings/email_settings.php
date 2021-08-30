<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row">
    <!-- form start -->
    <?php echo form_open('admin_controller/email_settings_post'); ?>
    <div class="col-lg-6 col-md-12" style="min-height: 600px;">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('email_settings'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php
                $message = $this->session->flashdata('message_type');
                if (!empty($message) && $message == "email") {
                    $this->load->view('admin/includes/_messages');
                }
                ?>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('mail_protocol'); ?></label>
                    <select name="mail_protocol" class="form-control" onchange="window.location.href = '<?= admin_url(); ?>email-settings?protocol='+this.value;">
                        <option value="smtp" <?= $protocol == "smtp" ? "selected" : ""; ?>><?= trans('smtp'); ?></option>
                        <option value="mail" <?= $protocol == "mail" ? "selected" : ""; ?>><?= trans('mail'); ?></option>
                    </select>
                </div>

                <?php if ($protocol == "smtp"): ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans('mail_library'); ?></label>
                        <select name="mail_library" class="form-control">
                            <option value="swift" <?= $this->general_settings->mail_library == "swift" ? "selected" : ""; ?>>Swift Mailer</option>
                            <option value="php" <?= $this->general_settings->mail_library == "php" ? "selected" : ""; ?>>PHP Mailer</option>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="mail_library" value="php">
                <?php endif; ?>

                <?php if ($protocol == "smtp"): ?>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans('encryption'); ?></label>
                        <select name="mail_encryption" class="form-control">
                            <option value="tls" <?= $this->general_settings->mail_encryption == "tls" ? "selected" : ""; ?>>TLS</option>
                            <option value="ssl" <?= $this->general_settings->mail_encryption == "ssl" ? "selected" : ""; ?>>SSL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans('mail_host'); ?></label>
                        <input type="text" class="form-control" name="mail_host"
                               placeholder="<?php echo trans('mail_host'); ?>" value="<?php echo html_escape($this->general_settings->mail_host); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans('mail_port'); ?></label>
                        <input type="text" class="form-control" name="mail_port"
                               placeholder="<?php echo trans('mail_port'); ?>" value="<?php echo html_escape($this->general_settings->mail_port); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans('mail_username'); ?></label>
                        <input type="text" class="form-control" name="mail_username"
                               placeholder="<?php echo trans('mail_username'); ?>" value="<?php echo html_escape($this->general_settings->mail_username); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo trans('mail_password'); ?></label>
                        <input type="password" class="form-control" name="mail_password"
                               placeholder="<?php echo trans('mail_password'); ?>" value="<?php echo html_escape($this->general_settings->mail_password); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="mail_encryption" value="<?= $this->general_settings->mail_encryption; ?>">
                    <input type="hidden" name="mail_host" value="<?= $this->general_settings->mail_host; ?>">
                    <input type="hidden" name="mail_port" value="<?= $this->general_settings->mail_port; ?>">
                    <input type="hidden" name="mail_username" value="<?= $this->general_settings->mail_username; ?>">
                    <input type="hidden" name="mail_password" value="<?= $this->general_settings->mail_password; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('mail_title'); ?></label>
                    <input type="text" class="form-control" name="mail_title"
                           placeholder="<?php echo trans('mail_title'); ?>" value="<?php echo html_escape($this->general_settings->mail_title); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('reply_to'); ?></label>
                    <input type="email" class="form-control" name="mail_reply_to"
                           placeholder="<?php echo trans('reply_to'); ?>" value="<?php echo html_escape($this->general_settings->mail_reply_to); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" name="submit" value="email" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?><!-- form end -->

    <?php echo form_open('admin_controller/email_verification_settings_post'); ?>
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('email_verification'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php
                if (!empty($message) && $message == "verification") {
                    $this->load->view('admin/includes/_messages');
                } ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('email_verification'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="email_verification" value="1" id="email_verification_1" class="square-purple" <?php echo ($this->general_settings->email_verification == '1') ? 'checked' : ''; ?>>
                            <label for="email_verification_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="email_verification" value="0" id="email_verification_2" class="square-purple" <?php echo ($this->general_settings->email_verification == '0') ? 'checked' : ''; ?>>
                            <label for="email_verification_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="verification" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?><!-- form end -->

    <?php echo form_open('admin_controller/contact_email_settings_post'); ?>
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('contact_messages'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php
                if (!empty($message) && $message == "contact") {
                    $this->load->view('admin/includes/_messages');
                } ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('send_contact_to_mail'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="mail_contact_status" value="1" id="mail_contact_status_1" class="square-purple" <?php echo ($this->general_settings->mail_contact_status == '1') ? 'checked' : ''; ?>>
                            <label for="mail_contact_status_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="mail_contact_status" value="0" id="mail_contact_status_2" class="square-purple" <?php echo ($this->general_settings->mail_contact_status == '0') ? 'checked' : ''; ?>>
                            <label for="mail_contact_status_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('email'); ?> (<?php echo trans("contact_messages_will_send"); ?>)</label>
                    <input type="text" class="form-control" name="mail_contact"
                           placeholder="<?php echo trans('email'); ?>" value="<?php echo html_escape($this->general_settings->mail_contact); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="contact" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?><!-- form end -->

    <?php echo form_open('admin_controller/send_test_email_post'); ?>
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('send_test_email'); ?></h3>
                <small class="small-title"><?php echo trans('send_test_email_exp'); ?></small>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php
                if (!empty($message) && $message == "send_email") {
                    $this->load->view('admin/includes/_messages');
                } ?>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('placeholder_email'); ?></label>
                    <input type="text" class="form-control" name="email" placeholder="<?php echo trans('placeholder_email'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="contact" class="btn btn-primary pull-right"><?php echo trans('send_email'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?><!-- form end -->
</div>


<style>
    h4 {
        color: #0d6aad;
        font-weight: 600;
        margin-bottom: 15px;
        margin-top: 30px;
    }
</style>