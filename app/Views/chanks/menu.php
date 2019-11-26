<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand">WebAuthApp</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php foreach($menu as $link => $name): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $link ?>"><?php echo $name ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
</div>