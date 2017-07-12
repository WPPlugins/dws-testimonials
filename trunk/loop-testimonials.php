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
            <?php 
             $getName = get_post_custom_values('customer_details_full_name',$post->ID) ;
            if($name == "yes" && strlen(trim($getName[0]))):?>
            <span class="testi_name"><?php  echo $getName[0] ?>, </span> 
            <?php endif; ?>
            <?php 
            $getAge = get_post_custom_values('customer_details_age',$post->ID);
            if($age == "yes" && strlen(trim($getAge[0]))):  ?>
            <span class="testi_age"><?php  echo $getAge[0] ?>, </span> 
            <?php endif; ?>
            <?php 
            $getPosition = get_post_custom_values('customer_details_position',$post->ID) ;
            if($position == "yes" && strlen(trim($getPosition[0]))): ?>
            <span class="testi_position"><?php  echo $getPosition[0] ?>, </span> 
            <?php endif; ?>
            <?php 
            $getCompany = get_post_custom_values('customer_details_company',$post->ID) ;
            if($company == "yes" && strlen(trim($getCompany[0]))): ?>
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
            <?php 
             $getEmail = get_post_custom_values('customer_details_e-mail',$post->ID);
            if($email == "yes" && strlen(trim($getEmail[0]))): ?>
            <span class="testi_email"><?php echo $getEmail[0] ?></span> 
            <?php endif; ?>          
            <?php 
            $get = get_post_custom_values('customer_details_city',$post->ID) ;
            if($city == "yes" && strlen(trim($get[0]))):?>
            <span class="testi_name"><strong><?php  echo $get[0] ?></strong> </span> 
            <?php endif; ?>
            <?php 
            $get = get_post_custom_values('customer_details_state/province',$post->ID);
            if($state == "yes" && strlen(trim($get[0]))):  ?>
                <span class="testi_name"><?php  echo $get[0] ?> </span> 
            <?php endif; ?>
            <?php 
            $get = get_post_custom_values('customer_details_country',$post->ID) ;
            if($country == "yes" && strlen(trim($get[0]))): ?>
                <span class="testi_name"><strong><?php  echo $get[0] ?></strong></span> 
            <?php endif; ?>            
        </div>
        <div class="clearthis"></div>
        <hr class="testi_hr" />
    </div>