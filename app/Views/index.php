<h1><?php echo $page_caption ?></h1>

<ul style="list-style: none; font-size: 1.5rem;">
    <?php foreach($menu as $link => $name): ?>
        <li style="display: inline; margin-right: 5px;">
            <a href="<?php echo $link ?>"><?php echo $name ?></a>
        </li>
    <?php endforeach; ?>
</ul>