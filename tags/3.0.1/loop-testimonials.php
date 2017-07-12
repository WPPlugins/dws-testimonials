<?php 
 /* We are looping through over testimonials now!! */ 
 ?>
 
    <div class="testimonialBlock">
        <?php if(has_post_thumbnail()): ?>
        <?php if($pic == "yes"): ?>
        <div class="testi_image"><?php the_post_thumbnail(array(150,150)); ?></div>
        <?php endif; ?>        
        <div class="testi_text">
        <?php else: ?>
        <div class="testi_text" style="width: 100%;">
        <?php endif; ?>
            <?php the_content('Read More >>'); ?>
            <?php if($name == "yes"): $getName = get_post_custom_values('customer_details_full_name',$post->ID) ?>
            <span class="testi_name"><?php  echo $getName[0] ?>, </span> 
            <?php endif; ?>
            <?php if($age == "yes"): $getAge = get_post_custom_values('customer_details_age',$post->ID) ?>
            <span class="testi_age"><?php  echo $getAge[0] ?>, </span> 
            <?php endif; ?>
            <?php if($position == "yes"): $getPosition = get_post_custom_values('customer_details_position',$post->ID) ?>
            <span class="testi_position"><?php  echo $getPosition[0] ?>, </span> 
            <?php endif; ?>
            <?php if($company == "yes"): $getCompany = get_post_custom_values('customer_details_company',$post->ID) ?>
            <span class="testi_company">
                <?php 
                    $http = "javascript:;";
                    if($website == "yes"): 
                        $getWebsite = get_post_custom_values('customer_details_website',$post->ID);
                        $http = $getWebsite[0];
                    endif;
                ?>
                <a href="<?php echo $http; ?>" target="_blank"><?php  echo $getCompany[0] ?></a> 
            </span> 
            <?php endif; ?>
            <?php if($email == "yes"): $getEmail = get_post_custom_values('customer_details_e-mail',$post->ID) ?>
            <span class="testi_email"><?php echo $getEmail[0] ?></span> 
            <?php endif; ?>
        </div>
        <div class="clearthis"></div>
        <hr class="testi_hr" />
    </div>