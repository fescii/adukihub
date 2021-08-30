<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .label-newsletter-email {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 300 !important;
        color: #41464b !important;
        background-color: #e2e3e5 !important;
        border-color: #d3d6d8 !important;
    }

    .newsletter-email-container {
        max-height: 300px;
        overflow-y: auto;
        margin-top: 15px;
    }

    .newsletter-spinner {
        display: none;
        text-align: center;
        font-size: 16px;
    }

    .text-newsletter-completed {
        display: none;
    }
</style>

<div class="row" style="margin-bottom: 30px;">
    <div class="col-sm-12">
        <div class="alert alert-success alert-large m-t-10">
            <strong><?php echo trans("warning"); ?>!</strong>&nbsp;&nbsp;<?php echo trans("newsletter_send_many_exp"); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('send_email_subscriber'); ?></h3>
            </div>
            <form id="form_send_email">
                <div class="box-body">
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label><?php echo trans('to'); ?></label>
                        <?php if (!empty($emails)): ?>
                            <p style="max-height: 150px; overflow-y: auto">
                                <?php foreach ($emails as $email): ?>
                                    <label class="label-newsletter-email"><?= $email; ?></label>
                                <?php endforeach; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label><?php echo trans('subject'); ?></label>
                        <input type="text" name="subject" id="newsletter_subject" class="form-control" placeholder="<?php echo trans('subject'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?> required>
                    </div>

                    <div class="form-group">
                        <label><?php echo trans('content'); ?></label>
                        <div class="row">
                            <div class="col-sm-12 editor-buttons">
                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#file_manager_image" data-image-type="editor"><i class="fa fa-image"></i>&nbsp;&nbsp;&nbsp;<?php echo trans("add_image"); ?></button>
                            </div>
                        </div>
                        <textarea class="tinyMCE form-control" name="body"></textarea>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="<?= admin_url(); ?>newsletter" id="btn_newsletter_back" class="btn btn-danger"><?= trans("back"); ?></a>
                    <button type="submit" id="btn_send_newsletter" class="btn btn-primary pull-right"><?php echo trans('send_email'); ?>&nbsp;&nbsp;<i class="fa fa-send"></i></button>

                    <div class="col-sm-12 m-t-30">
                        <div class="row">
                            <div id="newsletter_spinner" class="newsletter-spinner">
                                <strong class="newsletter-sending"><?= trans("mail_is_being_sent"); ?></strong>
                                <strong class="text-newsletter-completed"><?= trans("completed"); ?>!</strong>
                                <div class="spinner" style="margin-top: 15px;">
                                    <div class="bounce1"></div>
                                    <div class="bounce2"></div>
                                    <div class="bounce3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="newsletter-email-container">
                                <ul id="newsletter_sent_emails" class="list-group csv-uploaded-files"></ul>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('admin/file-manager/_load_file_manager', ['load_images' => true, 'load_files' => false, 'load_videos' => false, 'load_audios' => false]); ?>

<script>
    var array_emails = "";
    var array_sent = [];
    <?php if(!empty($emails)): ?>var array_emails = <?= json_encode($emails); ?>;<?php endif; ?>

    var subject = "";
    var body = "";
    var sent_count = 0;
    $("#form_send_email").submit(function (event) {
        event.preventDefault();
        $("#newsletter_spinner").show();
        document.getElementById("btn_newsletter_back").disabled = true;
        document.getElementById("btn_send_newsletter").disabled = true;
        body = tinyMCE.activeEditor.getContent();
        subject = $("#newsletter_subject").val();
        send_newsletter_email();
    });

    function send_newsletter_email() {
        var email = get_next_email();
        if (email != "") {
            var data = {
                "subject": subject,
                "body": body,
                "email": email
            };
            data[csfr_token_name] = $.cookie(csfr_cookie_name);
            $.ajax({
                type: "POST",
                url: base_url + "admin_controller/newsletter_send_email_post",
                cache: false,
                data: data,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj.result == 1) {
                        remove_item_from_array(array_emails, email);
                        array_sent.push(email);
                        sent_count = sent_count + 1;
                        $("#newsletter_sent_emails").prepend('<li class="list-group-item list-group-item-success"><i class="fa fa-check"></i>&nbsp;' + sent_count + '. ' + email + '</li>');
                        send_newsletter_email();
                    }
                }
            });
        } else {
            $("#newsletter_spinner .newsletter-sending").hide();
            $("#newsletter_spinner .spinner").hide();
            $("#newsletter_spinner .text-newsletter-completed").css('display', 'block');
        }
    }

    function get_next_email() {
        var next_email = "";
        var i;
        for (i = 0; i < array_emails.length; i++) {
            if (array_sent.indexOf(array_emails[i]) < 0) {
                next_email = array_emails[i];
                break;
            }
        }
        return next_email;
    }

    function remove_item_from_array(array, item) {
        var index = array.indexOf(item);
        while (index > -1) {
            array.splice(index, 1);
            index = array.indexOf(item);
        }
    }
</script>
