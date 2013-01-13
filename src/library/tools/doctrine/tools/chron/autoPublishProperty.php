<?php
/*
 * This chron job will automatically publish/unpublish a property
 * based on the fields published_from, published_to
 *
 * Finalized bookings are going to be visible in History
 */
require_once('../Bootstrap.php');

/*Get Workorders with status completed and date_updated > 1 day old*/
echo "Comenzando autoPublishProperty: " . date(DATE_RFC822). "\n\n";

//Publish property 
$unpublished_prop = Doctrine_Query::create()
                ->from('Property p')
                ->where('p.published_from >= NOW()')
                ->andWhereNotIn('p.status', array('draft','published'))
                ->execute();
if($unpublished_prop->count() > 0){
    foreach ($unpublished_prop as $uprop) {
        $uprop->status = 'published';
        try {
            $uprop->save();
            echo 'Propiedad '. $uprop->code ." fue publicada\n";
        } catch(Doctrine_Connection_Mysql_Exception $e){
            echo $e->getMessage();
//            file_put_contents('chron.log', $e->getMessage(), FILE_APPEND);
        }
    }
} else {
    echo "No hay propiedades a publicar\n\n";
}

//Unublish property
$published_prop = Doctrine_Query::create()
                ->from('Property p')
                ->where('p.published_to <= NOW()')
                ->andWhereNotIn('p.status', array('draft','unpublished'))
                ->execute();
if($published_prop->count() > 0){
    foreach ($published_prop as $prop) {
        $prop->status = 'unpublished';
        try {
            $prop->save();
            echo 'Propiedad '. $uprop->code ." fue despublicada\n";
        } catch(Doctrine_Connection_Mysql_Exception $e){
            echo $e->getMessage();
//            file_put_contents('chron.log', $e->getMessage(), FILE_APPEND);
        }
    }
} else {
    echo 'No hay propiedades a despublicar';
}

?>