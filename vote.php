<?php
$dataFile = 'votes.json';

$defaultData = [
    'question' => 'Какую оценку хотите за экзамен?',
    'options' => [
        '5' => 0,
        'выше 4' => 0,
        'не ниже "отлично"' => 0,
        'отчислиться' => 0,
        'наивысшую оценку' => 0
    ]
];

$data = [];
if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $data = json_decode($json, true);
}

if (!$data || !isset($data['options'])) {
    $data = $defaultData;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedOption = $_POST['option'] ?? '';

    if (array_key_exists($selectedOption, $data['options'])) {
        $data['options'][$selectedOption]++;
        file_put_contents($dataFile, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
        header('Location: index.php?voted=1');
        exit;
    } else {
        $message = 'Ошибка: выбран неверный вариант.';
    }
}

$totalVotes = array_sum($data['options']);
?>