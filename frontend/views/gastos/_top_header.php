<div class="row">
  <div class="col-md-6">
    <div class="small-box bg-red ">
      <div class="inner">
        <h3 id="smaller"><?= "RD$" . number_format($hoy, 2) ?></h3>
        <p>Total Hoy</p>
      </div>
      <div class="icon">
        <i class="fa fa-money"></i>
      </div>
    </div>
  </div>



  <div class="col-md-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3><?= "RD$" . number_format($mesActual, 2); ?></h3>
        <p>Total Mes Actual</p>
      </div>
      <div class="icon">
        <i class="fa fa-money"></i>
      </div>
    </div>
  </div>
</div>