<?php
/*
 * This chron job will change status to 8 (Finalized)
 * for bookings that have date_checkout later than today
 *
 * Finalized bookings are going to be visible in History
 */
require_once('../Bootstrap.php');

/*Get Workorders with status completed and date_updated > 1 day old*/
echo "Comenzando archivedFinalizedBookings: " . date(DATE_RFC822). "\n\n";
$confirmed_bookings = Doctrine_Query::create()
                ->from('Booking b')
                ->where('b.date_checkout >= NOW()')
                ->andWhere('b.booking_status_id = ?', '3')
                ->execute();
if($confirmed_bookings->count() > 0){
    foreach ($confirmed_bookings as $bo) {
        $bo->booking_status_id = 8;
        try {
            $bo->save();
            echo 'Reserva de'. $bo->Client->fullName ." fue finalizado\n";
        } catch(Doctrine_Connection_Mysql_Exception $e){
            echo $e->getMessage();
//            file_put_contents('chron.log', $e->getMessage(), FILE_APPEND);
        }
    }
} else {
    echo "No hay reservas para finalizar\n";
}
?>