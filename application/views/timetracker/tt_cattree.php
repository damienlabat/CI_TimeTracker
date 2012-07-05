<div class='well span6 pull-right hidden-phone'>
        <ul class="nav nav-list">
<?php    
        echo "<li";
        if ( $current['id']==NULL ) echo "  class='active'";
        echo "><a href='".tt_url($user['name'],$current['action'],$current, array( 'cat'=>NULL, 'id'=>NULL ) )."'>Home</a></li>";
?>

        <li class="nav-header">Categories</li>
<?php
    $icons=array(
        'activity'=>'icon-flag',
        'todo'=>'icon-check',
        'value'=>'icon-star'
        // todo undone icon-exclamation-sign
    );

    if ($cattree!=NULL)
    foreach( $cattree as $categorie)
    if ($categorie['nb_activities']>0) {

        if ($categorie['title']=='') $categorie['title']='(no categorie)';

        if (isset($categorie['active']))
            echo '<li class="active"><a href="#">'.$categorie['title'].'</a></li>';
        else
            echo '<li><a href="'.tt_url($user['name'],$current['action'],$current, array( 'cat'=>'categorie', 'id'=>$categorie['id'] ) ).'">'.$categorie['title'].'</a></li>';

        if (isset($categorie['activities'])) {
            echo '<ul class="nav nav-list">';
            foreach( $categorie['activities'] as $activity) {

                if (isset($activity['active']))
                    echo '<li class="active"><a href="#"><i class="icon-white ' . $icons[$activity['type_of_record']] . '"></i> ' . $activity['title'] . '</a></li>';
                else
                    echo '<li><a href="'.tt_url($user['name'],$current['action'],$current, array( 'cat'=>$activity['type_of_record'], 'id'=>$activity['id'] ) ).'"><i class="' . $icons[$activity['type_of_record']] . '"></i> ' . $activity['title'] . '</a></li>';
            }
            echo '</ul>';
        }

    }

?>
    </ul>
</div>