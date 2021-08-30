<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-12">
        <h3 style="font-size: 18px; font-weight: 600;"><?php echo trans('reward_system'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('settings'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('reward_controller/update_settings_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('msg_reward_settings'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?php echo trans('status'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="reward_system_status" value="1" id="reward_system_status_1"
                                   class="square-purple" <?php echo ($this->general_settings->reward_system_status == 1) ? 'checked' : ''; ?>>
                            <label for="reward_system_status_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="reward_system_status" value="0" id="reward_system_status_2"
                                   class="square-purple" <?php echo ($this->general_settings->reward_system_status != 1) ? 'checked' : ''; ?>>
                            <label for="reward_system_status_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('reward_amount'); ?></label>
                    <div class="input-group">
                        <div class="input-group-addon"><?php echo $this->general_settings->currency_symbol; ?></div>
                        <input type="text" class="form-control price-input" name="reward_amount" value="<?php echo $this->general_settings->reward_amount; ?>" placeholder="E.g. 1.5" required>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->

            <?php echo form_close(); ?><!-- form end -->
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('payout_methods'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('reward_controller/update_payout_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('msg_payout_methods'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?= trans("paypal"); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="payout_paypal_status" value="1" id="payout_paypal_status_1"
                                   class="square-purple" <?php echo ($this->general_settings->payout_paypal_status == 1) ? 'checked' : ''; ?>>
                            <label for="payout_paypal_status_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="payout_paypal_status" value="0" id="payout_paypal_status_2"
                                   class="square-purple" <?php echo ($this->general_settings->payout_paypal_status != 1) ? 'checked' : ''; ?>>
                            <label for="payout_paypal_status_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?= trans("iban"); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="payout_iban_status" value="1" id="payout_iban_status_1"
                                   class="square-purple" <?php echo ($this->general_settings->payout_iban_status == 1) ? 'checked' : ''; ?>>
                            <label for="payout_iban_status_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="payout_iban_status" value="0" id="payout_iban_status_2"
                                   class="square-purple" <?php echo ($this->general_settings->payout_iban_status != 1) ? 'checked' : ''; ?>>
                            <label for="payout_iban_status_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <label><?= trans("swift"); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="payout_swift_status" value="1" id="payout_swift_status_1"
                                   class="square-purple" <?php echo ($this->general_settings->payout_swift_status == 1) ? 'checked' : ''; ?>>
                            <label for="payout_swift_status_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-sm-6 col-xs-12 col-option">
                            <input type="radio" name="payout_swift_status" value="0" id="payout_swift_status_2"
                                   class="square-purple" <?php echo ($this->general_settings->payout_swift_status != 1) ? 'checked' : ''; ?>>
                            <label for="payout_swift_status_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->

            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('currency'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('reward_controller/update_currency_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata('msg_reward_currency'))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('currency_name'); ?></label>
                    <input type="text" class="form-control" name="currency_name" value="<?php echo $this->general_settings->currency_name; ?>" placeholder="E.g. US Dollar" required>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('currency_symbol'); ?></label>
                    <input type="text" class="form-control" name="currency_symbol" value="<?php echo html_escape($this->general_settings->currency_symbol); ?>" placeholder="E.g. $, USD or <?= html_escape('&#36;') ?>" required>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-5">
                            <label><?php echo trans('currency_format'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="currency_format" value="us" id="currency_format_1" class="square-purple" <?php echo ($this->general_settings->currency_format == 'us') ? 'checked' : ''; ?>>
                            <label for="currency_format_1" class="option-label">1<strong>,</strong>234<strong>,</strong>567<strong>.</strong>89</label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="currency_format" value="european" id="currency_format_2" class="square-purple" <?php echo ($this->general_settings->currency_format == 'european') ? 'checked' : ''; ?>>
                            <label for="currency_format_2" class="option-label">1<strong>.</strong>234<strong>.</strong>567<strong>,</strong>89</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12 m-b-5">
                            <label><?php echo trans('currency_symbol_format'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="currency_symbol_format" value="left" id="currency_symbol_format_1" class="square-purple" <?php echo ($this->general_settings->currency_symbol_format == 'left') ? 'checked' : ''; ?>>
                            <label for="currency_symbol_format_1" class="option-label">$100 (<?php echo trans("left"); ?>)</label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="currency_symbol_format" value="right" id="currency_symbol_format_2" class="square-purple" <?php echo ($this->general_settings->currency_symbol_format == 'right') ? 'checked' : ''; ?>>
                            <label for="currency_symbol_format_2" class="option-label">100$ (<?php echo trans("right"); ?>)</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->

            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>