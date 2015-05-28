<h2>Tags</h2>

<?php foreach($tags as $tag): ?>

<a href = "<?= $this->url->create("questions/tagged/" . $tag->title); ?>" class = "tag left"><?= $tag->title; ?> (<?= $tag->uses; ?>)</a>

<?php endforeach; ?>

