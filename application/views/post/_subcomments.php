<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $subcomments = get_subcomments($parent_comment->id); ?>
<?php if (!empty($subcomments)): ?>
    <div class="col-12">
        <div class="comments">
            <ul class="comment-list">
                <?php foreach ($subcomments as $subcomment): ?>
                    <li>
                        <div class="left">
                            <?php if (!empty($subcomment->user_slug)): ?>
                                <a href="<?= generate_profile_url($subcomment->user_slug); ?>">
                                    <img src="<?= get_user_avatar($subcomment->user_avatar); ?>" alt="<?php echo html_escape($subcomment->name); ?>">
                                </a>
                            <?php else: ?>
                                <img src="<?= get_user_avatar($subcomment->user_avatar); ?>" alt="<?php echo html_escape($subcomment->name); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="right">
                            <div class="row-custom">
                                <?php if (!empty($subcomment->user_slug)): ?>
                                    <a href="<?= generate_profile_url($subcomment->user_slug); ?>" class="username">
                                        <?php echo html_escape($subcomment->name); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="username"><?php echo html_escape($subcomment->name); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="row-custom comment">
                                <?php echo html_escape($subcomment->comment); ?>
                            </div>
                            <div class="row-custom">
                                <div class="comment-meta">
                                    <span class="date"><?php echo time_ago($subcomment->created_at); ?></span>
                                    <a href="javascript:void(0)" class="btn-comment-like<?= is_comment_voted($subcomment->id) ? ' comment-liked' : ''; ?><?= check_comment_owner($subcomment) ? ' comment-own' : ''; ?>" data-comment-id="<?= $subcomment->id; ?>"><i class="icon-like"></i>&nbsp;<?= trans("like"); ?>&nbsp;(<span id="lbl_comment_like_count_<?php echo $subcomment->id; ?>"><?= $subcomment->like_count; ?></span>)</a>
                                    <?php if ($this->auth_check):
                                        if ($subcomment->user_id == $this->auth_user->id || $this->auth_user->role == "admin"): ?>
                                            <a href="javascript:void(0)" class="btn-delete-comment" onclick="delete_comment('<?php echo $subcomment->id; ?>','<?php echo $post->id; ?>','<?php echo trans("message_comment_delete"); ?>');">&nbsp;<i class="icon-trash"></i>&nbsp;<?php echo trans("btn_delete"); ?></a>
                                        <?php endif;
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
