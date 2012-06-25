<?php

  	$list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();

    $data = array();
    foreach ($list as $abbr => $info) {
        foreach ($info as $zone) {        	
            if ( preg_match( '/^(America|Antartica|Arctic|Asia|Atlantic|Europe|Indian|Pacific)\//', $zone['timezone_id'] )) {            	
                $z = new DateTimeZone($zone['timezone_id']);
                $c = new DateTime(null, $z);
                $zone['time'] = $c->format('H:i');
                $sp= preg_split('/\//i', $zone['timezone_id']);
                $zone['region'] = isset($sp[0]) ? $sp[0] : ''; 
                $zone['city'] = isset($sp[1]) ? $sp[1] : '';               
                $data[] = $zone;
            }
        }
    }

    function data_sort($a,$b) {
	return $a['timezone_id'] > $b['timezone_id'];
	}

	usort($data, 'data_sort' );


    $options = array();
    foreach ($data as $key => $row) {    	
        $options[$row['region']][$row['city']] = array(
        	'value'=>$row['timezone_id'],
        	'title' => $row['city']
        		. ' ' . timezone2UTCdiff($row['timezone_id']).'GMT'
                . ' (' . $row['time'] . ')'
              );
    }


?>

<?= form_open( 'tt/' . $user['name'] . '/settings' , array(
	     'id' => 'classicform',     
	     'class' => "form-horizontal"
	) ) ?>
	<fieldset>

	  <div class="control-group">
	    <label for="timezone" class="control-label">timezone</label>
	    <div class="controls">
	      <select id="timezone" name='timezone'>
	        <?
	        foreach ($options as $TZ_region => $TZ_cities ) {
	        	echo '<optgroup label="'.$TZ_region.'">';
	        	foreach ($TZ_cities as $TZ_city) {
					echo "<option value='".$TZ_city['value']."'";
					if ( $TZ_city['value'] ==  $user['timezone'] ) echo " selected='selected'";
					echo ">".$TZ_city['title']."</option>";
				}
				echo '</optgroup>';
			}

	        ?>
	      </select>
	      <?php

				$z = new DateTimeZone($user['timezone']);
                $c = new DateTime(null, $z);

	      ?>
	      current: <?=$user['timezone']?> <?=timezone2UTCdiff($user['timezone'])?>GMT (<?=$c->format('H:i')?>) 
	    </div>
	  </div>


	  <div class="control-group">
	    <label for="language" class="control-label">language</label>
	    <div class="controls">
	      <select id="language" name='param_language'>
	        <option value='en'>english</option>
	      </select>
	    </div>
	  </div>
	 



	  <div class="form-actions">
	    <button class="btn btn-primary" type="submit">Save changes</button>
	  </div>

	</fieldset>
</form>