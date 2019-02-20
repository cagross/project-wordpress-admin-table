<?
// send em thru if already logged in
if (is_user_logged_in()) {
    header ("Location: " . $_SERVER['SCRIPT_URI'] . "wp-admin/");
    exit();
}
?>
<html>
<div style="padding:3em; text-align:center;"><a href="<?=get_bloginfo('url')?>/wp-admin/">Login</a></div>
</html>