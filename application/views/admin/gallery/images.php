<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans("images"); ?></h3>
        </div>
        <div class="right">
            <a href="<?php echo admin_url(); ?>gallery-add-image" class="btn btn-success btn-add-new">
                <i class="fa fa-plus"></i>
                <?php echo trans("add_image"); ?>
            </a>
        </div>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <?php $this->load->view('admin/gallery/_filter'); ?>
                        <thead>
                        <tr role="row">
                            <th width="20"><?php echo trans('id'); ?></th>
                            <th><?php echo trans('image'); ?></th>
                            <th><?php echo trans('title'); ?></th>
                            <th><?php echo trans('language'); ?></th>
                            <th><?php echo trans('album'); ?></th>
                            <th><?php echo trans('category'); ?></th>
                            <th><?php echo trans('date'); ?></th>
                            <th class="max-width-120"><?php echo trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($images as $item):
                            $img_base_url = base_url();
                            if ($item->storage == "aws_s3") {
                                $img_base_url = $this->aws_base_url;
                            } ?>
                            <tr>
                                <td><?php echo html_escape($item->id); ?></td>
                                <td>
                                    <div style="position: relative">
                                        <img src="<?= $img_base_url . html_escape($item->path_small); ?>" alt="" class="img-responsive" style="max-width: 140px; max-height: 140px;">
                                        <?php if ($item->is_album_cover): ?>
                                            <label class="label label-success" style="position: absolute;left: 0;top: 0;"><?php echo trans("album_cover"); ?></label>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo html_escape($item->title); ?></td>
                                <td>
                                    <?php
                                    $lang = get_language($item->lang_id);
                                    if (!empty($lang)) {
                                        echo html_escape($lang->name);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $album = get_gallery_album($item->album_id);
                                    if (!empty($album)) {
                                        echo html_escape($album->name);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $category = get_gallery_category($item->category_id);
                                    if (!empty($category)) {
                                        echo html_escape($category->name);
                                    }
                                    ?>
                                </td>
                                <td class="nowrap"><?php echo formatted_date($item->created_at); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn bg-purple dropdown-toggle btn-select-option"
                                                type="button"
                                                data-toggle="dropdown"><?php echo trans('select_an_option'); ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu options-dropdown">
                                            <?php if ($item->is_album_cover == 0): ?>
                                                <li>
                                                    <a href="javascript:void(0)" onclick="set_as_album_cover('<?php echo $item->id; ?>');"><i class="fa fa-check option-icon"></i><?php echo trans('set_as_album_cover'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <a href="<?php echo admin_url(); ?>update-gallery-image/<?php echo html_escape($item->id); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="delete_item('gallery_controller/delete_gallery_image_post','<?php echo $item->id; ?>','<?php echo trans("confirm_image"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-12 text-right">
                <?php echo $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>
</div>
