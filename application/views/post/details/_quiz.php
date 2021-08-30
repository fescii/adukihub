<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="quiz-container">
    <?php $item_count = 1;
    if (!empty($quiz_questions)):
        foreach ($quiz_questions as $question): ?>
            <div id="quiz_question_<?php echo $question->id; ?>" class="quiz-question" data-is-last-question="<?php echo (count($quiz_questions) == $item_count) ? '1' : '0'; ?>">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="title">
                            <?php echo $item_count . ". " . html_escape($question->question); ?>
                        </h3>
                        <div class="description font-text"><?php echo $question->description; ?></div>
                        <?php if (!empty($question->image_path)):
                            $question_img_base_url = base_url();
                            if ($question->image_storage == "aws_s3") {
                                $question_img_base_url = $this->aws_base_url;
                            } ?>
                            <div class="question-image">
                                <img src="<?php echo IMG_PATH_BG_LG; ?>" data-src="<?= $question_img_base_url . $question->image_path; ?>" alt="<?php echo html_escape($question->question); ?>" class="lazyload img-responsive"/>
                            </div>
                        <?php endif; ?>
                        <?php $question_answers = get_quiz_question_answers($question->id);
                        if (!empty($question_answers)):?>
                            <div class="question-answers">
                                <div class="row row-answer">
                                    <?php $i = 1;
                                    foreach ($question_answers as $answer): ?>
                                        <?php if ($question->answer_format == 'small_image'): ?>
                                            <div class="col-xs-12 col-sm-4 col-answer answer-format-image">
                                                <div id="question_answer_<?php echo $answer->id; ?>" class="answer answer_<?php echo $post->post_type; ?>" data-post-id="<?php echo $post->id; ?>" data-question-id="<?php echo $question->id; ?>" data-answer-id="<?php echo $answer->id; ?>" data-answer-assigned-id="<?php echo $answer->assigned_result_id; ?>">
                                                    <?php if (!empty($answer->image_path)):
                                                        $answer_img_base_url = base_url();
                                                        if ($answer->image_storage == "aws_s3") {
                                                            $answer_img_base_url = $this->aws_base_url;
                                                        } ?>
                                                        <div class="answer-image">
                                                            <img src="<?php echo IMG_BASE64_1x1; ?>" data-src="<?= $answer_img_base_url . $answer->image_path; ?>" alt="<?php echo html_escape($answer->answer_text); ?>" class="lazyload img-responsive"/>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="answer-bottom">
                                                        <div class="answer-radio">
                                                            <i class="quiz-answer-icon icon-circle-outline"></i>
                                                        </div>
                                                        <div class="answer-text">
                                                            <span><?php echo html_escape($answer->answer_text); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($i % 3 == 0): ?>
                                                <div class="col-sm-12"></div>
                                            <?php endif; ?>
                                        <?php elseif ($question->answer_format == 'large_image'): ?>
                                            <div class="col-xs-12 col-sm-6 col-answer answer-format-image">
                                                <div id="question_answer_<?php echo $answer->id; ?>" class="answer answer_<?php echo $post->post_type; ?>" data-post-id="<?php echo $post->id; ?>" data-question-id="<?php echo $question->id; ?>" data-answer-id="<?php echo $answer->id; ?>" data-answer-assigned-id="<?php echo $answer->assigned_result_id; ?>">
                                                    <?php if (!empty($answer->image_path)):
                                                        $answer_img_base_url = base_url();
                                                        if ($answer->image_storage == "aws_s3") {
                                                            $answer_img_base_url = $this->aws_base_url;
                                                        } ?>
                                                        <div class="answer-image">
                                                            <img src="<?php echo IMG_BASE64_1x1; ?>" data-src="<?= $answer_img_base_url . $answer->image_path; ?>" alt="<?php echo html_escape($answer->answer_text); ?>" class="lazyload img-responsive"/>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="answer-bottom">
                                                        <div class="answer-radio">
                                                            <i class="quiz-answer-icon icon-circle-outline"></i>
                                                        </div>
                                                        <div class="answer-text">
                                                            <span><?php echo html_escape($answer->answer_text); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if ($i % 2 == 0): ?>
                                                <div class="col-sm-12"></div>
                                            <?php endif; ?>
                                        <?php elseif ($question->answer_format == 'text'): ?>
                                            <div class="col-xs-12 col-sm-12 col-answer answer-format-text">
                                                <div id="question_answer_<?php echo $answer->id; ?>" class="answer answer_<?php echo $post->post_type; ?>" data-post-id="<?php echo $post->id; ?>" data-question-id="<?php echo $question->id; ?>" data-answer-id="<?php echo $answer->id; ?>" data-answer-assigned-id="<?php echo $answer->assigned_result_id; ?>">
                                                    <div class="answer-radio">
                                                        <i class="quiz-answer-icon icon-circle-outline"></i>
                                                    </div>
                                                    <div class="answer-text">
                                                        <span><?php echo html_escape($answer->answer_text); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php $i++;
                                    endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <div class="alert alert-success" role="alert">
                            <h4 class="text"><i class="icon-check"></i>&nbsp;&nbsp;<?php echo trans("correct_answer"); ?></h4>
                        </div>
                        <div class="alert alert-danger" role="alert">
                            <h4 class="text"><i class="icon-cross"></i>&nbsp;&nbsp;<?php echo trans("wrong_answer"); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $item_count++;
        endforeach;
    endif; ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div id="quiz_result_container"></div>
        </div>
        <div class="col-xs-12 col-sm-12 btn-play-again-content">
            <button type="button" class="btn btn-xl btn-custom" onclick="window.location.reload(); parent.scrollTo(0,0);"><i class="icon-refresh"></i><?php echo trans("play_again"); ?></button>
        </div>
    </div>
</div>


