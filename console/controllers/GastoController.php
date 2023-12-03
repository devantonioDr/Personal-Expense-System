<?php

namespace console\controllers;

use frontend\models\Gastos;
use yii\console\Controller;
use yii\db\Expression;
use yii\helpers\Console;

class GastoController extends Controller
{

    public function actionIndex($count)
    {
        $this->stdout("Generating and inserting $count fake Gastos records...\n", Console::FG_YELLOW);

        $currentMonthStart = strtotime(date('Y-m-01')); // Timestamp for the start of the current month
        $currentMonthEnd = strtotime(date('Y-m-t'));    // Timestamp for the end of the current month
        
        for ($i = 0; $i < $count; $i++) {
            $gasto = new Gastos();
            $gasto->detachBehavior('timestampBehavior');
            $gasto->user_id = 1; // Set the user_id as needed
            $gasto->descripcion = 'Fake Gasto ' . ($i + 1);
            $gasto->monto = rand(10, 100);
        
            // Generate a random timestamp within the current month
            $randomTimestamp = rand($currentMonthStart, $currentMonthEnd);
            $gasto->created_at = $randomTimestamp;
        
            if ($gasto->save()) {
                $this->stdout("Inserted Gasto with ID: {$gasto->id}\n", Console::FG_GREEN);
            } else {
                $this->stderr("Error inserting Gasto:\n");
                foreach ($gasto->errors as $errors) {
                    foreach ($errors as $error) {
                        $this->stderr("  - $error\n");
                    }
                }
            }
        }
        
        $this->stdout("Done.\n", Console::FG_YELLOW);
       
    }


    
}



