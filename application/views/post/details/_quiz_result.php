<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (!empty($result)): ?>
    <div class="quiz-result">
        <h4 class="title">
            <?php echo html_escape($result->result_title); ?>
        </h4>
        <?php if (!empty($result->image_path)):
            $result_img_base_url = base_url();
            if ($result->image_storage == "aws_s3") {
                $result_img_base_url = $this->aws_base_url;
            } ?>
            <img src="<?= $result_img_base_url . $result->image_path; ?>" alt="<?php echo html_escape($result->result_title); ?>" class="img-responsive"/>
        <?php endif; ?>
        <div class="description">
            <?php echo $result->description; ?>
        </div>
    </div>
<?php endif; ?>