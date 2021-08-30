<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans('administrators'); ?></h3>
        </div>
    </div><!-- /.box-header -->

    <div class="box-body">
        <div class="row">
            <!-- include message block -->
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable" id="cs_datatable" role="grid"
                           aria-describedby="example1_info">
                        <thead>
                        <tr role="row">
                            <th width="20"><?php echo trans('id'); ?></th>
                            <th><?php echo trans('image'); ?></th>
                            <th><?php echo trans('username'); ?></th>
                            <th><?php echo trans('email'); ?></th>
                            <th><?php echo trans('date'); ?></th>
                            <th class="max-width-120"><?php echo trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo html_escape($user->id); ?></td>
                                <td>
                                    <img src="<?php echo get_user_avatar($user->avatar); ?>" alt="user" class="img-responsive" style="height: 50px;">
                                </td>
                                <td>
                                    <?php echo html_escape($user->username); ?>
                                    &nbsp;
                                    <?php if ($user->reward_system_enabled == 1): ?>
                                        <label class="label label-info"><?php echo trans("reward_system"); ?></label>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo html_escape($user->email); ?></td>
                                <td><?php echo formatted_date($user->created_at); ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn bg-purple dropdown-toggle btn-select-option"
                                                type="button"
                                                data-toggle="dropdown"><?php echo trans('select_an_option'); ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu options-dropdown">
                                            <?php if ($user->reward_system_enabled == 1): ?>
                                                <li>
                                                    <a href="javascript:void(0)" onclick="enable_disable_reward_system('<?php echo $user->id; ?>');"><i class="fa fa-money option-icon"></i><?php echo trans('disable_reward_system'); ?></a>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <a href="javascript:void(0)" onclick="enable_disable_reward_system('<?php echo $user->id; ?>');"><i class="fa fa-money option-icon"></i><?php echo trans('enable_reward_system'); ?></a>
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <a href="<?php echo admin_url(); ?>edit-user/<?php echo html_escape($user->id); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="delete_item('admin_controller/delete_user_post','<?php echo $user->id; ?>','<?php echo trans("confirm_user"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
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
        </div>
    </div><!-- /.box-body -->
</div>