<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>robots.txt-parser</title>
</head>
<body>

<h2>indexView</h2>

<form action="/main/getFromId/" method="get">
    <p>id: <input type="text" name="id" /></p>
    <button type="submit" name="" value="getFromId">getFromId</button>
</form>
<hr />

<form action="/main/getAll/" method="get">
    <button type="submit" name="" value="getAll">getAll</button>
</form>
<hr />

<form action="/main/addOne/" method="get">
    <p>new link: <input type="text" name="link" /></p>
    <button type="submit" name="" value="addOne">addOne Link</button>
</form>
<hr />

<form action="/main/update/" method="get">
    <p>id: <input type="text" name="id" /></p>
    <p>new link: <input type="text" name="link" /></p>
    <button type="submit" name="" value="update">update</button>
</form>
<hr />

</body>
</html>