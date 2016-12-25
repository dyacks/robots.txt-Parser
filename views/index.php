<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>robots.txt-parser</title>
</head>
<body>

<h2>indexView</h2>

<form action="index.php" method="get">
    <p>id: <input type="text" name="id" /></p>
    <button type="submit" name="act" value="getFromId">getFromId</button>
</form>
<hr />

<form action="index.php" method="get">
    <button type="submit" name="act" value="getAll">getAll</button>
</form>
<hr />

<form action="index.php" method="get">
    <p>new link: <input type="text" name="link" /></p>
    <button type="submit" name="act" value="addOne">addOne Link</button>
</form>
<hr />

<form action="index.php" method="get">
    <p>id: <input type="text" name="id" /></p>
    <p>new link: <input type="text" name="link" /></p>
    <button type="submit" name="act" value="update">update</button>
</form>
<hr />

</body>
</html>