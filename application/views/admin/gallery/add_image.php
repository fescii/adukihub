<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div class="left">
                    <h3 class="box-title"><?php echo trans("add_image"); ?></h3>
                </div>
                <div class="right">
                    <a href="<?php echo admin_url(); ?>gallery-images" class="btn btn-success btn-add-new">
                        <i class="fa fa-bars"></i>
                        <?php echo trans("images"); ?>
                    </a>
                </div>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open_multipart('gallery_controller/add_gallery_image_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages_form'); ?>
                <div class="form-group">
                    <label><?php echo trans("language"); ?></label>
                    <select name="lang_id" class="form-control" onchange="get_albums_by_lang(this.value);">
                        <?php foreach ($this->languages as $language): ?>
                            <option value="<?php echo $language->id; ?>" <?php echo ($this->selected_lang->id == $language->id) ? 'selected' : ''; ?>><?php echo $language->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo trans("album"); ?></label>
                    <select name="album_id" id="albums" class="form-control" required onchange="get_categories_by_albums(this.value);">
                        <option value=""><?php echo trans('select'); ?></option>
                        <?php foreach ($albums as $album): ?>
                            <option value="<?php echo $album->id; ?>"><?php echo $album->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('category'); ?></label>
                    <select id="categories" name="category_id" class="form-control">
                        <option value=""><?php echo trans('select'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('title'); ?></label>
                    <input type="text" class="form-control"
                           name="title" id="title" placeholder="<?php echo trans('title'); ?>"
                           value="<?php echo old('title'); ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('image'); ?></label>
                    <div class="col-sm-12">
                        <div class="row">
                            <a class='btn btn-success btn-sm btn-file-upload'>
                                <?php echo trans('select_image'); ?>
                                <input type="file" id="Multifileupload" name="files[]" size="40" accept=".png, .jpg, .jpeg, .gif" multiple="multiple" required>
                            </a>
                            <span>(<?php echo trans("select_multiple_images"); ?>)</span>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div id="MultidvPreview">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('add_image'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>