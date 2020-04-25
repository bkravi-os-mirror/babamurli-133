<?php 
  
  //Below code downloads the PDF file saved in Google drive under public shared folder
  //We need Google API key of the project ravinimbus@gmail.com where "Google Drive" API is enabled
  phpinfo();
  /*
  require_once '../libs/vendor-google-api/autoload.php';
  
  $client = new Google_Client();
  $client->setApplicationName("Project From ravinimbus@gmail.com");
  $client->setDeveloperKey("AIzaSyDMB0wLYBS5wZ3VtcYSF6Vrn8vDDEZqGok");   //Google API key of the project ravinimbus@gmail.com where "Google Drive" API is enabled

  $driveService = new Google_Service_Drive($client);
  
  $fileId = '0B4qWz7YYoTVpOHdLanY1OVBGXzQ';   //This is the fileID of the Murli PDF stored in public shared folder in google drive
  $response = $driveService->files->get($fileId, array('alt' => 'media'));   //'alt' => 'media' means we are asking to download the file
  $content = $response->getBody()->getContents();
  file_put_contents("murli.pdf", $content);
  */
?>
