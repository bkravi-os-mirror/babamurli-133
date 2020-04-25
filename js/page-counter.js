function pageCounter(source) {
    if(!$.active) {
      $.ajax({
        url: "/execute_dbcounter.php",
        type: "POST",
        contentType: "application/json",
        //Note: below using JSON.stringify which will use JSON formatting while sending this POST data to server rather than using
        //      'form encoding'. The purpose to use JSON.stringify & not 'form encoding' because, at server side I am reading POST body
        //      using $data = json_decode(file_get_contents("php://input")); code snippet. And this must require we send POST body in JSON
        //      formatting rather than 'form formatting'. If you use form formatting, then probably at serve side you need to use _POST[]
        //      or _GET[] kind of coding to read POST body
        data: JSON.stringify({"source" : source}),
        cache: false,
        
        complete: function(jqXHR, textStatus) {
          //console.log('completed');
        },
        
        success: function (response) {
          //console.log(response);
        },
        
        error: function(xhr, error) {
          console.debug(error);
        }
      });
    }
}
