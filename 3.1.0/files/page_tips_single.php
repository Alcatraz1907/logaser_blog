<?php
/**
 * Template Name: Tips Single Template
 * Template Post Type: post, page, product
 */
include("header_tips_single.php"); ?>
<div class="tips-root-page">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php ar2_above_post() ?>

    <div class="container">
        <div class="row page-title">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="page-links">
                    <a href="/">Home </a>/
                    <a href="/blog/">Blog </a>/
                    <a href="/blog/tips/">Tips</a>
                </div>

                <div class="title text-center">
                    <h1><?php the_title() ?></h1>
                </div>

                <div class="social-buttons">
                    <?php if ( ar2_get_theme_option( 'post_display[post_social]' ) ) : ?>
                        <div class="entry-social">
                            <div class="addthis_toolbox addthis_default_style">
                                <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                                <a class="addthis_button_tweet"></a>
                                <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
                                <a class="addthis_counter addthis_pill_style"></a>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="row content">

        <?php the_content();?>

<!-- content -->
    <!--
            <div class="row content-block">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <h2 class="title">Во всем мире аптеки легко узнаваемы по кресту на эмблеме</h2>
                    <div class="description">
                        Во всем мире аптеки легко узнаваемы по кресту на эмблеме. Змея, обвивающая бокал, не менее популярна в качестве аптечного лого. Чаще всего крест, как символику, не заключают в рамку, хотя, если правильно подобран цвет, рамка станет прекрасным дополнением логотипа. К примеру, рамку можно раскрасить зеленым, а знак на ней – белым.
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/family-pharmacy-bg.png">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/family-pharmacy.png" width="220" height="280">
                </div>
            </div>

            <div class="row content-block">
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/panax-pharma-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/panax-pharma.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/atlantic-pharmacy-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/atlantic-pharmacy.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/natural-pharmacy-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/natural-pharmacy.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/turn-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/turn.png" width="220" height="280">
                </div>
            </div>
            <hr>
            <div class="row content-block">
                <div class="col-sm-6 col-md-6 col-lg-6 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/modern-phramacy-bg.png" width="580" height="360">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/modern-phramacy.png" width="220" height="280">
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <h2 class="title">Hе обязательно придерживаться уже давно заезженных идей</h2>
                    <div class="description">
                        Но, при создании нового, современного логотипа не обязательно придерживаться уже давно заезженных идей. Иногда логотип можно стилизовать и под таблетку, выбрав круглую форму логотипа с характерным делением. Яркими примерами нестандартных решений являются логотипы: CVS Pharmacy, Live OAK Pharmacy, Thomas Pharmacy.
                    </div>
                </div>
            </div>

            <div class="row content-block">
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/little-white-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/oak.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/little-white-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/tomas-phramacy.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/little-white-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/cvs-phramacy.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/little-white-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/cvs-phramacy.png" width="220" height="280">
                </div>
            </div>

            <hr>

            <div class="row content-block">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <h2 class="title">Легко узнаваемые логотипы во всем мире аптек</h2>
                    <div class="description">
                        Цвет вывески – это тоже немаловажный фактор. Так уж сложилось, что наиболее удачно с аптекой ассоциируются белый, зеленый и голубой цвета – к ним почему–то у нас больше доверия. Чтобы «разбавить» холодные цвета, можно добавить немного красного.
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/big-white-bg.png" width="580" height="360">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/puyue.png" width="220" height="280">
                </div>
            </div>

            <div class="row content-block">
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/pottstown-hospital-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/pottstown-hospital.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/beautiful-pharmacy-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/beautiful-pharmacy.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/amazing-pharmacy-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/amazing-pharmacy.png" width="220" height="280">
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/little-white-bg.png" width="280" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/10-pharmacy.png" width="220" height="280">
                </div>
            </div>

            <hr>

            <div class="row content-block">
                <div class="col-sm-6 col-md-6 col-lg-6 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/big-white-bg.png" width="580" height="360">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/dobrogo-dnya.png" width="220" height="280">
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <h2 class="title">Стилизованные дизайнерские идеи для логотипов аптек</h2>
                    <div class="description">
                        Интересная эмблема у сети аптек Родина фарм, которая использует стилизованный зеленый крест, такой же идеи придерживается аптека "Доброго дня". Сеть аптек Style Фарма использует белый крест на малиновом фоне.
                    </div>
                </div>
            </div>

            <div class="row content-block">
                <div class="col-sm-4 col-md-4 col-lg-4 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/middle-white-bg.png" width="380" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/dobrogo-dnya-2.png" width="220" height="280">
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/middle-white-bg.png" width="380" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/style-pharma.png" width="220" height="280">
                </div>
                <div class="col-sm-4 col-md-4 col-lg-4 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/middle-white-bg.png" width="380" height="240">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/atlantic-pharmacy-white.png" width="220" height="280">
                </div>

            </div>

            <hr>

            <div class="row content-block">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <h2 class="title">Готовые дизайнерские решения в нашей галерее</h2>
                    <div class="description">
                        Перед тем, как нажать кнопку «Создать», советуем просмотреть готовые дизайнерские решения в нашей галерее, которые используются отечественными и зарубежными компаниями.
                    </div>
                    <a href="#" class="btn btn-link">Перейти в портфолио</a>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 logo-block">
                    <img class="background-logo" src="/blog/wp-content/uploads/2018/06/idea-bg.png" width="580" height="360">
                    <img class="logotype" src="/blog/wp-content/uploads/2018/06/idea.png" width="220" height="280">
                </div>
            </div>

            <div class="example-logos">
                <div class="logo-column">
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/antarctica-bg.png" width="180" height="160">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/antarctica.png" width="220" height="280">
                    </div>
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/kittypic-bg.png" width="180" height="160">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/kittypic.png" width="220" height="280">
                    </div>
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/go-tickets-bg.png" width="180" height="260">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/go-tickets.png" width="220" height="280">
                    </div>
                </div>

                <div class="logo-column">
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/best-logo-bg.png" width="180" height="160">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/best-logo.png" width="220" height="280">
                    </div>
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/bar-code-bg.png" width="180" height="260">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/bar-code.png" width="220" height="280">
                    </div>
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/invasione-creativa-bg.png" width="180" height="160">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/invasione-creativa.png" width="220" height="280">
                    </div>
                </div>

                <div class="logo-column">
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/union-of-moscow-bg.png" width="180" height="160">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/union-of-moscow.png" width="220" height="280">
                    </div>
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/sayulita-bg.png" width="180" height="160">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/sayulita.png" width="220" height="280">
                    </div>
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/music-dog-bg.png" width="180" height="260">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/music-dog.png" width="220" height="280">
                    </div>
                </div>

                <div class="logo-column">
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/shop-wise-bg.png" width="180" height="260">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/shop-wise.png" width="220" height="280">
                    </div>
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/loveclip-bg.png" width="180" height="160">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/loveclip.png" width="220" height="280">
                    </div>
                    <div class="logo-block">
                        <img class="background-logo" src="/blog/wp-content/uploads/2018/06/spartan-bg.png" width="180" height="160">
                        <img class="logotype" src="/blog/wp-content/uploads/2018/06/spartan.png" width="220" height="280">
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="gallery-subject-page">
                    <div class="row">
                        <div class="create-logo-section text-center">
                            <h2 class="title">How to create an abstract logo?</h2>
                            <div class="description">Let’s start right now. Click on ‘Create’ to see what Logaster has to offer. In just a couple of minutes, you can choose the best logo out of dozens of options.
                               Edit your selected logo, if you wish, and download it in a print-ready format.</div>

                            <section class="row bottom-create-logo text-center">
                                <div class="col-md-12 bottom-create-logo">
                                    <a href="/logo/" class="btn btn-link" ga-action="TryItBtn" ga-data="BottomCreateBTN">create abstract logo</a>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        -->
            <!-- content -->
            <?php endwhile; else: ?>

                <?php ar2_post_notfound() ?>

            <?php endif; ?>


