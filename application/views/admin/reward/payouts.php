<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans('payouts'); ?></h3>
        </div>
        <div class="right">
            <a href="<?php echo admin_url(); ?>reward-system/add-payout" class="btn btn-success btn-add-new">
                <i class="fa fa-plus"></i>
                <?php echo trans('add_payout'); ?>
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
                        <?php $this->load->view('admin/reward/_filter', ['url' => admin_url() . 'reward-system/payouts']); ?>
                        <thead>
                        <tr role="row">
                            <th><?php echo trans('id'); ?></th>
                            <th><?php echo trans('user_id'); ?></th>
                            <th><?php echo trans('username'); ?></th>
                            <th><?php echo trans('email'); ?></th>
                            <th><?php echo trans('amount'); ?></th>
                            <th><?php echo trans('payout_method'); ?></th>
                            <th><?php echo trans('date'); ?></th>
                            <th class="max-width-120"><?php echo trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($payouts as $item): ?>
                            <tr>
                                <td><?php echo $item->id; ?></td>
                                <td><?php echo $item->user_id; ?></td>
                                <td><?php echo html_escape($item->username); ?></td>
                                <td><?php echo html_escape($item->email); ?></td>
                                <td><?php echo price_formatted($item->amount); ?></td>
                                <td><?php echo trans($item->payout_method); ?></td>
                                <td><?php echo $item->created_at; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn bg-purple dropdown-toggle btn-select-option"
                                                type="button"
                                                data-toggle="dropdown"><?php echo trans('select_an_option'); ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu options-dropdown">
                                            <li>
                                                <a href="javascript:void(0)" onclick="delete_item('reward_controller/delete_payout_post','<?php echo $item->id; ?>','<?php echo trans("confirm_record"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
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
