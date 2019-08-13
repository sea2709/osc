<?php
/**
 * Template for Email Header
 */
$mail_header = apply_filters('ae_get_mail_header', '');
if ($mail_header != '') return $mail_header;

$logo_url = get_template_directory_uri() . "/img/logo-de.png";
$options = AE_Options::get_instance();

// save this setting to theme options
$site_logo = $options->site_logo;
if (!empty($site_logo)) {
    $logo_url = $site_logo['large'][0];
}

$logo_url = apply_filters('ae_mail_logo_url', $logo_url);

$customize = et_get_customization();

$mail_header = '<html>
                        <head>
                        </head>
                        <body style="font-family:"Roboto",OpenSans,"Open Sans",Arial,sans-serif;font-size: 18px;margin: 0; padding: 0; color: #222222;background-color: #f0f0f0">
                        <div style="background:#F0F0F0;padding: 40px;">
                        <div style="margin: 0px auto; width:600px; background: #FFF; border: 1px solid ' . $customize['background'] . '">
                            <table width="100%" cellspacing="0" cellpadding="0">
                            <tr style="background: #2a394e; height: 63px; vertical-align: middle;">
                                <td style="padding: 10px 5px 10px 20px; width: 100%; text-align: center">
                                <a href="' . home_url() . '"> 
                                    <img style="max-height: 100px" src="' . $logo_url . '" alt="' . get_option('blogname') . '">
                                </a>
                                </td>
                            </tr>
                            <tr><td style="height: 5px; background-color: ' . $customize['background'] . ';"></td></tr>
                            <tr>
                                <td style="background: #ffffff; color: #222222; line-height: 26px; padding: 10px 20px; font-size: 18px;">';
echo $mail_header;