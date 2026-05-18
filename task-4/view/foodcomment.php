<?php while($row = mysqli_fetch_assoc($posts)){ ?>

<div style="background:#fff; padding:15px; margin:15px; border-radius:8px;">

    <h3><?= $row['title'] ?></h3>
    <p><?= $row['content'] ?></p>

    <hr>

    <h4>Comments</h4>

    <?php
    $comments = getCommentsByPost($row['id']);
    while($c = mysqli_fetch_assoc($comments)){
    ?>

        <p>
            <b><?= $c['name'] ?>:</b> <?= $c['comment'] ?>

            <a href="../controller/FoodexperienceController.php?delete_comment=<?= $c['id'] ?>"
               style="color:red; margin-left:10px;">
               delete
            </a>
        </p>

    <?php } ?>

    <!-- ADD COMMENT -->
    <form method="post" action="../controller/FoodexperienceController.php">
        <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
        <input type="text" name="comment" placeholder="Write comment..." required>
        <button name="add_comment">Comment</button>
    </form>

</div>

<?php } ?>