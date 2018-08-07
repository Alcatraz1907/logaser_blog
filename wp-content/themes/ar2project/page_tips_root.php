<?php
/**
 * Template Name: Tips Root Template
 * Template Post Type: post, page, product
 */
include("header_tips_root.php"); ?>
    <div class="tips-root-page">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="container">
            <div class="row">
                <div class="page-links col-sm-12 col-md-12 col-lg-12">
                    <a href="/"> <?= __( 'Home' )?> </a>/
                    <a href="/blog/"> <?= __( 'Blog' )?> </a>/
                    <a href="/blog/tips/"><?= get_page_by_path(TIPS_SLUG)->post_title?></a>
                </div>
            </div>
<!--        Header block-->
        <!--
            <div class="row page-title">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <h1 class="title">Logo tips catalogue</h1>
                        <div class="description">Fusce vehicula dolor arcu, sit amet blandit dolor mollis nec. Donec viverra eleifend lacus, vitae ullamcorper metus. Sed sollicitudin ipchild quis
                            <br>nunc sollicitudin ultrices. Donec euismod scelerisque ligula. Maecenas eu varius risus, eu aliquet arcu. Curabitur fermentum suscipit est, tincidunt
                            <br>mattis lorem luctus id. Donec eget.
                        </div>
                    </div>
                </div>
            </div>
        -->

            <?php echo get_post()->post_content ?>
            <div class="row content">
                <!-- content -->
                <div class="row">
                    <?php
                        $all_tips = get_children(
                            array(
                                'post_parent' => get_page_by_path(TIPS_SLUG)->ID,
                                'post_type'   => 'page',
                                'post_status' => 'publish'
                            )
                        );
                    ?>

                    <?php if ($all_tips):?>
                        <?php foreach ( $all_tips as $tip ):?>
                            <div class="tips">
                                <div class="col-sm-12 col-md-12 col-lg-12 parent-tip">
                                    <a href="<?= $tip->guid ?>"><?= get_secondary_title($tip->ID) ?></a>
                                </div>
                                    <?php
                                        $tip_childrens = get_children(
                                            array(
                                                'post_parent' => $tip->ID,
                                                'post_type'   => 'page',
                                                'post_status' => 'publish'
                                            )
                                        );

                                        if($tip_childrens):?>

                                            <?php foreach ( $tip_childrens as $tip_children):?>
                                                <a href="<?= $tip_children->guid ?>">
                                                    <div class="child-tip">
                                                        <div class="logo-block">
                                                            <?php echo get_the_post_thumbnail($tip_children->ID, 'post-thumbnail', array("class"=>"logotype")) ?>
                                                        </div>
                                                        <div class="tip-name"><?= get_secondary_title($tip_children->ID); ?></div>
                                                        <div class="tip-creation-date"><?= date("M d, Y", strtotime($tip_children->post_date)) ?></div>
                                                    </div>
                                                </a>
                                            <?php endforeach;?>
                                        <?php endif; ?>
                            </div>
                        <hr>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>

                <!-- content -->
                <?php endwhile; else: ?>

                    <?php ar2_post_notfound() ?>

                <?php endif; ?>

            </div>
        </div>
        <!-- #content -->
    </div>
</div>  <!-- logaster block -->
<?php include("footer_tips_root.php"); ?>