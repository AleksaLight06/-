<?php
require 'vote.php';
$alreadyVoted = isset($_GET['voted']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Опрос</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 20px auto; }
        .poll-container { border: 1px solid #ccc; padding: 20px; border-radius: 8px; background-color: #f9f9f9; }
        .result-item { margin-bottom: 15px; }
        .result-label { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 14px; }
        .progress-bg { background-color: #e0e0e0; border-radius: 4px; height: 20px; overflow: hidden; }
        .progress-fill { 
            background-color: #4CAF50; 
            height: 100%; 
            transition: width 0.5s ease; 
            display: flex; 
            align-items: center; 
            padding-left: 5px; 
            color: white; 
            font-size: 12px; 
            box-sizing: border-box;
        }
        .chart-container {
            display: flex;
            align-items: flex-end;
            gap: 20px;
            height: 200px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
        }
        .bar-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            height: 100%;
            justify-content: flex-end;
        }
        .bar {
            width: 100%;
            background-color: #2196F3;
            transition: height 0.5s ease;
            border-radius: 4px 4px 0 0;
            min-height: 2px;
        }
        .bar-label { font-size: 12px; margin-top: 5px; text-align: center; }
        .voted-msg { color: green; font-weight: bold; margin-bottom: 15px; }
        .reset-link { display: block; margin-top: 20px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>

<div class="poll-container">
    <h2><?php echo htmlspecialchars($data['question']); ?></h2>

    <?php if ($alreadyVoted): ?>
        <p class="voted-msg">Спасибо! Ваш голос учтен.</p>
    <?php endif; ?>

    <?php if ($message): ?>
        <p style="color: red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if (!$alreadyVoted): ?>
        <form method="POST" action="index.php">
            <?php foreach ($data['options'] as $option => $votes): ?>
                <div style="margin-bottom: 10px;">
                    <label>
                        <input type="radio" name="option" value="<?php echo htmlspecialchars($option); ?>" required>
                        <?php echo htmlspecialchars($option); ?>
                    </label>
                </div>
            <?php endforeach; ?>
            <button type="submit" style="margin-top: 10px; padding: 8px 16px; cursor: pointer;">Голосовать</button>
        </form>
    <?php endif; ?>

    <h3>Статистика (<?php echo $totalVotes; ?> голосов)</h3>
    <?php foreach ($data['options'] as $option => $votes): ?>
        <?php $percent = $totalVotes > 0 ? round(($votes / $totalVotes) * 100) : 0; ?>
        <div class="result-item">
            <div class="result-label">
                <span><?php echo htmlspecialchars($option); ?></span>
                <span><?php echo $votes; ?> (<?php echo $percent; ?>%)</span>
            </div>
            <div class="progress-bg">
                <div class="progress-fill" style="width: <?php echo $percent; ?>%;">
                    <?php if ($percent > 10) echo $percent . '%'; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <h3>Диаграмма</h3>
    <div class="chart-container">
        <?php $maxVotes = max($data['options']) ?: 1; ?>
        <?php foreach ($data['options'] as $option => $votes): ?>
            <?php $heightPercent = ($votes / $maxVotes) * 100; ?>
            <div class="bar-wrapper">
                <div class="bar" style="height: <?php echo $heightPercent; ?>%;" title="<?php echo $votes; ?> голосов"></div>
                <div class="bar-label"><?php echo htmlspecialchars($option); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($alreadyVoted): ?>
        <a href="index.php" class="reset-link">Вернуться к голосованию</a>
    <?php endif; ?>
</div>

</body>
</html>