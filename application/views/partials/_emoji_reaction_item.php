<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (helper_getsession('vr_reaction_vote_count_' . $reactions->post_id, 'int') < 3): ?>
    <div class="col-reaction col-reaction-like" onclick="add_reaction('<?php echo $reactions->post_id; ?>', '<?php echo $reaction; ?>');">
        <div class="col-sm-12">
            <div class="row">
                <div class="icon-cnt">
                    <img src="<?php echo base_url(); ?>assets/img/reactions/<?php echo $reaction; ?>.png" alt="<?php echo $reaction; ?>" class="img-reaction">
                    <label class="label reaction-num-votes"><?= $reaction_vote; ?></label>
                </div>
            </div>
            <div class="row">
                <p class="text-center">
                    <label class="label label-reaction <?php echo (is_reaction_voted($reactions->post_id, $reaction) == true) ? 'label-reaction-voted' : ''; ?>"><?php echo trans($reaction); ?></label>
                </p>
            </div>
        </div>
    </div>
<?php else:
    if (is_reaction_voted($reactions->post_id, $reaction) == true): ?>
        <div class="col-reaction col-reaction-like" onclick="add_reaction('<?php echo $reactions->post_id; ?>', '<?php echo $reaction; ?>');">
            <div class="col-sm-12">
                <div class="row">
                    <div class="icon-cnt">
                        <img src="<?php echo base_url(); ?>assets/img/reactions/<?php echo $reaction; ?>.png" alt="<?php echo $reaction; ?>" class="img-reaction">
                        <label class="label reaction-num-votes"><?= $reaction_vote; ?></label>
                    </div>
                </div>
                <div class="row">
                    <p class="text-center">
                        <label class="label label-reaction <?php echo (is_reaction_voted($reactions->post_id, $reaction) == true) ? 'label-reaction-voted' : ''; ?>"><?php echo trans($reaction); ?></label>
                    </p>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="col-reaction col-reaction-like col-disable-voting">
            <div class="col-sm-12">
                <div class="row">
                    <div class="icon-cnt">
                        <img src="<?php echo base_url(); ?>assets/img/reactions/<?php echo $reaction; ?>.png" alt="<?php echo $reaction; ?>" class="img-reaction">
                        <label class="label reaction-num-votes"><?= $reaction_vote; ?></label>
                    </div>
                </div>
                <div class="row">
                    <p class="text-center">
                        <label class="label label-reaction"><?php echo trans($reaction); ?></label>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