<!-- блок для вивовду всіх тіпсів -->
            <div class="row logo-creation-tips">
                <div class="create-tips-title">
                    <h2 class="title">Related Logo Creation Tips</h2>
                    <div class="description">The ability to change the logo  Feel free to edit your logo when you wish without additional payments.</div>
                </div>
                <?php
                
                $all_tips = get_children(
                    array(
                        'post_parent' => get_page_by_title(TIPS_SLUG)->ID,
                    )
                );
                ?>
                <div class="tips-block">
                    <?php if ($all_tips):?>
                        <?php foreach ( $all_tips as $tip ):?>
                            <div class="tips">
                                <div class="parent-tip">
                                    <a href="<?= $tip->guid ?>"><?= $tip->post_title ?></a>
                                </div>
                                <?php

                                $tip_childrens = get_children(
                                    array(
                                        'post_parent' => $tip->ID,
                                    )
                                );

                                if($tip_childrens):?>
                                    <div class="child-tip">
                                        <div class="tip-name">
                                            <?php foreach ( $tip_childrens as $tip_children):?>
                                                <a href="<?= $tip_children->guid ?>">
                                                    <?= $tip_children->post_title ?>,
                                                </a>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach;?>
                    <?php endif;?>
                </div>
            </div>

        </div>
    </div><!-- #content -->
</div><!-- logaster block -->
<?php include("footer_tips_single.php"); ?>