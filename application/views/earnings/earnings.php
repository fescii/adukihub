<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo trans("earnings"); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="profile-page-top">
                    <?php $this->load->view("profile/_profile_user_info"); ?>
                </div>
            </div>
        </div>
        <div class="profile-page">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3">
                    <?php $this->load->view("earnings/_tabs"); ?>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-9">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <div class="earnings-box earnings-box-pageviews">
                                <strong><?= $user->total_pageviews; ?></strong>
                                <label><?= trans("total_pageviews"); ?></label>
                                <i class="icon-chart"></i>
                            </div>
                            <div class="earnings-box earnings-box-balance">
                                <strong><?= price_formatted($user->balance); ?></strong>
                                <label><?= trans("balance"); ?></label>
                                <i class="icon-coin-bag"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <script src="<?php echo base_url(); ?>assets/js/jquery-1.12.4.min.js"></script>
                            <script src="<?php echo base_url(); ?>assets/vendor/chart/chart.min.js"></script>
                            <script src="<?php echo base_url(); ?>assets/vendor/chart/utils.js"></script>
                            <script src="<?php echo base_url(); ?>assets/vendor/chart/analyser.js"></script>
                            <div class="content">
                                <div style="min-height: 400px;">
                                    <canvas id="chart-2"></canvas>
                                </div>
                            </div>

                            <div class="table-responsive table-earnings">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col"><?php echo trans("date"); ?></th>
                                        <th scope="col"><?php echo trans("pageviews"); ?></th>
                                        <th scope="col"><?php echo trans("earnings"); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($page_views_counts)):
                                        for ($i = 1; $i <= $number_of_days; $i++):
                                            if ($i <= $today):
                                                $earning = get_earning_object_by_day($i, $page_views_counts);
                                                if (!empty($earning)):?>
                                                    <tr>
                                                        <td><?php echo date("M j, Y", strtotime($earning->date)); ?></td>
                                                        <td><?php echo $earning->count; ?></td>
                                                        <td><?php echo price_formatted($earning->total_amount, $this->decimal_point); ?></td>
                                                    </tr>
                                                <?php endif;
                                            endif;
                                        endfor;
                                    endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <script>
                                var number_of_days = parseInt('<?= $number_of_days; ?>');
                                var presets = window.chartColors;
                                var utils = Samples.utils;
                                var inputs = {
                                    min: 0,
                                    max: 1,
                                    count: 8,
                                    decimals: 2,
                                    continuity: 1
                                };

                                function generateLabels(config) {
                                    var labels = [];
                                    var i;
                                    for (i = 1; i <= number_of_days; i++) {
                                        labels.push(i);
                                    }
                                    return labels;
                                }

                                var options = {
                                    maintainAspectRatio: false,
                                    spanGaps: false,
                                    elements: {
                                        line: {
                                            tension: 0.000001
                                        }
                                    },
                                    plugins: {
                                        filler: {
                                            propagate: false
                                        }
                                    },
                                    scales: {
                                        x: {
                                            ticks: {
                                                autoSkip: false,
                                                maxRotation: 0
                                            }
                                        },
                                        xAxes: [
                                            {
                                                scaleLabel: {
                                                    display: true,
                                                    labelString: ''
                                                }
                                            }
                                        ],
                                        yAxes: [
                                            {
                                                ticks: {
                                                    beginAtZero: true,
                                                    callback: function (label, index, labels) {
                                                        return "<?= $this->general_settings->currency_symbol; ?>" + label.toFixed(4);
                                                    }
                                                }
                                            }
                                        ]
                                    },
                                    tooltips: {
                                        enabled: false
                                    },
                                    legend: {
                                        onClick: null
                                    }
                                };
                                [false, 'origin', 'start', 'end'].forEach(function (boundary, index) {
                                    // reset the random seed to generate the same data for all charts
                                    utils.srand(8);
                                    new Chart('chart-' + index, {
                                        type: 'line',
                                        data: {
                                            labels: generateLabels(),
                                            datasets: [{
                                                backgroundColor: utils.transparentize(presets.green),
                                                borderColor: presets.green,
                                                data: [
                                                    <?php for ($i = 1; $i <= $number_of_days; $i++) {
                                                    if ($i <= $today) {
                                                        if ($i != 1) {
                                                            echo ',';
                                                        }
                                                        $earning = get_earning_object_by_day($i, $page_views_counts);
                                                        if (!empty($earning)) {
                                                            echo number_format($earning->total_amount, $this->decimal_point, ".", "");
                                                        } else {
                                                            echo '0';
                                                        }
                                                    }
                                                } ?>
                                                ],
                                                label: '<?= trans("earnings") ?>: <?= replace_month_name(date("M Y"));?>',
                                                fill: boundary
                                            }]
                                        },
                                        options: Chart.helpers.merge(options, {
                                            title: {
                                                text: 'fill: ' + boundary,
                                                display: false
                                            }
                                        })
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Wrapper End-->


