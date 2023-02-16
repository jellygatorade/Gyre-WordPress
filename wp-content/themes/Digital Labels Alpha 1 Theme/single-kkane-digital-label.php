<?php get_header();?>


<!--
<svg id="fader"></svg>
-->
<!-- Script here is for the fade in and out of page on load and away navigation. -->
<!--
<script type="text/javascript">

    function fadeInPage() {
            if (!window.AnimationEvent) { return; }
            var fader = document.getElementById('fader');
            fader.classList.add('fade-out');
            }

    fadeInPage();

    document.addEventListener('DOMContentLoaded', function() {
        if (!window.AnimationEvent) { return; }
        var anchors = document.getElementsByTagName('a');
        for (var idx=0; idx<anchors.length; idx+=1) {
            if (anchors[idx].hostname !== window.location.hostname) {
                continue;
            }
            anchors[idx].addEventListener('click', function(event) {
                var fader = document.getElementById('fader'),
                anchor = event.currentTarget;
                var listener = function() {
                    window.location = anchor.href;
                    fader.removeEventListener('animationend', listener);
                };
                fader.addEventListener('animationend', listener);
                event.preventDefault();
                fader.classList.add('fade-in');
    });
    }

    });

    window.addEventListener('pageshow', function (event) {
        if (!event.persisted) {
            return;
        }
        var fader = document.getElementById('fader');
        fader.classList.remove('fade-in');
    });

</script>
-->

<div class="flexHorizontal padding10px">
    <div class="flexChildOnlyLeft">

        <!--
        <ul>
            <?php 
            /*

            // Polylang plugin language switcher, see https://polylang.pro/doc/function-reference/#pll_the_languages
            $languagesArgs = array(
                'dropdown' => 0,
                'show_names' => 1,
                'display_names_as' => 'name',
                'show_flags' => 0,
                'hide_if_empty' => 1,
                'force_home' => 0,
                'echo' => 1,
                'hide_if_no_translation' => 1,
                'hide_current'=> 0,
                'post_id' => null,
                'raw' => 0,
            );

            //pll_the_languages(); //default arguments
            pll_the_languages( $languagesArgs ); //customized arguments
            */
            ?>
        </ul>
        -->

        <?php while ( have_posts() ) : the_post(); ?>

            <p>Debug: this get_post_type result is: <?php echo get_post_type( $post->ID ); ?></p>
            <p>Debug: the kkane_digital_label post type object: <?php var_dump(get_post_type_object( 'kkane-digital-label' )); ?></p>

            <br>
            <p class="nameandtitle"><?php echo get_post_meta($post->ID, 'Artist', true); ?></p>
            <p><?php echo get_post_meta($post->ID, 'Artist Nationality, Birth Year - Death Year', true); ?></p>
            <br>
            <i><h1 class="nameandtitle"><?php the_title(); ?></h1></i>
            <p><?php echo get_post_meta($post->ID, 'Date', true); ?></p>
            <p><?php echo get_post_meta($post->ID, 'Medium', true); ?></p>
            <p class="creditline"><?php echo get_post_meta($post->ID, 'Credit Line', true); ?></p>
            <br>
            <?php the_content(); ?>

        <?php endwhile; ?>

    </div>
</div>

<?php get_footer();?>

<style>

    body {
        border: 0px;
        padding: 0px;
        margin: 0px;

        font-family: 'Verlag-Book';
    }

    ul {
        border: 0px;
        padding: 0px;
        margin: 0px;
    }

    /* Displays the ul list items horizontally */
    ul > li {
        display: inline-block;
        padding-right: 10px;
        zoom: 1;
        *display: inline;
        font-size: 16px;
    }

    p {
        border: 0px;
        padding: 0px;
        margin: 0px; 
        font-size: 16px;
    }

    h1 {
        border: 0px;
        padding: 0px;
        margin: 0px; 
        font-size: 16px;
    }

    select {
        color: blue;
        font-family: 'Verlag-Book';
    }

    option {
        font-family: 'Verlag-Book';
    }

    .flexHorizontal {
        display: flex;
        flex-direction: row;
        justify-content: /* flex-start */ center;
    }

    .padding10px {
        padding: 10px;
    }

    .flexChildOnlyLeft {
        max-width: 700px;
    }

    .nameandtitle {
        font-size: 26px;
    }

    .creditline {
        font-size: 13px;
    }

    /* Below is for fade in and fade out on page load */
    #fader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 999999;
      pointer-events: none;
      background: #FFFFFF;
      animation-duration: 500ms;
      animation-timing-function: ease-in-out;
    }

    @keyframes fade-out {
        from { opacity: 1 }
          to { opacity: 0 }
    }

    @keyframes fade-in {
        from { opacity: 0 }
          to { opacity: 1 }
    }

    #fader.fade-out {
        opacity: 0;
        animation-name: fade-out;
    }

    #fader.fade-in {
        opacity: 1;
        animation-name: fade-in;
    }

</style>