
<?php
if(isset($_SESSION["success"])){?>

<div class="toast show align-items-center text-bg-success border-0 position-absolute" style="top:10vh; right: 10vw;" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
      <?php
        echo $_SESSION["success"];
        unset($_SESSION["success"]);
      ?>
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>


<?php
}
else if(isset($_SESSION["message"])){?>
<div class="toast show align-items-center text-bg-primary border-0 position-absolute" style="top:10vh; right: 10vw;" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
      <?php
        echo $_SESSION["message"];
        unset($_SESSION["message"]);

      ?>
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>

<?php
}
else if(isset($_SESSION["error"])){?>
<div class="toast show align-items-center text-bg-danger border-0 position-absolute" style="top:10vh; right: 10vw;" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
      <?php
        echo $_SESSION["error"];
        unset($_SESSION["error"]);

      ?>
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>
    

<?php   
}
?>