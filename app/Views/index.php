<div class="container">

    <div class="row">
        <div class="col-7">
            <h1><?php echo $page_caption ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Login</th>
                    <th scope="col">Email</th>
                    <th scope="col">Name</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <th scope="row"><?php echo $user['id'] ?></th>
                    <td><?php echo $user['login'] ?></td>
                    <td><?php echo $user['email'] ?></td>
                    <td><?php echo $user['name'] ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>