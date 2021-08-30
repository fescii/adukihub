<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

    <div class="row">
        <div class="col-sm-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <div class="left">
                        <h3 class="box-title"><?php echo trans('add_payout'); ?></h3>
                    </div>
                    <div class="right">
                        <a href="<?php echo admin_url(); ?>reward-system/earnings" class="btn btn-success btn-add-new">
                            <i class="fa fa-bars"></i>
                            <?php echo trans('earnings'); ?>
                        </a>
                        <a href="<?php echo admin_url(); ?>reward-system/payouts" class="btn btn-success btn-add-new">
                            <i class="fa fa-bars"></i>
                            <?php echo trans('payouts'); ?>
                        </a>
                    </div>
                </div>

                <!-- form start -->
                <?php echo form_open('reward_controller/add_payout_post'); ?>
                <div class="box-body">
                    <!-- include message block -->
                    <?php $this->load->view('admin/includes/_messages'); ?>

                    <div class="form-group">
                        <label><?php echo trans("user"); ?></label>
                        <select name="user_id" class="form-control">
                            <option value=""><?php echo trans('select'); ?></option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user->id; ?>"><?php echo "Id: " . $user->id . " - " . $user->username; ?>&nbsp;(<?= trans("balance") . ": " . price_formatted($user->balance); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?php echo trans("payout_method"); ?></label>
                        <select name="payout_method" class="form-control">
                            <option value=""><?php echo trans('select'); ?></option>
                            <option value="paypal"><?= trans("paypal"); ?></option>
                            <option value="iban"><?= trans("iban"); ?></option>
                            <option value="swift"><?= trans("swift"); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php echo trans('amount'); ?></label>
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo $this->general_settings->currency_symbol; ?></div>
                            <input type="text" class="form-control price-input" name="amount" placeholder="E.g. 1.5" required>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_payout'); ?></button>
                </div>
                <!-- /.box-footer -->

                <?php echo form_close(); ?><!-- form end -->
            </div>
            <!-- /.box -->
        </div>
    </div>

<?php $this->load->view('admin/file-manager/_load_file_manager', ['load_images' => true, 'load_files' => false, 'load_videos' => false, 'load_audios' => false]); ?>