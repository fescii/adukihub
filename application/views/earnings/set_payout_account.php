<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo trans("payouts"); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="profile-page-top">
                    <?php $this->load->view("profile/_profile_user_info"); ?>
                </div>
            </div>
        </div>
        <div class="profile-page">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3">
                    <?php $this->load->view("earnings/_tabs"); ?>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-9">
                    <?php
                    $active_tab = $this->session->flashdata('msg_payout');
                    if (empty($active_tab)) {
                        $active_tab = "paypal";
                    }
                    $show_all_tabs = false;
                    ?>
                    <!-- Nav pills -->
                    <ul class="nav nav-pills nav-payout-accounts text-center">
                        <?php if ($this->general_settings->payout_paypal_status): $show_all_tabs = true; ?>
                            <li class="<?php echo ($active_tab == 'paypal') ? 'active' : ''; ?>">
                                <a class="nav-link-paypal" data-toggle="pill" href="#tab_paypal"><?php echo trans("paypal"); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->general_settings->payout_iban_status): $show_all_tabs = true; ?>
                            <li class="<?php echo ($active_tab == 'iban') ? 'active' : ''; ?>">
                                <a class="nav-link-bank" data-toggle="pill" href="#tab_iban"><?php echo trans("iban"); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->general_settings->payout_swift_status): $show_all_tabs = true; ?>
                            <li class="<?php echo ($active_tab == 'swift') ? 'active' : ''; ?>">
                                <a class="nav-link-swift" data-toggle="pill" href="#tab_swift"><?php echo trans("swift"); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php $active_tab_content = 'paypal'; ?>
                    <!-- Tab panes -->
                    <?php if ($show_all_tabs): ?>
                        <div class="tab-content">
                            <div class="tab-pane <?php echo ($active_tab == 'paypal') ? 'active' : 'fade'; ?>" id="tab_paypal">
                                <?php if ($active_tab == "paypal"):
                                    $this->load->view('partials/_messages');
                                endif; ?>
                                <?php echo form_open('set-paypal-payout-account-post', ['id' => 'form_validate_payout_1']); ?>
                                <div class="form-group">
                                    <label><?php echo trans("paypal_email_address"); ?>*</label>
                                    <input type="email" name="payout_paypal_email" class="form-control form-input" value="<?php echo html_escape($user_payout->payout_paypal_email); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="default_payout_account" value="paypal" <?php echo ($user_payout->default_payout_account == 'paypal') ? 'checked' : ''; ?>>
                                        <span class="checkbox-icon"><i class="icon-check"></i></span>
                                        <?php echo trans("set_default_payment_account"); ?>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-custom"><?php echo trans("save_changes"); ?></button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>

                            <div class="tab-pane <?php echo ($active_tab == 'iban') ? 'active' : 'fade'; ?>" id="tab_iban">
                                <?php if ($active_tab == "iban"):
                                    $this->load->view('partials/_messages');
                                endif; ?>
                                <?php echo form_open('set-iban-payout-account-post', ['id' => 'form_validate_payout_2']); ?>
                                <div class="form-group">
                                    <label><?php echo trans("full_name"); ?>*</label>
                                    <input type="text" name="iban_full_name" class="form-control form-input" value="<?php echo html_escape($user_payout->iban_full_name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12 col-md-6 m-b-sm-15">
                                            <label><?php echo trans("country"); ?>*</label>
                                            <input type="text" name="iban_country" class="form-control form-input" value="<?php echo html_escape($user_payout->iban_country); ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label><?php echo trans("bank_name"); ?>*</label>
                                            <input type="text" name="iban_bank_name" class="form-control form-input" value="<?php echo html_escape($user_payout->iban_bank_name); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo trans("iban_long"); ?>(<?php echo trans("iban"); ?>)*</label>
                                    <input type="text" name="iban_number" class="form-control form-input" value="<?php echo html_escape($user_payout->iban_number); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="default_payout_account" value="iban" <?php echo ($user_payout->default_payout_account == 'iban') ? 'checked' : ''; ?>>
                                        <span class="checkbox-icon"><i class="icon-check"></i></span>
                                        <?php echo trans("set_default_payment_account"); ?>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-custom"><?php echo trans("save_changes"); ?></button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>

                            <div class="tab-pane <?php echo ($active_tab == 'swift') ? 'active' : 'fade'; ?>" id="tab_swift">
                                <?php if ($active_tab == "swift"):
                                    $this->load->view('partials/_messages');
                                endif; ?>
                                <?php echo form_open('set-swift-payout-account-post', ['id' => 'form_validate_payout_3']); ?>
                                <div class="form-group">
                                    <label><?php echo trans("full_name"); ?>*</label>
                                    <input type="text" name="swift_full_name" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_full_name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12 col-md-6 m-b-sm-15">
                                            <label><?php echo trans("country"); ?>*</label>
                                            <input type="text" name="swift_country" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_country); ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label><?php echo trans("state"); ?>*</label>
                                            <input type="text" name="swift_state" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_state); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12 col-md-6 m-b-sm-15">
                                            <label><?php echo trans("city"); ?>*</label>
                                            <input type="text" name="swift_city" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_city); ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label><?php echo trans("postcode"); ?>*</label>
                                            <input type="text" name="swift_postcode" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_postcode); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo trans("address"); ?>*</label>
                                    <input type="text" name="swift_address" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_address); ?>" required>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12 col-md-6 m-b-sm-15">
                                            <label><?php echo trans("bank_account_holder_name"); ?>*</label>
                                            <input type="text" name="swift_bank_account_holder_name" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_bank_account_holder_name); ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label><?php echo trans("bank_name"); ?>*</label>
                                            <input type="text" name="swift_bank_name" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_bank_name); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-12 col-md-6 m-b-sm-15">
                                            <label><?php echo trans("bank_branch_country"); ?>*</label>
                                            <input type="text" name="swift_bank_branch_country" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_bank_branch_country); ?>" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label><?php echo trans("bank_branch_city"); ?>*</label>
                                            <input type="text" name="swift_bank_branch_city" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_bank_branch_city); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo trans("swift_iban"); ?>*</label>
                                    <input type="text" name="swift_iban" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_iban); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label><?php echo trans("swift_code"); ?>*</label>
                                    <input type="text" name="swift_code" class="form-control form-input" value="<?php echo html_escape($user_payout->swift_code); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="default_payout_account" value="swift" <?php echo ($user_payout->default_payout_account == 'swift') ? 'checked' : ''; ?>>
                                        <span class="checkbox-icon"><i class="icon-check"></i></span>
                                        <?php echo trans("set_default_payment_account"); ?>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-custom"><?php echo trans("save_changes"); ?></button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <p class="warning-set-payout">**<?= trans("warning_default_payout_account"); ?></p>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Wrapper End-->


