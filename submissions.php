<?php
$path =  plugin_dir_url(__FILE__); 
$identifier = 'EMAIL_TMPL';
$pagenum = filter_input(INPUT_GET, 'pagenum');
$pagenum = isset($pagenum) ? absint($pagenum) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$j = 1 + $offset;
$year = filter_input(INPUT_GET,'year');
$month = filter_input(INPUT_GET,'month');
// ... set up your argument array for WP_Query:
$args = array( 
    'orderby' => 'date',
    'order' => 'DESC' ,
    'post_type' => 'post',
    'posts_per_page' => -1

    );

if(isset($year) && $year!='')
{
    $args['year'] = $year ;
}

if(isset($month) && $month!='')
{
    $args['monthnum'] = $month;
}





$total_posts = count(get_posts( $args ));

$args['posts_per_page'] = $limit;
$args['paged'] = $pagenum;
$posts = get_posts( $args );

$num_of_pages = ceil( $total_posts/$limit);
$pagination = pm_get_pagination($num_of_pages,$pagenum);

if(isset($_GET['metaguss_export']))
{
    include 'metagauss-export.php';
}

function get_analytics_data_new($postid)
{
    include_once ( GADWP_DIR . 'admin/ajax-actions.php' );
    $backend = new GADWP_Backend_Ajax();
   $data = $backend->custom_item_reports($postid);
   return $data;
   return $postid;
}
?>
<div class="pmagic"> 
  
  <!-----Operationsbar Starts----->
  <form name="blog_manager" id="blog_manager" action="" method="get">
    <input type="hidden" name="page" value="metagauss_dashboard" />
    <input type="hidden" name="pagenum" value="<?php echo $pagenum;?>" />
    <div class="operationsbar">
      <div class="pmtitle">
        <?php _e('Blogs','profilegrid-user-profiles-groups-and-communities');?>
      </div>
      <div class="nav">
        <ul>
            <li>
            <select name="year" id="year">
                <option value="">All</option>
                <?php for($i=2018; $i <=2030; $i++):?>
                <option value="<?php echo $i;?>" <?php selected($i,$year);?> >
                    <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            </li>  
            <li>
            <select name="month" id="month">
                <option value="">All</option>
                <?php for($i=1; $i <=12; $i++):?>
                <option value="<?php echo $i;?>" <?php selected($i,$month);?> ><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            </li> 
            <li><input type="submit" value="Filter" /></li>  
            <li><input type="submit" value="export" name="metaguss_export" id="metaguss_export"/></li>
            <?php 
            if(isset($_GET['export']) && isset($filename))
            {
                echo '<a href="'.$filename.'">Downlad blogs</a>';
            }
            ?>
        </ul>
      </div>
    </div>
    <!--------Operationsbar Ends-----> 
    
    <!-------Contentarea Starts-----> 
    
    <!----Table Wrapper---->
    
    <?php 
    if(isset($posts) && !empty($posts)):
   
    
    ?>
    <div class="pmagic-table"> 
      
      <!----Sidebar---->
      
      <table class="pg-email-list">
        <tr>
          <th><?php _e('SR','profilegrid-user-profiles-groups-and-communities');?></th>
          <th><?php _e('Blog Name','profilegrid-user-profiles-groups-and-communities');?></th>
          <th><?php _e('URL','profilegrid-user-profiles-groups-and-communities');?></th>
          <th><?php _e('Publish Date','profilegrid-user-profiles-groups-and-communities');?></th>
          <th><?php _e('KeyWord','profilegrid-user-profiles-groups-and-communities');?></th>
          <th><?php _e('Organic Search','profilegrid-user-profiles-groups-and-communities');?></th>
          <th><?php _e('Users','profilegrid-user-profiles-groups-and-communities');?></th>
          <th><?php _e('Page Views','profilegrid-user-profiles-groups-and-communities');?></th>
          <th><?php _e('Bounce Rate','profilegrid-user-profiles-groups-and-communities');?></th>
          
        </tr>
        <?php
                    
			foreach($posts as $post)
			{
                            $keyword = get_post_meta($post->ID,'_yoast_wpseo_focuskw',true);
                            $analytics = get_analytics_data_new($post->ID);        
				?>
        <tr>
         
          <td><?php echo $j;?></td>
          <td><?php echo $post->post_title;?></td>
          <td><?php echo get_permalink($post->ID);?></td>
         <td><?php echo $post->post_date;?></td>
          <td><?php echo $keyword;?></td>
          <td><?php echo $analytics[0][4];?></td>
           <td><?php echo $analytics[0][1];?></td>
            <td><?php echo $analytics[0][2];?></td>
             <td><?php echo $analytics[0][3];?></td>
             
        </tr>
        <?php $j++; }?>
      </table>
    </div>
    
    <?php echo $pagination;?>
    <?php else:?>
	<div class="pm_message"><?php _e('No any blog post','profilegrid-user-profiles-groups-and-communities');?></div>
	<?php endif;?>
  </form>
</div>