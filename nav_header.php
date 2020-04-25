    <nav class="navbar navbar-light fixed-top" style="background-color: #e3f2fd;">
      <a class="navbar-brand" href="#">
        <img src="images/bks/sb_72x72.png" width="30" height="30" class="d-inline-block align-top mr-0" alt="">
        <span class="mb-0" style="font-size: 17px;"><?php echo $brand_name; ?></span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          
          <li class="nav-item">
            <a class="nav-link" href="i.php">Go Home</a>
          </li>
          <div class="dropdown-divider" style="border-color:#1B8C82;"></div>

        </ul>
      </div>
    </nav>
    <script>
      $(document).ready(function () {
        //Below is to collapse navbar on clicking a link
        $(".navbar-nav li a").click(function(event) {
          $(".navbar-collapse").collapse('hide');
        });

        //Below is to collapse navbar on clicking outside
        $(document).click(function (event) {
          var clickover = $(event.target);
          var _opened = $(".navbar-collapse").hasClass("show");
          if (_opened === true && !clickover.hasClass("navbar-toggler")) {
            $(".navbar-toggler").click();
          }
        });
      });
    </script>
