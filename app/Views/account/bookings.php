<?php
/** @var array $bookings */
?>

<div class="account-content">
    <h1>Мои бронирования</h1>
    
    <?php if (!empty($bookings)): ?>
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Даты</th>
                    <th>Тип</th>
                    <th>Гости</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= $booking['id_query'] ?></td>
                        <td>
                            <?= date('d.m.Y', strtotime($booking['check_in'])) ?> - 
                            <?= date('d.m.Y', strtotime($booking['check_out'])) ?>
                        </td>
                        <td><?= htmlspecialchars($booking['type']) ?></td>
                        <td><?= $booking['peoples'] ?></td>
                        <td class="status-<?= $booking['status'] ?>">
                        <?= $getBookingStatus($booking['status']) ?>
                        </td>
                        <td>
                            <a href="/booking/<?= $booking['id_query'] ?>" class="btn-view">Подробнее</a>
                            <?php if ($booking['status'] === 'pending'): ?>
                                <a href="/booking/cancel/<?= $booking['id_query'] ?>" class="btn-cancel">Отменить</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>У вас пока нет бронирований. <a href="/rooms">Посмотреть номера</a></p>
    <?php endif; ?>
</div>