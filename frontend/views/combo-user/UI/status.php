<?php
use frontend\models\ComboUser;

if ($status === ComboUser::IS_FINISHED) {
    echo '<span class="label label-success">Subido</span>';
} elseif ($status === ComboUser::IS_PENDING) {
    echo '<span class="label label-warning">Pendiente</span>';
} elseif ($status === ComboUser::IS_BEING_PROCESSED) {
    echo '<span class="label label-danger">Siendo procesado...</span>';
}
