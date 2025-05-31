<?php

$questionsArray = json_decode($assignment->questions);
$no = 1; $totalNilai = 0;

$assignmentResponses = isset($responses) ? $responses : [];

foreach ($questionsArray as $item) {
    $scoreProperty = "question_" . $item->id . "_score";
    $scoreValue = isset($item->$scoreProperty) ? $item->$scoreProperty : '';

    $instruksiProperty = "question_" . $item->id . "_intruksi";
    $instruksiValue = isset($item->$instruksiProperty) ? $item->$instruksiProperty : '';

    $asIDValue = isset($assignmentResponses[$item->id]['as_id']) ? $assignmentResponses[$item->id]['as_id'] : '';
    
    $nilaiValue = isset($assignmentResponses[$item->id]['nilai']) ? floatval($assignmentResponses[$item->id]['nilai']) : 0;


    echo "<div data-id='$item->id' class='form-group'>
        <div class='form-group mb-0'>
            <label class='font-weight-normal' style='background:#ddd; padding: 2px 10px;'>Question $no</label>
            <input type='text' name='question_{$item->id}_score' class='question-score' value='$scoreValue' style='width: 60px;text-align: center;' disabled='true'>
            / <input type='text' name='question_{$item->id}_nilai' class='question-score' data-assrespid='{$asIDValue}' value='{$nilaiValue}' style='width: 60px;text-align: center;'>
    </div>";

    if (isset($assignmentResponses[$item->id])) {
        $response = $assignmentResponses[$item->id];
        echo "<div class='row recordrtc'>
            <div class='col'>
                <div class='audio-container'>
                    <audio src='".base_url()."uploads/audio/".$response['file_name']."' controls='' class='rounded-0'></audio>
                </div>
            </div>
        </div>";
    }

    echo "</div>";

    $no++; $totalNilai += $nilaiValue;
}

echo "<div id='total-nilai'>
    <p class='text-center'>
        <span id='tampilkan-nilai'>T0TAL NILAI: $totalNilai</span>
    </p>
</div>";
?>