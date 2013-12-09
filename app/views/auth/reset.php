<?php
/**
 * Created by PhpStorm.
 * @author RobinFai <robinfai9@gmail.com>
 * @link http://bootstrap.robinfai.com
 * @license http://www.yiiframework.com/license/
 * @version $Id: reset.php 2013-12-10 07:34 robin.fai $
 */
?>
<form action="." method="post">
    <?php if (Session::has('error')) echo trans(Session::get('reason')); ?>

    <input type="hidden" name="token" value="<?php echo $token ?>">
    <input type="text" name="email">
    <input type="password" name="password">
    <input type="password" name="password_confirmation">
    <input type="submit" value="submit"/>
</form>