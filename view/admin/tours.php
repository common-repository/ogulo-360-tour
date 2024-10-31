<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.ogulo.de/
 * @since      1.0.0
 *
 * @package    Ogulo_360_Tour
 * @subpackage Ogulo_360_Tour/admin/tours
 */
?>

<script>
    function copyTo(id) {

        /* Get the text field */
        var copyText = document.getElementById(id);

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");


    }
</script>


<div id="my_tours_table">
    <table style="width:100%; text-align:left;" class="wp-list-table widefat fixed striped tours">
        <thead>
            <tr>
                <th style="width:15%;"><?php _e('Created on', 'ogulo-360-tour'); ?></th>
                <th style="width:25%;"><?php _e('Titel', 'ogulo-360-tour'); ?></th>
                <th style="width:30%;"><?php _e('Embed as URL', 'ogulo-360-tour'); ?></th>
                <th style="width:30%;"><?php _e('Embed as shortcode', 'ogulo-360-tour'); ?></th>
            </tr>
        </thead>


        <tbody>
            <?php
            $view = '';


            if ($args['tours']) {
                foreach ($args['tours'] as $index => $tour) {


                    if (!empty($args['company']->contract->allow_subdomain) && $args['company']->contract->allow_subdomain == 1) {
                        $url = home_url('tour/' . $tour->short_code);
                        $link = '';
                    } else {
                        $url = 'tour.ogulo.com/' . $tour->short_code;
                        $link = '<a target="_blank" href="https://www.ogulo.com/features/eigene-domain/">Eigene Domain verwenden?</a>';
                    }


                    $size = "'{in px or %}'";
                    $index++;
                    $view .= '<tr class="ogulo-row">';
                    $view .= '<td>' . date_i18n('d.m.Y', strtotime($tour->created_at)) . '</td>';
                    $view .= '<td>' . $tour->headline . '</td>';
                    $view .= '<td class="ogulo-copy"><input  type="text" value="' . $url . '" id="copy-A' . $index . '"><button onclick="copyTo(\'copy-A' . $index . '\')"></button> ' . $link . ' </td>';
                    $view .= '<td class="ogulo-copy"><input  type="text" value="[ogulo_tour name=\'' . $tour->headline . '\' slug=\'' . $tour->short_code . '\' width=\'100%\' height=\'600px\' ]" id="copy-B' . $index . '"><button onclick="copyTo(\'copy-B' . $index . '\')"></button></td>';
                    $view .= '</tr>';
                }
            }
            echo $view;
            ?>
        </tbody>
    </table>
</div>