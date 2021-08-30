<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans('pageviews'); ?>&nbsp;(<?= trans("this_month"); ?>)</h3>
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
                    <table class="table table-bordered table-striped" role="grid">
                        <?php $this->load->view('admin/reward/_filter', ['url' => admin_url() . 'reward-system/pageviews']); ?>
                        <thead>
                        <tr role="row">
                            <th><?php echo trans("post"); ?></th>
                            <th><?php echo trans("author"); ?></th>
                            <th><?php echo trans("ip_address"); ?></th>
                            <th><?php echo trans("user_agent"); ?></th>
                            <th><?php echo trans("earnings"); ?></th>
                            <th><?php echo trans("date"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pageviews as $item): ?>
                            <tr>
                                <td><?php echo $item->post_id; ?></td>
                                <td>
                                    <a href="<?php echo generate_profile_url($item->author_slug); ?>" target="_blank" class="table-user-link">
                                        <strong><?php echo html_escape($item->author_username); ?></strong>
                                    </a>
                                </td>
                                <td><?php echo html_escape($item->ip_address); ?></td>
                                <td><?php echo html_escape($item->user_agent); ?></td>
                                <td><?php echo price_formatted($item->reward_amount, $this->decimal_point); ?></td>
                                <td><?php echo $item->created_at; ?></td>
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
