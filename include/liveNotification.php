<?php
require_once(__DIR__ . "/initialize.php");

function sendToLiveNotification($refNumber) {
  global $mysqli;
  
  $options = array(
    'cluster' => PUSHER_APP_CLUSTER,
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    PUSHER_APP_KEY,
    PUSHER_APP_SECRET,
    PUSHER_APP_ID,
    $options
  );

  $data['message'] = 'A new service request (ID: ' . $refNumber . ') for plot maintenance has been created.';
  $data['title'] = 'New Service Request';
  $data['type'] = 'success';
  $pusher->trigger('admin-channel', 'new-order', $data);

  $mysqli->query("INSERT INTO tbl_notifications (title, message, type, is_read) 
  VALUES ('" . $data['title'] . "', '" . $data['message'] . "', '" . $data['type'] . "', 0)");
}



  // $options = array(
//     'cluster' => 'PUSHER_APP_CLUSTER',
//     'useTLS' => true
//   );

// $pusher = new Pusher\Pusher(
//     'PUSHER_APP_KEY',
//     'PUSHER_APP_SECRET',
//     'PUSHER_APP_ID',
//     $options
// );


// // Example data to send
// $data = ['message' => 'New order received!'];
// $pusher->trigger('my-channel', 'my-event', $data);
?>
