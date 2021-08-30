<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header with-border">
        <div class="left">
            <h3 class="box-title"><?php echo trans('earnings'); ?></h3>
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
                    <table class="table table-bordered table-striped" role="grid">
                        <?php $this->load->view('admin/reward/_filter', ['url' => admin_url() . 'reward-system/earnings']); ?>
                        <thead>
                        <tr role="row">
                            <th><?php echo trans('user_id'); ?></th>
                            <th><?php echo trans('username'); ?></th>
                            <th><?php echo trans('email'); ?></th>
                            <th><?php echo trans('total_pageviews'); ?></th>
                            <th><?php echo trans('balance'); ?></th>
                            <th><?php echo trans('payout_method'); ?></th>
                            <th class="max-width-120"><?php echo trans('options'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($earnings as $item): ?>
                            <tr>
                                <td><?php echo $item->id; ?></td>
                                <td><?php echo html_escape($item->username); ?></td>
                                <td><?php echo html_escape($item->email); ?></td>
                                <td><?php echo html_escape($item->total_pageviews); ?></td>
                                <td><?php echo price_formatted($item->balance); ?></td>
                                <td>
                                    <p class="m-0">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#accountDetailsModel_<?php echo $item->id; ?>"><?php echo trans("payout_method"); ?></button>
                                    </p>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn bg-purple dropdown-toggle btn-select-option"
                                                type="button"
                                                data-toggle="dropdown"><?php echo trans('select_an_option'); ?>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu options-dropdown">
                                            <li>
                                                <a href="<?php echo admin_url(); ?>edit-user/<?php echo html_escape($item->id); ?>"><i class="fa fa-edit option-icon"></i><?php echo trans('edit'); ?></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="delete_item('page_controller/delete_page_post','<?php echo $item->id; ?>','<?php echo trans("confirm_page"); ?>');"><i class="fa fa-trash option-icon"></i><?php echo trans('delete'); ?></a>
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

<?php foreach ($earnings as $item):
    $payout = $this->reward_model->get_user_payout_account($item->id); ?>
    <!-- Modal -->
    <div id="accountDetailsModel_<?php echo $item->id; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo trans($payout->default_payout_account); ?></h4>
                </div>
                <div class="modal-body">
                    <?php if (!empty($payout)): ?>
                        <?php if ($payout->default_payout_account == "paypal"): ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("user"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($item->username); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("paypal_email_address"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo $payout->payout_paypal_email; ?>
                                    </strong>
                                </div>
                            </div>
                        <?php elseif ($payout->default_payout_account == "iban"): ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("user"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($item->username); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("full_name"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->iban_full_name); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("country"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->iban_country); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("bank_name"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->iban_bank_name); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("iban"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->iban_number); ?>
                                    </strong>
                                </div>
                            </div>
                        <?php elseif ($payout->default_payout_account == "swift"): ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("user"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($item->username); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("full_name"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_full_name); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("address"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_address); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("state"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_state); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("city"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_city); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("postcode"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_postcode); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("country"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_country); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("bank_account_holder_name"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_bank_account_holder_name); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("iban"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_iban); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("swift_code"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_code); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("bank_name"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_bank_name); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("bank_branch_city"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_bank_branch_city); ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("bank_branch_country"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo html_escape($payout->swift_bank_branch_country); ?>
                                    </strong>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($payout->default_payout_account != "paypal" && $payout->default_payout_account != "iban" && $payout->default_payout_account != "swift"): ?>
                        <p class="text-center text-muted"><?= trans("no_records_found"); ?></p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<?php endforeach; ?>

<style>
    .modal .row {
        min-height: 26px;
    }
</style>
