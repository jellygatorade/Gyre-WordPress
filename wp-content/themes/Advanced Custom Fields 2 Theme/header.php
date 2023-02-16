<!DOCTYPE html>

<html>

    <head>

        <?php wp_head();?> <!-- fires the wp_head action https://developer.wordpress.org/reference/functions/wp_head/ -->
        <!-- Comment -->

    </head>

    <body <?php body_class();?>> <!-- https://developer.wordpress.org/reference/functions/body_class/ -->
    <p> Hello world header text </p>

    <!--
    <header class="sticky-top">
        <div class="container">
            <?php 
            /*
            wp_nav_menu(
                array(
                    'theme-location' => 'top-menu',
                )
            );
            */
            ?>
        </div>
    </header>
    -->