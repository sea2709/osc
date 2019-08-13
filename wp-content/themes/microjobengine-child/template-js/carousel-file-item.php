<script type="text/template" id="ae_carousel_file_template">
    <# if(typeof attach_id === 'undefined') { #>
    <li class="image-item">
        <a href="{{= url}}"><i class="fa fa-paperclip"></i> <span class="item-name">{{= name }}</span></a>
        <a href="" title="<?php _e("Delete", 'enginethemes'); ?>" class="delete-img delete"><i class="fa fa-times"></i></a>
    </li>
    <# } #>
    <# if(typeof attach_id !== 'undefined') { #>
    <li class="image-item" id="{{= attach_id }}">
        <a href="#"><i class="fa fa-paperclip"></i> <span class="item-name">{{= name }}</span></a>
        <a href="" title="<?php _e("Delete", 'enginethemes'); ?>" class="delete-img delete"><i class="fa fa-times"></i></a>
    </li>
    <# } #>
</script>