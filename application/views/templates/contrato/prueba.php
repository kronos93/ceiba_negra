<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script type="text/javascript" src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: '#contrato',
            height: '600px',
            plugins: [
			    'advlist autolink lists charmap print preview hr anchor pagebreak',
			    'searchreplace wordcount visualblocks visualchars code fullscreen',
			    'nonbreaking save table directionality',
			    'template paste textcolor colorpicker textpattern'
			  ],
            toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            content_css: '../assets/css/tinymce.css'
        });
    </script>
</head>
<body>
    <textarea id="contrato"><?= $output ?></textarea>
</body>
</html>