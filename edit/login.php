<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='../css/admin.css' type='text/css'>
        <link rel='stylesheet' href='../css/buttons.css' type='text/css'>
        <title>Lucid Tree: Login</title>
    </head>
    <body>
        <div class='contentLogin'>
            <form action="login-action.php" method="post">
                <fieldset>
                    <legend>Moves login</legend>
                        <p>
                            <label for="username">Username: </label><br/>
                            <input type="text" name="username" id="username" value="" />
                        </p>
                        <p>
                            <label for="password">Password: </label><br/>
                            <input type="password" name="password" id="password" value="" />
                        </p>
                        <p>
                            <label for="remember">
                                <input type="checkbox" name="remember" id="remember" value="1" /> Remember me
                            </label>
                        </p>
                </fieldset>
                <p class="loginButtons">
                    <input class="button medium gray" type="reset" value="Clear" /> <input class="button medium green" type="submit" value="Login" />
                </p>
            </form>
        </div>
    </body>
</html>
