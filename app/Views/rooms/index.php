<h1>Комнаты</h1>
<ul>
<?php foreach ($rooms as $room): ?>
    <li><?= htmlspecialchars($room['name']) ?></li>
<?php endforeach; ?>
</ul>
