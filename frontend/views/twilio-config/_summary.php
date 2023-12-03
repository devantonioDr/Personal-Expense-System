<?php
?>
<div class="row">
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>$ <?= number_format($total_spent,2); ?></h3>

                <p>Total Gastado Hoy</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <?php //echo Html::a('Ver todos <i class="fa fa-arrow-circle-right"></i>', ['/donations-history?DonationsHistorySearch%5Bclient_id%5D=&DonationsHistorySearch%5Bamount%5D=&DonationsHistorySearch%5Bstatus%5D=1&DonationsHistorySearch%5Bresponse_code%5D=&DonationsHistorySearch%5Bip%5D=&DonationsHistorySearch%5BtransactionID%5D=&DonationsHistorySearch%5BordenID%5D=&DonationsHistorySearch%5Bcreated_by%5D='], ["class" => 'small-box-footer seeAll']) ?>
        </div>
    </div>
    <!-- ./col -->

</div>