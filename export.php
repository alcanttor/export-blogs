<?php 

$path =  plugin_dir_url(__FILE__); 
$identifier = 'EMAIL_TMPL';
$pagenum = filter_input(INPUT_GET, 'pagenum');
$pagenum = isset($pagenum) ? absint($pagenum) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$i = 1 + $offset;
$year = filter_input(INPUT_GET,'year');
$month = filter_input(INPUT_GET,'month');
// ... set up your argument array for WP_Query:
$filename = 'export-blogs_'.$year.'-'.$month.'.xls';
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

$posts = get_posts( $args );

$num_of_pages = ceil( $total_posts/$limit);
$pagination = pm_get_pagination($num_of_pages,$pagenum);


$table = '<table class="wp-list-table widefat fixed" cellspacing="0">
  <thead>
    <tr>';
	
    $table .='<th scope="col" style="">Blog Name</th>';
    $table .='<th scope="col" style="">URL</th>';
    $table .='<th scope="col" style="">Publish Date</th>';
    $table .='<th scope="col" style="">Keyword</th>';
   
   $table .=' </tr>
  </thead>
  <tfoot>
     
  </tfoot>
  <tbody id="the-list">';
 
  $k=1;
  foreach($posts as $post)
  {
	if($k%2==0)
	{
		$class="";
	}
	else
	{
		$class="alternate";
	}
	 $table .= '<tr class="'.$class.'">';
	$keyword = get_post_meta($post->ID,'_yoast_wpseo_focuskw',true);
                             
        $table .='<th scope="row" style="">'.$post->post_title.'</th>';
        $table .='<th scope="row" style="">'.get_permalink($post->ID).'</th>';
	$table .='<th scope="row" style="">'.$post->post_date.'</th>';
        $table .='<th scope="row" style="">'.$keyword.'</th>';
	
	$table .='</tr>';
$k++;
} 
    
$table .='  </tbody>
</table>';

file_put_contents($filename,$table);
wp_redirect($filename);
?>