<script type="text/javascript">
    var is_wp_cron = <?php echo cl_cron::is_wp_cron() ? 'true' : 'false'; ?>;
    <?php 
        $time = ini_get('max_execution_time');
        if ($time != 0) {
            echo 'var wp_cron_time = ' . ( $time + 25 );
        } else {
            echo 'var wp_cron_time = 0';
        }
    ?>

</script>
<?php if (cl_cron::isset_task(0)) { 
    ?>
    <div class="task-bar" id="task-running">
        <div class="task-title animate" style="text-transform:uppercase;font-weight: 600; color: green;">
            <?php lang::get('Processing of link creation');?> <span class="title-delete"><?php lang::get('can be started');?></span>
        </div>
        <div class="task-description">
            <?php lang::get('You can start the process of link creation OR just add additional tasks to the queue');?>
        </div>
        <div class="logs" style="display: none;">
            <div class="log-title">
                <div class="title">
                    <?php lang::get('Processing')?>...&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
                <div class="loading">
                    <img src="<?php echo plugins_url('/assets/img/wpadmload.gif', dirname(__FILE__) );?>">
                </div>
            </div>
            <div class="log-content">
                <div class="log-content-box">
                </div>

                <div class="process-bar">
                    <div class="inline progress-box" id="tasks-process" >
                        <div class="progress-text"> 
                            <?php lang::get('Pages &amp; Articles: ')?>
                            <span class="stats-tasks" style="display: inline;"><span id="count_tasks">0</span> <?php lang::get('of') ?> <span id="all_tasks">0</span></span> <span class="procent-progress" id="procent_tasks">0%</span>
                        </div>
                        <div class="progress-bar procent-tasks" style="width: 0%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="task-buttons">
            <center>
                <?php if (cl_cron::isset_task()) { 

                    ?>
                    <div class="one-button big">
                        <input class="button button-primary" id="task_start" type="button" value="<?php lang::get('Start links creation');?>" onclick="processLinks('start')">
                    </div>
                    <div class="one-button big stop-button-task" style="display: none;">
                        <input class="button button-stop" id="task_stop" type="button" disabled="disabled" value="<?php lang::get('Stop linking processing');?>" onclick="processLinks('stop');">
                    </div>
                    <?php 
                        if ( isset($_GET['autotask']) ) {
                        ?>
                        <script type="text/javascript">
                            jQuery(document).ready(function(){
                                processLinks('start');
                            })
                        </script>
                        <?php
                        }
                    } else {
                    ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function(){
                            stopShows();
                            getLog(true);
                            goToByScroll('task-running');
                        })
                    </script>
                    <?php
                } ?>
                <div class="one-button">
                    <input class="button" id="task_clear" type="button" value="<?php lang::get('Clear all cron tasks & all logs');?>" onclick="processLinks('clear');">
                </div>
            </center>
            <div class="clear"></div>
        </div>
    </div>

    <?php 

    }
?>