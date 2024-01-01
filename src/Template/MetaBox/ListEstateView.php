<div class="estates-container">
    <?php foreach ($estates as $estate) : ?>
        <div class="estate-card">
            <img src="<?= $estate['image_url']; ?>" alt="Изображение" class="estate-image" />
            <div class="estate-info">
                <h3 class="estate-title"><?= $estate['title']; ?></h3>
                <p class="estate-description"><?= $estate['description']; ?></p>
            </div>
            <div class="estate-action-bar">
                <a href="<?= $estate['edit_link']; ?>" class="estate-action-link"><span class="dashicons dashicons-edit"></span></a>
                <a href="<?= $estate['view_link']; ?>" class="estate-action-link"><span class="dashicons dashicons-visibility"></span></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?page=<?= $i; ?>" class="page-link <?= $i == $currentPage ? 'active' : ''; ?>">
            <?= $i; ?>
        </a>
    <?php endfor; ?>
</div>