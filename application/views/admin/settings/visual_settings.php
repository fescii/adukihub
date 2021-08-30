<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-12">
        <h3 style="font-size: 18px; font-weight: 600;"><?php echo trans('visual_settings'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-xs-12">
        <?php $this->load->view('admin/includes/_messages'); ?>
    </div>
    <div class="col-sm-6 col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?php echo trans('style'); ?></h3>
                </div>
            </div><!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open_multipart('admin_controller/visual_settings_post'); ?>
            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label><?php echo trans("site_color"); ?></label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="clrpicker" class="color-picker">
                                        <input type="text" name="site_color" value="<?= $this->site_color; ?>" class="form-control" style="width: 150px;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label><?php echo trans("block_color"); ?></label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="clrpicker-block" class="color-picker">
                                        <input type="text" name="site_block_color" value="<?= $visual_settings->site_block_color; ?>" class="form-control" style="width: 150px;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group m-t-60">
                    <label><?php echo trans('post_list_style'); ?></label>
                    <div class="row m-b-15">
                        <div class="col-sm-4">
                            <div class="col-sm-12 m-b-15 col-option">
                                <input type="radio" name="post_list_style" id="post_list_style_1" value="vertical" class="square-purple" <?php echo ($visual_settings->post_list_style == "vertical") ? 'checked' : ''; ?>>
                                <label for="post_list_style_1"><?= trans("vertical"); ?></label>
                            </div>
                            <img src="<?php echo base_url(); ?>assets/admin/img/post_vertical.jpg" alt="" class="img-responsive" style="width: 150px;">
                        </div>
                        <div class="col-sm-6">
                            <div class="col-sm-12 m-b-15 col-option">
                                <input type="radio" name="post_list_style" value="horizontal" id="post_list_style_2" class="square-purple" <?php echo ($visual_settings->post_list_style == "horizontal") ? 'checked' : ''; ?>>
                                <label for="post_list_style_2"><?= trans("horizontal"); ?></label>
                            </div>
                            <img src="<?php echo base_url(); ?>assets/admin/img/post_horizontal.jpg" alt="" class="img-responsive" style="height: 100px;">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="color" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-sm-6 col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?php echo trans('logo'); ?></h3>
                </div>
            </div><!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open_multipart('admin_controller/visual_settings_post'); ?>

            <div class="box-body">
                <div class="form-group" style="margin-bottom: 60px;">
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label display-block"><?php echo trans('logo'); ?></label>
                            <div style="margin-bottom: 10px; border: 1px solid #eee; padding: 10px; width: auto; display: inline-block;">
                                <img src="<?php echo get_logo($visual_settings); ?>" alt="logo" style="max-width: 250px; max-height: 250px;">
                            </div>
                            <div class="display-block">
                                <a class='btn btn-success btn-sm btn-file-upload'>
                                    <?php echo trans('change_logo'); ?>
                                    <input type="file" name="logo" size="40" accept=".png, .jpg, .jpeg, .gif, .svg" onchange="$('#upload-file-info1').html($(this).val().replace(/.*[\/\\]/, ''));">
                                </a>
                                (.png, .jpg, .jpeg, .gif, .svg)
                            </div>
                            <span class='label label-info' id="upload-file-info1"></span>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label display-block"><?php echo trans('logo_footer'); ?></label>
                            <div style="margin-bottom: 10px; border: 1px solid #eee; padding: 10px; width: auto; display: inline-block;">
                                <img src="<?php echo get_logo_footer($visual_settings); ?>" alt="logo" style="max-width: 250px; max-height: 250px;">
                            </div>
                            <div class="display-block">
                                <a class='btn btn-success btn-sm btn-file-upload'>
                                    <?php echo trans('change_logo'); ?>
                                    <input type="file" name="logo_footer" size="40" accept=".png, .jpg, .jpeg, .gif, .svg" onchange="$('#upload-file-info2').html($(this).val().replace(/.*[\/\\]/, ''));">
                                </a>
                                (.png, .jpg, .jpeg, .gif, .svg)
                            </div>
                            <span class='label label-info' id="upload-file-info2"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 60px;">
                    <label class="control-label display-block"><?php echo trans('logo_email'); ?></label>
                    <div style="margin-bottom: 10px; border: 1px solid #eee; padding: 10px; width: auto; display: inline-block;">
                        <img src="<?php echo get_logo_email($visual_settings); ?>" alt="logo" style="max-width: 200px; max-height: 200px;">
                    </div>
                    <div class="display-block">
                        <a class='btn btn-success btn-sm btn-file-upload'>
                            <?php echo trans('change_logo'); ?>
                            <input type="file" name="logo_email" size="40" accept=".png, .jpg, .jpeg" onchange="$('#upload-file-info3').html($(this).val().replace(/.*[\/\\]/, ''));">
                        </a>
                        (.png, .jpg, .jpeg)
                    </div>
                    <span class='label label-info' id="upload-file-info3"></span>
                </div>

                <div class="form-group">
                    <label class="control-label display-block"><?php echo trans('favicon'); ?> (16x16px)</label>
                    <div style="margin-bottom: 10px;">
                        <img src="<?php echo get_favicon($visual_settings); ?>" alt="favicon" style="max-width: 100px; max-height: 100px;">
                    </div>
                    <div class="display-block">
                        <a class='btn btn-success btn-sm btn-file-upload'>
                            <?php echo trans('change_favicon'); ?>
                            <input type="file" name="favicon" size="40" accept=".png" onchange="$('#upload-file-info4').html($(this).val().replace(/.*[\/\\]/, ''));">
                        </a>
                        (.png)
                    </div>
                    <span class='label label-info' id="upload-file-info4"></span>
                </div>


            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="logo" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>
<style>
    .colorpicker::before {
        display: none !important;
    }
</style>
<script>
    $(function () {
        $('#clrpicker').colorpicker({
            popover: false,
            inline: true,
            container: '#clrpicker',
            format: 'hex'
        });
        $('#clrpicker-block').colorpicker({
            popover: false,
            inline: true,
            container: '#clrpicker-block',
            format: 'hex'
        });
    });
</script>
