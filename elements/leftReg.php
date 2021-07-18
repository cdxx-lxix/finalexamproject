<div class="leftcolumn">
    <div class="card" style="text-align: center">
        <h2>Welcome, new user!</h2>
        <p>Fill this form to complete the registration process</p>
        <p><b>Fields with * are required and ergo can't be empty</b></p>
        <form action="processors/registProcessor.php" method="POST" class="MyRegistration">
            <input type="text" name="username" required placeholder="Username *">
            <input type="email" name="email" required placeholder="Email *">
            <input type="password" name="password1" required placeholder="Password *">
            <input type="password" name="password2" required placeholder="Password repeat *">
            <input type="text" name="realname" placeholder="Real name">
            <input type="date" name="birthday" placeholder="Birthday">

            <input type="submit" name="submit" value="Sign up!" class="btn btn-primary">
        </form>
    </div>
</div>