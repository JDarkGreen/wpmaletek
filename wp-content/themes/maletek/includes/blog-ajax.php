    
    <?php 
        $total = $the_query->max_num_pages;

        if ( $page > 1) :
    ?>
        <a class="paginator-button text-uppercase text-center hidden-xs" href="#" data-paginator="Anterior">
            <img src="<?php echo IMAGES; ?>/arrows-paginator-prev.png" alt="" class="img-responsive" />
            ver anterior <span><?php echo $filter; ?></span>
        </a> <!-- /paginator-button -->
    <?php endif; ?>

    
    <?php while ( $the_query->have_posts() )  : ?>
    
    <?php
       $the_query->the_post();        
       $id = get_the_ID();

       $featured = is_sticky( $id ) ? 'container-content-blog__article--featured' : null;
    ?>
    
        <article class="container-content-blog__article <?php echo $featured; ?>">
       
           <?php 
                $array = array( 'class' => 'img-responsive hidden-xs' );

                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'full' , $array );
                }
           ?>
        
            <div class="container-content-blog__article-caption">
                    <h2><a href="#"><?php the_title(); ?></a></h2>
                    <?php
                        if ( is_sticky() ) {
                            $content = get_the_content( '[ seguir leyendo ]' );
                            $content_mobile = get_the_content( 'Seguir leyendo &raquo;' );
                                
                            echo '<p class="hidden-xs">' . $content . '</p>';

                            echo '<p class="visible-xs-block">' . $content_mobile . '</p>';

                        }else{
                            $year = get_the_date('Y');
                             $content_mobile = get_the_content( 'Seguir leyendo &raquo;' );

                            echo '<p class="hidden-xs">' . $year . '</p>';
                            echo '<p class="visible-xs-block">' . $content_mobile . '</p>';
                        }
                    ?>

            </div> <!-- /container-content-blog__article-caption -->

        </article> <!-- /container-content-blog__article -->


    <?php endwhile; ?>

    <!-- Add the pagination functions here.     -->
    <input type="hidden" id="currrent_post_page" value="<?php echo $page; ?>" />

    <?php if ( $page < $total ) : ?> 
        <a class="paginator-button text-uppercase text-center hidden-xs" href="#" data-paginator="Siguiente">
            <img src="<?php echo IMAGES; ?>/arrows-paginator-next.png" alt="" class="img-responsive" />
            ver más <span><?php echo $filter; ?></span>
        </a> <!-- /paginator-button -->
    <?php endif; ?>

    <nav class="nav_paginator visible-xs-block text-center">
        <ul class="list-inline">
            <li>
                <?php if ( $page > 1 ) : ?>
                <a class="paginator-button" href="#" data-paginator="Anterior">
                    &laquo; Anterior
                </a> <!-- /paginator-button -->
                <?php endif; ?>
            </li>
            <li>
                <?php if ( $page < $total ) : ?>
                <a class="paginator-button" href="#" data-paginator="Siguiente">
                    Siguiente &raquo;
                </a> <!-- /paginator-button -->
                <?php endif; ?>
            </li>
        </ul>
    </nav>  <!-- / nav_paginator -->