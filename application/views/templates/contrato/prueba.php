<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script type="text/javascript" src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: '#contrato',
        });
    </script>
</head>
<body>
    <textarea id="contrato"><?= $output ?></textarea>
</body>
</html>