<nav id="ust-menu" class="menu">
  <ul>
    <?php foreach($MENU as $m): ?>
      <li><a href="<?= $m['Url'] ?>"><?= $m['Baslık'] ?></a></li>
    <?php endforeach; ?>
  </ul>
</nav>
