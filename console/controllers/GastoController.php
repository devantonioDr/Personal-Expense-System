<?php

namespace console\controllers;

use common\models\Gastos\Gastos;
use Yii;
use yii\console\Controller;

class GastoController extends Controller
{
    public function actionIndex()
    {
        // Set the time zone to America/Santo_Domingo
        date_default_timezone_set('America/Santo_Domingo');

        // Fetch all records with non-null created_at
        $gastos = Gastos::find()->where(['is not', 'created_at', null])->all();

        var_dump(count($gastos));

        foreach ($gastos as $gasto) {
            // Convert Unix timestamp to DateTime object
            $dateTime = (new \DateTime("@{$gasto->created_at}"))->setTimezone(new \DateTimeZone('America/Santo_Domingo'));
            // Format the date to YYYY-MM-DD
            $formattedDate = $dateTime->format('Y-m-d');

            // Update the fecha_pago field
            $gasto->fecha_pago = $formattedDate;

            // Save the record
            if (!$gasto->save()) {
                var_dump($gasto->errors);
                // Log the error if saving fails
                Yii::error("Failed to save gasto ID {$gasto->id}: " . json_encode($gasto->errors));
            }
        }

        echo "Conversion complete. Updated records: " . count($gastos) . "\n";
    }
}
