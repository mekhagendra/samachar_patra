<?php
/**
 * The template for displaying the footer
 *
 * @package Samachar_Patra
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

    </div><!-- #content -->

    <?php
    // Include the organized footer component
    get_template_part('templates/parts/footer/footer');
    ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>