<?php
$selector = $_GET['selector'];
$validator = $_GET['validator'];
?>

<div class="leftcolumn">
    <div class="card" style="text-align: center">
        <h2>Create new password</h2>
        <p>You entered correct credentials and now it's time to make a new password</p>
        <p><b>Both fields are required</b></p>
        <form action="processors/changeProcessor.php" method="POST" class="MyRegistration">
            <input type="hidden" name="selector" value="<?php echo $selector ?>">
            <input type="hidden" name="validator" value="<?php echo $validator ?>">
            <input type="password" name="password1" required placeholder="New password">
            <input type="password" name="password2" required placeholder="Repeat new password">

            <input type="submit" name="submit" value="Reset password" class="btn btn-primary">
        </form>
    </div>
</div>