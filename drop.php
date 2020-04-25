<?php
  //NOTE: create a Dropbox App first in Dropbox App Console. And get App key, App secret & Access token
  //Appconsole: https://www.dropbox.com/developers/apps?_tk=pilot_lp&_ad=topbar4&_camp=myapps
  //This library reference: https://github.com/kunalvarma05/dropbox-php-sdk
  //More on these API https://kunalvarma05.github.io/dropbox-php-sdk/master/Kunnu/Dropbox/Models/FolderMetadata.html#method_getData
  //Dropbox a/c: brahmakumaris.easy@gmail.com
  //Folders: babamurli-133, bkdrluhar-42, omshantiworld-18
  
  error_reporting(E_ALL);
  ini_set('display_errors',1);
  ini_set('display_startup_errors',1);
  error_reporting(-1);  
  
  include './util.php';
  require_once './compo/vendor-dropbox/autoload.php';
  
  use Kunnu\Dropbox\Dropbox;
  use Kunnu\Dropbox\DropboxApp;
  
  //[App key, App secret & Access token]
  $app = new DropboxApp("4mgqnq80n6fduun", "vr72giu9mdi2izt", "NX5sGqKCzhAAAAAAAAAAM5dHN7iLMlEAKsI2Sc9Lwv9gE6YAjmQ34KEbOAvWUsbY");
  $dropbox = new Dropbox($app);
  
  //creating folders inside dropbox root
  /*
  $result = $dropbox->createFolder("/omshantiworld-18");
  echo "===========<br>";
  foreach($result->getData() as $key => $value) {
    echo "$key => $value<br>";
  }
  */
  
  //getting MetaData of a file/folder stored in dropbox
  $result = $dropbox->getMetadata("/babamurli-133");
  echo "===========<br>";
  foreach($result->getData() as $key => $value) {
    echo "$key => $value<br>";
  }
  
  //Uploading a file into dropbox inside folder "babamurli-133"
  $file = "./test_dropbox.txt";
  file_put_contents($file, "testing file upload-download");
  $result = $dropbox->upload($file, "/babamurli-133/" . fileWithExt($file));
  echo "===========<br>";
  foreach($result->getData() as $key => $value) {
    echo "$key => $value<br>";
  }
  unlink($file);
  
  //Downloading a file from dropbox folder "babamurli-133"
  $file = "./test_dropbox.txt";
  $result = $dropbox->download("/babamurli-133/" . fileWithExt($file));
  echo "===========<br>";
  foreach($result->getData() as $key => $value) {
    echo "$key => $value<br>";
  }
  if(file_put_contents("$file", $result->getContents())) {
    echo "<br>File written: $file<br>";
  } else {
    echo "<br>Couln't write into: $file<br>";
  }

  echo "<br>Done!";
?>