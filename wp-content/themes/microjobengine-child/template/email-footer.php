<?php
/**
 * Template for Email Footer
 */
$mail_footer = apply_filters('ae_get_mail_footer', '');
if ($mail_footer != '') return $mail_footer;

$info = apply_filters('ae_mail_footer_contact_info', get_option('blogname') . ' <br>');

$mail_footer = '</td>
                        </tr>
                        <tr>
                            <td style="background: #ffffff; color: #222222; line-height: 20px; padding: 10px 20px;"><a href="' . home_url() . '"> ' . $info . '</a></td>
                        </tr>
                        </table>
                    </div>
                    </div>
                    </body>
                    </html>';
echo $mail_footer;