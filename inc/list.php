<div class="col-md-6">
    <div class="card mt-4">
        <div class="card-header">
            <h2 class="text-center">Список тестов</h2>
        </div>

        <div class="card-body">
            <ul class="list">
                <?php
                $res = $db->query("SELECT * FROM tests");
                while ($row = $res->fetch()) {
                    ?>
                    <li><a target="_blank" rel="noopener noreferrer" href="test.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body text-center">
            <a href="index.php?do=add" class="btn btn-primary">Добавить тест</a>
        </div>
    </div>
</div>